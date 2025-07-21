<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendSubscriptionReminders extends Command
{
    protected $signature = 'reminders:send-subscription';
    protected $description = 'Send WhatsApp reminders to clients whose subscription is about to expire';

    public function __construct(private WhatsAppService $whatsAppService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Starting to send subscription reminders...');

        $thirtyDaysAgo = Carbon::now()->subDays(30);

        // Get clients who registered 30 or more days ago
        $clients = Client::where('created_at', '<=', $thirtyDaysAgo)->get();

        if ($clients->isEmpty()) {
            $this->info('No clients found needing reminders.');
            return Command::SUCCESS;
        }

        $successCount = 0;
        $failureCount = 0;

        foreach ($clients as $client) {
            if ($this->whatsAppService->sendReminder($client)) {
                $successCount++;
                $this->info("Reminder sent successfully to {$client->name}");
            } else {
                $failureCount++;
                $this->error("Failed to send reminder to {$client->name}");
            }
        }

        $this->info("Completed sending reminders.\nSuccess: {$successCount}\nFailures: {$failureCount}");

        return Command::SUCCESS;
    }
}