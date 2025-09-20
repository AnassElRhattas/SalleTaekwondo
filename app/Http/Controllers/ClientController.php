<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = Client::orderBy('created_at', 'desc');
        
        // Filtrer par groupe si spécifié
        if ($request->has('group') && $request->group != 'all') {
            $query->where('group', $request->group);
        }
        
        // Recherche par nom si spécifié
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $clients = $query->paginate(10);
        
        // Conserver les paramètres de recherche dans la pagination
        $clients->appends($request->except('page'));
        
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:clients,name',
            'birth_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'group' => 'required|string|in:Box,Taekwondo,Karaté',
            'profile_picture' => 'nullable|image|max:2048',
            'Birth_contract' => 'nullable|image|max:2048',
            'weight' => 'nullable|numeric|between:0,999.99',
            'height' => 'nullable|numeric|between:0,999.99',
            'registration_date' => 'required|date'
        ]);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('profile-pictures', 'public');
        }
        if ($request->hasFile('Birth_contract')) {
            $validated['Birth_contract'] = $request->file('Birth_contract')->store('Birth-contract', 'public');
        }

        $validated['payer_abon'] = now();

        // Créer le client avec la date d'inscription personnalisée
        $client = new Client($validated);
        $client->created_at = Carbon::parse($validated['registration_date']);
        $client->save();

        return redirect()->route('clients.index')
            ->with('success', 'Client added successfully!');
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:clients,name,' . $client->id,
            'birth_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'group' => 'required|string|in:Box,Taekwondo,Karaté',
            'profile_picture' => 'nullable|image|max:2048',
            'weight' => 'nullable|numeric|between:0,999.99',
            'height' => 'nullable|numeric|between:0,999.99',
            'registration_date' => 'nullable|date'
        ]);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        // Mettre à jour la date d'inscription si fournie
        if ($request->has('registration_date') && $request->registration_date) {
            $client->created_at = Carbon::parse($validated['registration_date']);
            unset($validated['registration_date']); // Retirer du tableau pour éviter l'erreur de colonne inexistante
        }

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully!');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully!');
    }

    public function trash(): View
    {
        $trashedClients = Client::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);
        
        return view('clients.trash', compact('trashedClients'));
    }

    public function restore($id): RedirectResponse
    {
        $client = Client::onlyTrashed()->findOrFail($id);
        $client->restore();

        return redirect()->route('clients.trash')
            ->with('success', 'Client restored successfully!');
    }

    public function forceDelete($id): RedirectResponse
    {
        $client = Client::onlyTrashed()->findOrFail($id);
        $client->forceDelete();

        return redirect()->route('clients.trash')
            ->with('success', 'Client permanently deleted!');
    }

    public function validatePayment(Client $client): RedirectResponse
    {
        $client->payer_abon = Carbon::parse($client->payer_abon)->addMonth();
        $client->save();

        // Générer et envoyer le reçu via WhatsApp
        $this->generateAndSendReceipt($client);

        return redirect()->route('clients.index')
            ->with('success', 'Payment validated successfully! Receipt sent via WhatsApp.');
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