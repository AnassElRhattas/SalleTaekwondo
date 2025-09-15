<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppAutomationService;

class WhatsAppController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppAutomationService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Afficher la page de gestion WhatsApp
     */
    public function index()
    {
        // Vérifier le statut du service WhatsApp
        $serviceStatus = $this->whatsappService->getStatus();
        $isServiceAvailable = $this->whatsappService->isServiceAvailable();
        $isWhatsAppConnected = $this->whatsappService->isWhatsAppConnected();
        
        return view('whatsapp.index', compact('serviceStatus', 'isServiceAvailable', 'isWhatsAppConnected'));
    }

    /**
     * Envoyer les rappels d'abonnement
     */
    public function sendReminders(Request $request)
    {
        try {
            // Vérifier si le service WhatsApp est disponible
            if (!$this->whatsappService->isServiceAvailable()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le service WhatsApp n\'est pas disponible. Veuillez démarrer le service.'
                ], 503);
            }

            // Vérifier si WhatsApp est connecté
            if (!$this->whatsappService->isWhatsAppConnected()) {
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp n\'est pas connecté. Veuillez scanner le QR code.'
                ], 400);
            }

            // Exécuter la commande de rappels
            $exitCode = Artisan::call('reminders:send-subscription');
            $output = Artisan::output();

            if ($exitCode === 0) {
                Log::info('Rappels WhatsApp envoyés via interface web', ['output' => $output]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Les rappels ont été envoyés avec succès !',
                    'output' => $output
                ]);
            } else {
                Log::error('Erreur lors de l\'envoi des rappels WhatsApp', ['exit_code' => $exitCode, 'output' => $output]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de l\'envoi des rappels.',
                    'output' => $output
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception lors de l\'envoi des rappels WhatsApp', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur inattendue est survenue : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tester les rappels (mode dry-run)
     */
    public function testReminders(Request $request)
    {
        try {
            // Vérifier si le service WhatsApp est disponible
            if (!$this->whatsappService->isServiceAvailable()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le service WhatsApp n\'est pas disponible. Veuillez démarrer le service.'
                ], 503);
            }

            // Exécuter la commande de test
            $exitCode = Artisan::call('reminders:send-subscription', ['--dry-run' => true]);
            $output = Artisan::output();

            if ($exitCode === 0) {
                Log::info('Test des rappels WhatsApp via interface web', ['output' => $output]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Test effectué avec succès !',
                    'output' => $output
                ]);
            } else {
                Log::error('Erreur lors du test des rappels WhatsApp', ['exit_code' => $exitCode, 'output' => $output]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors du test.',
                    'output' => $output
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception lors du test des rappels WhatsApp', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur inattendue est survenue : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir le statut du service WhatsApp
     */
    public function getStatus()
    {
        $serviceStatus = $this->whatsappService->getStatus();
        $isServiceAvailable = $this->whatsappService->isServiceAvailable();
        $isWhatsAppConnected = $this->whatsappService->isWhatsAppConnected();
        
        return response()->json([
            'service_available' => $isServiceAvailable,
            'whatsapp_connected' => $isWhatsAppConnected,
            'status' => $serviceStatus
        ]);
    }
}