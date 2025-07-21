<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $baseUrl;
    protected string $instanceId;
    protected string $token;

    public function __construct()
    {
        $this->instanceId = config('services.green_api.instance_id');
        $this->token = config('services.green_api.token');
        $this->baseUrl = "https://api.green-api.com/waInstance{$this->instanceId}";
    }

    public function sendReminder(Client $client): bool
    {
        try {
            // Format phone number to international format if Moroccan
            $phone = $this->formatPhoneNumber($client->phone);
            
            $response = Http::post("{$this->baseUrl}/sendMessage/{$this->token}", [
                'chatId' => "{$phone}@c.us",
                'message' => $this->buildReminderMessage($client->name)
            ]);

            dd($response->body());

            if ($response->successful()) {
                Log::info("Reminder sent successfully to {$client->name}");
                return true;
            }

            Log::error("Failed to send reminder to {$client->name}", [
                'response' => $response->json(),
                'client_id' => $client->id
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error("Error sending WhatsApp reminder", [
                'error' => $e->getMessage(),
                'client_id' => $client->id
            ]);
            return false;
        }
    }

    protected function formatPhoneNumber(string $phone): string
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If it's a Moroccan number (starts with 0)
        if (strlen($phone) === 10 && str_starts_with($phone, '0')) {
            return '212' . substr($phone, 1);
        }

        return $phone;
    }

    protected function buildReminderMessage(string $name): string
    {
        return "Bonjour {$name}, votre abonnement à la salle Taekwondo est presque expiré. Merci de passer le renouveler.";
    }
}