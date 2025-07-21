<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Total Clients Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Total des Clients') }}</h3>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalClients }}</p>
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
                                    <th scope="col" class="px-6 py-3">Jours restants</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expiringClients as $client)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ $client->name }}</td>
                                        <td class="px-6 py-4">{{ $client->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">{{ $client->days_remaining }}</td>
                                        <td class="px-6 py-4">
                                            @if ($client->days_remaining < 0)
                                                <span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Expiré</span>
                                            @elseif ($client->days_remaining <= 7)
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
</x-app-layout>
