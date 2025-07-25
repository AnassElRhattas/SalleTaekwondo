<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Suivi des Paiements Mensuels') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Notifications -->
            @if (session('success'))
                <div id="notification" class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 dark:bg-green-800 dark:border-green-600 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filtres -->
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('payments.tracking') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-grow">
                        <label for="group" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filtrer par groupe</label>
                        <select name="group" id="group" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                            <option value="all" {{ request('group') == 'all' ? 'selected' : '' }}>Tous les groupes</option>
                            <option value="Box" {{ request('group') == 'Box' ? 'selected' : '' }}>Box</option>
                            <option value="Taekwondo" {{ request('group') == 'Taekwondo' ? 'selected' : '' }}>Taekwondo</option>
                            <option value="Karaté" {{ request('group') == 'Karaté' ? 'selected' : '' }}>Karaté</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tableau de suivi des paiements -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky left-0 bg-gray-50 dark:bg-gray-700 z-10">
                                    Client
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky left-[150px] bg-gray-50 dark:bg-gray-700 z-10">
                                    Groupe
                                </th>
                                @foreach ($months as $month)
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ $month['name'] }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($clientsWithPayments as $clientData)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white sticky left-0 bg-white dark:bg-gray-800 z-10">
                                        {{ $clientData['client']->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 sticky left-[150px] bg-white dark:bg-gray-800 z-10">
                                        {{ $clientData['client']->group }}
                                    </td>
                                    @foreach ($clientData['payments'] as $payment)
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($payment['status'] === 'paid')
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>
                                            @elseif ($payment['status'] === 'overdue')
                                                <form action="{{ route('payments.validate-month', $clientData['client']) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="month_date" value="{{ $payment['month']['date']->format('Y-m-d') }}">
                                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full hover:bg-red-200 dark:hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @elseif ($payment['status'] === 'not_registered')
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full opacity-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </span>
                                            @else
                                                <form action="{{ route('payments.validate-month', $clientData['client']) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="month_date" value="{{ $payment['month']['date']->format('Y-m-d') }}">
                                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Légende -->
            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Légende</h3>
                <div class="flex flex-wrap gap-6">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Paiement validé</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Paiement en retard</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Paiement à venir</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full opacity-50 mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Client non inscrit à cette période</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide notifications after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const notification = document.getElementById('notification');
            if (notification) {
                setTimeout(function() {
                    notification.style.opacity = '0';
                    notification.style.transition = 'opacity 1s';
                    setTimeout(function() {
                        notification.style.display = 'none';
                    }, 1000);
                }, 5000);
            }
        });
    </script>
</x-app-layout>