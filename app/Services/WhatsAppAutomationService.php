<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppAutomationService
{
    private string $baseUrl;
    private int $timeout;
    
    public function __construct()
    {
        $this->baseUrl = config('services.whatsapp.base_url', 'http://localhost:3000');
        $this->timeout = config('services.whatsapp.timeout', 30);
    }
    
    /**
     * Vérifier le statut de connexion du service WhatsApp
     */
    public function getStatus(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->baseUrl . '/status');
            
            if ($response->successful()) {
                return $response->json();
            }
            
            throw new Exception('Erreur lors de la vérification du statut: ' . $response->status());
        } catch (Exception $e) {
            Log::error('WhatsApp Service Status Error: ' . $e->getMessage());
            return [
                'connected' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Envoyer un fichier PDF via WhatsApp
     */
    public function sendPDFFile(string $number, string $filePath, string $caption = ''): array
    {
        try {
            // Nettoyer et formater le numéro
            $cleanNumber = $this->formatPhoneNumber($number);
            
            // Vérifier que le fichier existe
            if (!file_exists($filePath)) {
                throw new Exception("Le fichier PDF n'existe pas: {$filePath}");
            }
            
            $response = Http::timeout($this->timeout)
                ->attach('file', file_get_contents($filePath), basename($filePath))
                ->post($this->baseUrl . '/send-file', [
                    'number' => $cleanNumber,
                    'caption' => $caption
                ]);
            
            if ($response->successful()) {
                $result = $response->json();
                Log::info("Fichier PDF WhatsApp envoyé à {$cleanNumber}: " . basename($filePath));
                return $result;
            }
            
            throw new Exception('Erreur lors de l\'envoi du fichier: ' . $response->body());
        } catch (Exception $e) {
            Log::error("Erreur envoi fichier WhatsApp à {$number}: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Envoyer un message WhatsApp unique
     */
    public function sendMessage(string $number, string $message): array
    {
        try {
            // Nettoyer et formater le numéro
            $cleanNumber = $this->formatPhoneNumber($number);
            
            $response = Http::timeout($this->timeout)
                ->post($this->baseUrl . '/send-message', [
                    'number' => $cleanNumber,
                    'message' => $message
                ]);
            
            if ($response->successful()) {
                $result = $response->json();
                Log::info("Message WhatsApp envoyé à {$cleanNumber}");
                return $result;
            }
            
            throw new Exception('Erreur lors de l\'envoi: ' . $response->body());
        } catch (Exception $e) {
            Log::error("Erreur envoi WhatsApp à {$number}: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Envoyer des messages en masse
     */
    public function sendBulkMessages(array $contacts): array
    {
        try {
            // Formater les contacts
            $formattedContacts = array_map(function ($contact) {
                return [
                    'number' => $this->formatPhoneNumber($contact['number']),
                    'message' => $contact['message']
                ];
            }, $contacts);
            
            $response = Http::timeout($this->timeout * 2) // Plus de temps pour les envois en masse
                ->post($this->baseUrl . '/send-bulk', [
                    'contacts' => $formattedContacts
                ]);
            
            if ($response->successful()) {
                $result = $response->json();
                Log::info("Messages WhatsApp en masse envoyés: " . count($formattedContacts) . " contacts");
                return $result;
            }
            
            throw new Exception('Erreur lors de l\'envoi en masse: ' . $response->body());
        } catch (Exception $e) {
            Log::error("Erreur envoi en masse WhatsApp: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Envoyer des rappels d'abonnement
     */
    public function sendSubscriptionReminders(array $clients): array
    {
        $contacts = [];
        
        foreach ($clients as $client) {
            $message = $this->generateReminderMessage($client);
            $contacts[] = [
                'number' => $client['telephone'],
                'message' => $message
            ];
        }
        
        return $this->sendBulkMessages($contacts);
    }
    
    /**
     * Générer le message de rappel personnalisé en arabe
     */
    private function generateReminderMessage(array $client): string
    {
        $nom = $client['nom'] ?? 'العميل';
        $prenom = $client['prenom'] ?? '';
        $dateExpiration = $client['date_expiration'] ?? 'قريباً';
        
        $nomComplet = trim($prenom . ' ' . $nom);
        
        return "🥋 مرحباً {$nomComplet}،\n\n" .
               "انخراطك في جمعية آلاء الرياضية للتايكواندو ينتهي في {$dateExpiration}.\n\n" .
               "لمواصلة الاستفادة من الانخراط، يرجى تجديده.\n\n" .
               "شكراً لثقتك! 🙏\n\n" .
               "إدارة جمعية آلاء الرياضية للتايكواندو";
    }
    
    /**
     * Formater le numéro de téléphone
     */
    private function formatPhoneNumber(string $number): string
    {
        // Supprimer tous les caractères non numériques
        $clean = preg_replace('/[^0-9]/', '', $number);
        
        // Si le numéro commence par 0, le remplacer par 212 (Maroc)
        if (str_starts_with($clean, '0')) {
            $clean = '212' . substr($clean, 1);
        }
        
        // Si le numéro ne commence pas par 212, l'ajouter
        if (!str_starts_with($clean, '212')) {
            $clean = '212' . $clean;
        }
        
        return $clean;
    }
    
    /**
     * Vérifier si le service WhatsApp est disponible
     */
    public function isServiceAvailable(): bool
    {
        try {
            Http::timeout(5)->get($this->baseUrl . '/status');
            return true;
        } catch (Exception $e) {
            Log::error('Erreur lors de la vérification du service WhatsApp: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupérer le code QR pour la connexion WhatsApp
     */
    public function getQRCode(): ?string
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->baseUrl . '/qrcode');
            
            if ($response->successful()) {
                $data = $response->json();
                if ($data['success'] && isset($data['qrCode'])) {
                    return $data['qrCode'];
                }
            }
            
            return null;
        } catch (Exception $e) {
            Log::error('WhatsApp QR Code Error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Vérifier si WhatsApp est connecté
     */
    public function isWhatsAppConnected(): bool
    {
        $status = $this->getStatus();
        return isset($status['connected']) && $status['connected'] === true;
    }
    
    /**
     * Obtenir les statistiques d'envoi
     */
    public function getStats(): array
    {
        // Cette méthode pourrait être étendue pour récupérer des statistiques
        // du service Node.js si nécessaire
        return [
            'service_available' => $this->isServiceAvailable(),
            'base_url' => $this->baseUrl
        ];
    }
}