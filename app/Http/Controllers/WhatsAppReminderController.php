<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class WhatsAppReminderController extends Controller
{
    /**
     * Show the WhatsApp reminders dashboard
     */
    public function index()
    {
        // Get today's date
        $today = Carbon::today();
        
        // Get the latest CSV file if it exists
        $filename = 'whatsapp_reminders_' . $today->format('Y-m-d') . '.csv';
        $fileExists = Storage::disk('public')->exists($filename);
        
        // Count clients with expiring subscriptions
        $expiringCount = $this->getExpiringClientsCount();
        
        return view('whatsapp.index', [
            'fileExists' => $fileExists,
            'filename' => $filename,
            'expiringCount' => $expiringCount,
        ]);
    }
    
    /**
     * Generate the CSV file and return it for download
     */
    public function generateAndDownload()
    {
        // Run the command to generate the CSV
        Artisan::call('whatsapp:generate-csv');
        
        // Get today's date
        $today = Carbon::today();
        $filename = 'whatsapp_reminders_' . $today->format('Y-m-d') . '.csv';
        
        // Check if file exists
        if (!Storage::disk('public')->exists($filename)) {
            return redirect()->back()->with('error', 'Aucun fichier CSV n\'a été généré. Il n\'y a peut-être pas de clients avec des abonnements expirants.');
        }
        
        // Return file for download
        return Storage::disk('public')->download($filename, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
    
    /**
     * Count clients with expiring subscriptions
     */
    private function getExpiringClientsCount()
    {
        $today = Carbon::today();
        
        return Client::whereNotNull('payer_abon')
            ->get()
            ->filter(function ($client) use ($today) {
                $lastPaymentDate = Carbon::parse($client->payer_abon);
                $nextPaymentDate = $lastPaymentDate->copy()->addMonth();
                $daysUntilExpiration = $today->diffInDays($nextPaymentDate, false);
                
                return $daysUntilExpiration <= 3;
            })
            ->count();
    }
}