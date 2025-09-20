<?php

namespace App\Services;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptService
{
    /**
     * Générer un reçu de paiement PDF pour un client
     */
    public function generateReceipt(Client $client): array
    {
        $receiptNumber = 'REC-' . date('Y') . '-' . str_pad($client->id, 4, '0', STR_PAD_LEFT) . '-' . time();
        $currentDate = Carbon::now()->format('d/m/Y');
        $paymentDate = Carbon::parse($client->payer_abon)->format('d/m/Y');
        $nextPaymentDate = Carbon::parse($client->payer_abon)->addMonth()->format('d/m/Y');
        
        // Données pour le template
        $data = [
            'receiptNumber' => $receiptNumber,
            'currentDate' => $currentDate,
            'paymentDate' => $paymentDate,
            'nextPaymentDate' => $nextPaymentDate,
            'client' => $client
        ];
        
        // Générer le PDF
        $pdf = Pdf::loadView('receipts.receipt-template', $data);
        $pdf->setPaper('A4', 'portrait');
        
        // Sauvegarder le PDF
        $filename = "receipts/receipt_{$receiptNumber}.pdf";
        $pdfContent = $pdf->output();
        Storage::disk('public')->put($filename, $pdfContent);
        
        // Retourner les informations du fichier PDF
        return [
            'filename' => $filename,
            'path' => Storage::disk('public')->path($filename),
            'url' => Storage::disk('public')->url($filename),
            'receiptNumber' => $receiptNumber
        ];
    }
    
    /**
     * Formater le reçu pour WhatsApp (version courte)
     */
    public function formatReceiptForWhatsApp(Client $client): string
    {
        $receiptNumber = 'REC-' . date('Y') . '-' . str_pad($client->id, 4, '0', STR_PAD_LEFT) . '-' . time();
        $currentDate = Carbon::now()->format('d/m/Y');
        $paymentDate = Carbon::parse($client->payer_abon)->format('d/m/Y');
        $nextPaymentDate = Carbon::parse($client->payer_abon)->addMonth()->format('d/m/Y');
        
        return "🥋 *REÇU DE PAIEMENT - CLUB TAEKWONDO*

📋 *Reçu N°:* {$receiptNumber}
📅 *Date:* {$currentDate}

👤 *Client:* {$client->name}
🥋 *Groupe:* {$client->group}

💰 *PAIEMENT CONFIRMÉ* ✅
📅 *Payé le:* {$paymentDate}
📅 *Prochaine échéance:* {$nextPaymentDate}

Merci pour votre paiement ! 💪
Continuez à vous entraîner dur !

_Ce message confirme votre paiement d'abonnement mensuel._";
    }
}