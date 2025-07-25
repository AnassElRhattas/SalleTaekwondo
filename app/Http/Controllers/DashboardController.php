<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord avec les statistiques
     */
    public function index(): View
    {
        // Statistiques de base
        $totalClients = Client::count();
        
        // Clients avec abonnements expirants
        $clients = Client::whereNotNull('payer_abon')->get();
        $expiringClients = [];
        $today = Carbon::now()->startOfDay();
        
        // Statistiques d'abonnement
        $expiredCount = 0;
        $expiringCount = 0;
        $activeCount = 0;
        
        // Statistiques par mois
        $currentYear = Carbon::now()->year;
        $monthlyStats = $this->getMonthlyStats($currentYear);
        
        // Taux de renouvellement
        $renewalRate = $this->calculateRenewalRate();
        
        foreach ($clients as $client) {
            $lastPaymentDate = Carbon::parse($client->payer_abon)->startOfDay();
            $nextPaymentDate = $lastPaymentDate->copy()->addMonth()->startOfDay();
            $daysRemaining = $today->diffInDays($nextPaymentDate, false);
            
            $client->days_remaining = $daysRemaining;
            
            // Compter les clients par statut
            if ($daysRemaining < 0) {
                $expiredCount++;
            } elseif ($daysRemaining <= 3) {
                $expiringCount++;
            } else {
                $activeCount++;
            }
            
            // Ajouter à la liste des clients expirants pour l'affichage
            if ($daysRemaining <= 3) {
                $expiringClients[] = $client;
            }
        }
        
        // Âge moyen des clients
        $averageAge = $this->calculateAverageAge();
        
        return view('dashboard', compact(
            'totalClients', 
            'expiringClients', 
            'expiredCount', 
            'expiringCount', 
            'activeCount',
            'monthlyStats',
            'renewalRate',
            'averageAge'
        ));
    }
    
    /**
     * Calcule les statistiques mensuelles d'inscriptions et de renouvellements
     */
    private function getMonthlyStats(int $year): array
    {
        $stats = [];
        
        // Nouvelles inscriptions par mois
        $newClients = DB::table('clients')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->get()
            ->keyBy('month')
            ->toArray();
        
        // Renouvellements par mois
        $renewals = DB::table('clients')
            ->select(DB::raw('MONTH(payer_abon) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('payer_abon', $year)
            ->whereNotNull('payer_abon')
            ->groupBy('month')
            ->get()
            ->keyBy('month')
            ->toArray();
        
        // Préparer les données pour tous les mois
        for ($month = 1; $month <= 12; $month++) {
            $stats[$month] = [
                'month_name' => Carbon::create($year, $month, 1)->format('F'),
                'new_clients' => $newClients[$month]->count ?? 0,
                'renewals' => $renewals[$month]->count ?? 0
            ];
        }
        
        return $stats;
    }
    
    /**
     * Calcule le taux de renouvellement des abonnements
     */
    private function calculateRenewalRate(): float
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        
        // Nombre total de clients qui ont eu un abonnement qui a expiré dans les 6 derniers mois
        $totalExpired = Client::whereNotNull('payer_abon')
            ->where('payer_abon', '<=', $sixMonthsAgo)
            ->count();
        
        if ($totalExpired === 0) {
            return 100.0; // Si aucun client n'a expiré, le taux est de 100%
        }
        
        // Nombre de clients qui ont renouvelé après expiration
        $renewed = Client::whereNotNull('payer_abon')
            ->where('payer_abon', '>', $sixMonthsAgo)
            ->whereRaw('DATEDIFF(payer_abon, created_at) > 30') // Au moins un renouvellement
            ->count();
        
        return round(($renewed / $totalExpired) * 100, 1);
    }
    
    /**
     * Calcule l'âge moyen des clients
     */
    private function calculateAverageAge(): ?float
    {
        $clientsWithBirthDate = Client::whereNotNull('birth_date')->get();
        
        if ($clientsWithBirthDate->isEmpty()) {
            return null;
        }
        
        $totalAge = 0;
        $count = 0;
        
        foreach ($clientsWithBirthDate as $client) {
            $birthDate = Carbon::parse($client->birth_date);
            $age = $birthDate->age;
            
            $totalAge += $age;
            $count++;
        }
        
        return $count > 0 ? round($totalAge / $count, 1) : null;
    }
}