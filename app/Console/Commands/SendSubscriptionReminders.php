<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Services\WhatsAppAutomationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendSubscriptionReminders extends Command
{
    protected $signature = 'reminders:send-subscription {--dry-run : Afficher les clients sans envoyer de messages}';
    protected $description = 'Send WhatsApp reminders to clients whose subscription is about to expire';

    private WhatsAppAutomationService $whatsappService;

    public function __construct(WhatsAppAutomationService $whatsappService)
    {
        parent::__construct();
        $this->whatsappService = $whatsappService;
    }

    public function handle(): int
    {
        $this->info('🚀 Démarrage de l\'envoi automatique des rappels d\'abonnement...');

        // Vérifier si le service WhatsApp est disponible
        if (!$this->whatsappService->isServiceAvailable()) {
            $this->error('❌ Le service WhatsApp n\'est pas disponible. Assurez-vous qu\'il est démarré.');
            $this->info('💡 Pour démarrer le service: cd whatsapp-service && npm start');
            return Command::FAILURE;
        }

        $this->info('✅ Service WhatsApp disponible');
        
        // Vérifier si WhatsApp est connecté
        $isDryRun = $this->option('dry-run');
        if (!$this->whatsappService->isWhatsAppConnected()) {
            $this->warn('⚠️  WhatsApp n\'est pas encore connecté. Scannez le QR code affiché dans le terminal du service.');
            if (!$isDryRun) {
                $this->error('❌ Impossible d\'envoyer des messages sans connexion WhatsApp.');
                return Command::FAILURE;
            }
        } else {
            $this->info('✅ WhatsApp connecté');
        }

        $thirtyDaysAgo = Carbon::now()->subDays(30);

        // Récupérer les clients dont l'abonnement a expiré il y a 30 jours ou plus
        $clients = Client::where('payer_abon', '<=', $thirtyDaysAgo)
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->get();

        if ($clients->isEmpty()) {
            $this->info('ℹ️  Aucun client trouvé nécessitant des rappels.');
            return Command::SUCCESS;
        }

        $this->info("📱 {$clients->count()} client(s) trouvé(s) pour les rappels");
        
        if ($isDryRun) {
            $this->warn('🔍 Mode test activé - aucun message ne sera envoyé');
        }

        $successCount = 0;
        $failureCount = 0;
        $clientsData = [];

        // Préparer les données des clients
        foreach ($clients as $client) {
            $clientData = [
                'nom' => $client->name,
                'prenom' => $client->prenom ?? '',
                'telephone' => $client->phone,
                'date_expiration' => Carbon::parse($client->payer_abon)->addDays(30)->format('d/m/Y')
            ];
            
            $clientsData[] = $clientData;
            
            if ($isDryRun) {
                $this->line("📋 {$client->prenom} {$client->name} - {$client->phone} - Expire: {$clientData['date_expiration']}");
            }
        }

        if ($isDryRun) {
            $this->info("\n✅ Mode test terminé. {$clients->count()} client(s) seraient contactés.");
            return Command::SUCCESS;
        }

        // Envoyer les rappels
        $this->info('📤 Envoi des rappels en cours...');
        
        $result = $this->whatsappService->sendSubscriptionReminders($clientsData);
        
        if ($result['success']) {
            $results = $result['results'] ?? [];
            
            foreach ($results as $index => $messageResult) {
                $client = $clients[$index] ?? null;
                
                if ($messageResult['success']) {
                    $successCount++;
                    $this->info("✅ Message envoyé à {$client->prenom} {$client->name} ({$messageResult['number']})");
                    Log::info("Rappel WhatsApp envoyé à {$client->prenom} {$client->name} - {$messageResult['number']}");
                } else {
                    $failureCount++;
                    $error = $messageResult['error'] ?? 'Erreur inconnue';
                    $this->error("❌ Échec pour {$client->prenom} {$client->name} ({$messageResult['number']}): {$error}");
                    Log::error("Échec rappel WhatsApp pour {$client->prenom} {$client->name} - {$messageResult['number']}: {$error}");
                }
            }
        } else {
            $this->error("❌ Erreur lors de l'envoi en masse: {$result['error']}");
            Log::error("Erreur envoi rappels WhatsApp: {$result['error']}");
            return Command::FAILURE;
        }

        $this->info("\n📊 Rappels terminés:");
        $this->info("✅ Succès: {$successCount}");
        $this->info("❌ Échecs: {$failureCount}");
        $this->info("📱 Total: " . ($successCount + $failureCount));

        if ($successCount > 0) {
            $this->info("\n🎉 Les rappels ont été envoyés automatiquement via WhatsApp!");
        }

        return Command::SUCCESS;
    }
}