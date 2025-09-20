<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class PaymentTrackingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Client::orderBy('name', 'asc');
        
        // Filtrer par groupe si spécifié
        if ($request->has('group') && $request->group != 'all') {
            $query->where('group', $request->group);
        }
        
        $clients = $query->get();
        
        // Générer les mois de septembre 2025 à septembre 2026
        $startMonth = Carbon::create(2025, 9, 1)->startOfMonth();
        $endMonth = Carbon::create(2026, 9, 1)->startOfMonth();
        $months = [];
        
        while ($startMonth->lte($endMonth)) {
            $months[] = [
                'date' => $startMonth->copy(),
                'name' => $startMonth->translatedFormat('m - F Y')
            ];
            $startMonth->addMonth();
        }
        
        // Préparer les données de paiement pour chaque client
        $clientsWithPayments = $clients->map(function ($client) use ($months) {
            $lastPaymentDate = $client->payer_abon ? Carbon::parse($client->payer_abon) : null;
            $registrationDate = Carbon::parse($client->created_at);
            
            $paymentStatus = [];
            
            foreach ($months as $month) {
                $monthDate = $month['date'];
                $status = 'unpaid';
                
                // Vérifier si le mois est antérieur à la date d'inscription
                // On utilise startOfMonth() pour comparer uniquement les mois, pas les jours
                $monthStartDate = $monthDate->copy()->startOfMonth();
                $registrationStartMonth = $registrationDate->copy()->startOfMonth();
                
                if ($monthStartDate->lt($registrationStartMonth)) {
                    $status = 'not_registered';
                } else if ($lastPaymentDate) {
                    // Un client est considéré comme payé pour tous les mois jusqu'à son dernier paiement
                    $lastPaymentMonth = $lastPaymentDate->copy()->startOfMonth();
                    
                    if ($monthStartDate->lte($lastPaymentMonth)) {
                        $status = 'paid';
                    } elseif ($monthStartDate->lte(Carbon::now()->startOfMonth())) {
                        $status = 'overdue';
                    }
                } elseif ($monthStartDate->lte(Carbon::now()->startOfMonth())) {
                    $status = 'overdue';
                }
                
                $paymentStatus[] = [
                    'month' => $month,
                    'status' => $status
                ];
            }
            
            return [
                'client' => $client,
                'payments' => $paymentStatus
            ];
        });
        
        return view('payments.tracking', [
            'clientsWithPayments' => $clientsWithPayments,
            'months' => $months
        ]);
    }
    
    public function validateMonthPayment(Request $request, Client $client)
    {
        $monthDate = Carbon::createFromFormat('Y-m-d', $request->month_date);
        
        // Si le client n'a jamais payé ou si la date de paiement est antérieure au mois demandé
        if (!$client->payer_abon || Carbon::parse($client->payer_abon)->lt($monthDate)) {
            $client->payer_abon = $monthDate;
            $client->save();
            
            // Générer et envoyer le reçu via WhatsApp
            $this->generateAndSendReceipt($client);
        }
        
        return redirect()->back()->with('success', 'Paiement validé pour ' . $client->name . '. Reçu envoyé via WhatsApp.');
    }

    /**
     * Générer un reçu et l'envoyer via WhatsApp
     */
    private function generateAndSendReceipt(Client $client): void
    {
        try {
            // Vérifier si le client a un numéro de téléphone
            if (empty($client->phone)) {
                \Log::warning("Client {$client->name} n'a pas de numéro de téléphone pour l'envoi du reçu");
                return;
            }

            $receiptService = new \App\Services\ReceiptService();
            $whatsappService = new \App\Services\WhatsAppAutomationService();

            // Générer le reçu PDF
            $receiptData = $receiptService->generateReceipt($client);
            
            // Formater le message d'accompagnement pour WhatsApp
            $whatsappMessage = $receiptService->formatReceiptForWhatsApp($client);

            // Vérifier si le service WhatsApp est disponible
            if ($whatsappService->isServiceAvailable() && $whatsappService->isWhatsAppConnected()) {
                // Envoyer d'abord le message de confirmation
                $messageResult = $whatsappService->sendMessage($client->phone, $whatsappMessage);
                
                if ($messageResult['success']) {
                    // Ensuite envoyer le fichier PDF
                    $fileResult = $whatsappService->sendPDFFile(
                        $client->phone, 
                        $receiptData['path'], 
                        "🧾 Voici votre reçu officiel de paiement (PDF)"
                    );
                    
                    if ($fileResult['success']) {
                        \Log::info("Reçu PDF envoyé avec succès à {$client->name} ({$client->phone})");
                    } else {
                        \Log::error("Erreur lors de l'envoi du PDF à {$client->name}: " . $fileResult['error']);
                    }
                } else {
                    \Log::error("Erreur lors de l'envoi du message à {$client->name}: " . $messageResult['error']);
                }
            } else {
                \Log::warning("Service WhatsApp non disponible pour l'envoi du reçu à {$client->name}");
            }

        } catch (\Exception $e) {
            \Log::error("Erreur lors de la génération/envoi du reçu pour {$client->name}: " . $e->getMessage());
        }
    }
}