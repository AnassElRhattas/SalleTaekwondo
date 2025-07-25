<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Statistiques principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Clients Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('Total des Clients') }}</h3>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalClients }}</p>
                    </div>
                </div>
                
                <!-- Abonnements actifs -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('Abonnements actifs') }}</h3>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $activeCount }}</p>
                    </div>
                </div>
                
                <!-- Abonnements expirants -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('Expirant bientôt') }}</h3>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $expiringCount }}</p>
                    </div>
                </div>
                
                <!-- Abonnements expirés -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('Abonnements expirés') }}</h3>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $expiredCount }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Statistiques supplémentaires -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Taux de renouvellement -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('Taux de renouvellement') }}</h3>
                        <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $renewalRate }}%</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Pourcentage de clients qui renouvellent leur abonnement</p>
                    </div>
                </div>
                
                <!-- Âge moyen -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('Âge moyen des clients') }}</h3>
                        @if ($averageAge)
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $averageAge }} ans</p>
                        @else
                            <p class="text-xl text-gray-500 dark:text-gray-400">Données insuffisantes</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Statistiques mensuelles -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Statistiques mensuelles') }}</h3>
                    <div class="w-full" style="height: 300px;">
                        <canvas id="monthlyStatsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Clients with Expiring Subscriptions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Clients avec Abonnements Expirants') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nom</th>
                                    <th scope="col" class="px-6 py-3">Date d'inscription</th>
                                    <th scope="col" class="px-6 py-3">Derniere abonnement</th>
                                    <th scope="col" class="px-6 py-3">Jours restants</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expiringClients as $client)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ $client->name }}</td>
                                        <td class="px-6 py-4">{{ $client->created_at->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4">{{ $client->payer_abon }}</td>
                                        <td class="px-6 py-4">
    @if ($client->days_remaining < 0)
        <span class="px-1 py-1 text-xs font-bold text-red-500 rounded-full">{{ $client->days_remaining }}</span>
    @else 
        <span class="px-1 py-1 text-xs font-bold text-green-600 rounded-full">{{ $client->days_remaining }}</span>
    @endif
</td>

                                        <td class="px-6 py-4">
                                            @if ($client->days_remaining < 0)
                                                <span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Expiré</span>
                                            @elseif ($client->days_remaining <= 3)
                                                <span class="px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded-full">Expire bientôt</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">Actif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Données pour le graphique mensuel
            const monthlyData = @json($monthlyStats);
            const months = Object.values(monthlyData).map(item => item.month_name);
            const newClients = Object.values(monthlyData).map(item => item.new_clients);
            const renewals = Object.values(monthlyData).map(item => item.renewals);
            
            // Configuration du graphique mensuel
            const ctx = document.getElementById('monthlyStatsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Nouveaux clients',
                            data: newClients,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1
                        },
                        {
                            label: 'Renouvellements',
                            data: renewals,
                            backgroundColor: 'rgba(16, 185, 129, 0.5)',
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
