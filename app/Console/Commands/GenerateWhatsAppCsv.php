<?php

namespace App\Console\Commands;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateWhatsAppCsv extends Command
{
    protected $signature = 'whatsapp:generate-csv';
    protected $description = 'Generate a CSV file for WhatsApp reminders based on subscription expiration';

    public function handle(): int
    {
        $this->info('Starting to generate WhatsApp CSV file...');

        // Get current date
        $today = Carbon::today();
        
        // Get clients whose subscription is about to expire (within 3 days) or has already expired
        $clients = Client::whereNotNull('payer_abon')
            ->get()
            ->filter(function ($client) use ($today) {
                // Calculate next payment date (1 month after last payment)
                $lastPaymentDate = Carbon::parse($client->payer_abon);
                $nextPaymentDate = $lastPaymentDate->copy()->addMonth();
                
                // Calculate days until expiration (negative if already expired)
                $daysUntilExpiration = $today->diffInDays($nextPaymentDate, false);
                
                // Keep clients whose subscription expires in 3 days or less, or has already expired
                return $daysUntilExpiration <= 3;
            });

        if ($clients->isEmpty()) {
            $this->info('No clients found with expiring subscriptions.');
            return Command::SUCCESS;
        }

        // Generate CSV content
        $csvContent = "Name,Phone,Name,PhraseExperation,Days\n";
        
        foreach ($clients as $client) {
            // Format phone number
            $phone = $this->formatPhoneNumber($client->phone);
            
            // Calculate days until expiration
            $lastPaymentDate = Carbon::parse($client->payer_abon);
            $nextPaymentDate = $lastPaymentDate->copy()->addMonth();
            $daysUntilExpiration = $today->diffInDays($nextPaymentDate, false);
            
            // Clean client name to avoid encoding issues
            $clientName = $this->cleanName($client->name);
            
            // Generate appropriate phrase based on expiration status
            if ($daysUntilExpiration < 0) {
                // Subscription has already expired
                $phrase = "انتهت صلاحية اشتراكك منذ فترة";
                $days = $this->formatDaysInArabic(abs($daysUntilExpiration));
            } else {
                // Subscription will expire soon
                $phrase = "تنتهي صلاحية اشتراكك في";
                $days = $this->formatDaysInArabic($daysUntilExpiration);
            }
            
            // Add to CSV content
            $csvContent .= "{$clientName},{$phone},{$clientName},{$phrase},{$days}\n";
        }
        
        // Save CSV file with UTF-8 BOM to ensure proper encoding
        $filename = 'whatsapp_reminders_' . $today->format('Y-m-d') . '.csv';
        $csvContentWithBOM = "\xEF\xBB\xBF" . $csvContent; // Add UTF-8 BOM
        Storage::disk('public')->put($filename, $csvContentWithBOM);
        
        // Also create a copy in the root directory for easier access
        file_put_contents(public_path($filename), $csvContentWithBOM);
        
        $this->info("CSV file generated successfully: {$filename}");
        $this->info("Total clients included: {$clients->count()}");
        $this->info("File path: " . Storage::disk('public')->path($filename));
        
        return Command::SUCCESS;
    }
    
    /**
     * Format phone number to international format
     */
    protected function formatDaysInArabic(int $days): string
    {
        return match($days) {
            1 => 'يوم',
            2 => 'يومان',
            default => $days . ' أيام'
        };
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
    
    /**
     * Clean client name to avoid encoding issues
     */
    protected function cleanName(string $name): string
    {
        // Simple approach to handle common Arabic/French characters
        // Replace accented characters with their non-accented equivalents
        $search = ['à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ'];
        $replace = ['a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y'];
        $name = str_replace($search, $replace, $name);
        
        // For Arabic names, we'll keep them as is but ensure they're properly encoded
        // Just trim whitespace and remove control characters
        $name = preg_replace('/[\x00-\x1F\x7F]/', '', $name);
        
        return trim($name);
    }
}