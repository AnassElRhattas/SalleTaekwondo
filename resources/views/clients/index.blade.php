<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Clients List') }}
        </h2>
    </x-slot>
    
    <!-- Alpine.js pour les menus déroulants -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Messages de notification -->
            @if(session('success'))
                <div id="notification" class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 flex justify-between items-center">
                    <div>
                        <p class="font-bold">Succès!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                    <button onclick="document.getElementById('notification').remove()" class="text-green-700 hover:text-green-900">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- En-tête avec recherche, filtre et bouton d'ajout -->
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <!-- Barre de recherche améliorée -->
                        <div class="relative w-full md:w-1/3">
                            <form action="{{ route('clients.index') }}" method="GET" class="flex">
                                <!-- Conserver le filtre de groupe s'il est défini -->
                                @if(request('group') && request('group') != 'all')
                                    <input type="hidden" name="group" value="{{ request('group') }}">
                                @endif
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un client..." class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition duration-200">
                                </div>
                                <button type="submit" class="ml-2 px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        
                        <!-- Filtre par groupe -->
                        <div class="relative w-full md:w-1/3">
                            <form action="{{ route('clients.index') }}" method="GET" class="flex">
                                <!-- Conserver le paramètre de recherche s'il est défini -->
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                <select name="group" onchange="this.form.submit()" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition duration-200">
                                    <option value="all" {{ request('group') == 'all' || !request('group') ? 'selected' : '' }}>Tous les groupes</option>
                                    <option value="Box" {{ request('group') == 'Box' ? 'selected' : '' }}>Box</option>
                                    <option value="Taekwondo" {{ request('group') == 'Taekwondo' ? 'selected' : '' }}>Taekwondo</option>
                                    <option value="Karaté" {{ request('group') == 'Karaté' ? 'selected' : '' }}>Karaté</option>
                                </select>
                            </form>
                        </div>
                        
                        <!-- Bouton d'ajout de client -->
                        <a href="{{ route('clients.create') }}" class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-200 w-full md:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter un client
                        </a>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        @if($clients->isEmpty())
                            <div class="p-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucun client</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Commencez par ajouter un nouveau client.</p>
                                <div class="mt-6">
                                    <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Ajouter un client
                                    </a>
                                </div>
                            </div>
                        @else
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">#</th>
                                        <th scope="col" class="px-6 py-3">Nom</th>
                                        <th scope="col" class="px-6 py-3">Date de naissance</th>
                                        <th scope="col" class="px-6 py-3">Téléphone</th>
                                        <th scope="col" class="px-6 py-3">Adresse</th>
                                        <th scope="col" class="px-6 py-3">Groupe</th>
                                        <th scope="col" class="px-6 py-3">Date d'inscription</th>
                                        <th scope="col" class="px-6 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($clients as $index => $client)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $client->name }}</td>
                                        <td class="px-6 py-4">{{ $client->birth_date }}</td>
                                        <td class="px-6 py-4">{{ $client->phone }}</td>
                                        <td class="px-6 py-4">{{ $client->address }}</td>
                                        <td class="px-6 py-4">
                                            @if($client->group)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $client->group == 'Box' ? 'bg-blue-100 text-blue-800' : ($client->group == 'Taekwondo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $client->group }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $client->created_at }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center space-x-3">
                                                <!-- Bouton View avec icône -->
                                                <button onclick="viewDetails({{ $client->id }}, '{{ $client->name }}', '{{ $client->birth_date }}', '{{ $client->phone }}', '{{ $client->address }}', '{{ $client->created_at }}', '{{ $client->payer_abon }}', '{{ $client->profile_picture ?? "/default-avatar.jpg" }}', '{{ $client->group }}')" 
                                                    class="p-1.5 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-colors duration-200" title="Voir les détails">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                                
                                                <!-- Bouton Edit avec icône -->
                                                <button onclick="openEditModal({{ $client->id }}, '{{ $client->name }}', '{{ $client->birth_date }}', '{{ $client->phone }}', '{{ $client->address }}', '{{ $client->group }}')" 
                                                    class="p-1.5 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors duration-200" title="Modifier">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                
                                                <!-- Menu déroulant pour plus d'actions -->
                                                <div class="relative" x-data="{ open: false }">
                                                    <button @click="open = !open" class="p-1.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors duration-200" title="Plus d'actions">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                        </svg>
                                                    </button>
                                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 border border-gray-200 dark:border-gray-700">
                                                        <!-- Lien pour valider le paiement -->
                                                        <a href="{{ route('clients.validate-payment', $client->id) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                            <div class="flex items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Valider le paiement
                                                            </div>
                                                        </a>
                                                        
                                                        <!-- Bouton de suppression -->
                                                        <form action="{{ route('clients.destroy', $client) }}" method="POST" class="block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client?')">
                                                                <div class="flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                    Supprimer
                                                                </div>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            
                            <!-- Pagination -->
                            <div class="px-6 py-4">
                                {{ $clients->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-40 hidden overflow-y-auto h-full w-full backdrop-blur-sm transition-all duration-300 z-50">
        <div class="relative top-20 mx-auto p-8 border-0 w-[800px] shadow-2xl rounded-xl bg-white dark:bg-gray-800 transform transition-all duration-300">
            <div class="mt-2">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white text-center flex-grow">Détails du client</h3>
                    <button onclick="closeDetailsModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="flex flex-row gap-8">
                    <!-- Photo de profil -->
                    <div class="flex-shrink-0">
                        <div class="w-40 h-40 overflow-hidden border-4 border-gray-200 dark:border-gray-600 rounded-full shadow-md">
                            <img id="detailProfilePic" src="/default-avatar.jpg" alt="Photo de profil" class="w-full h-full object-cover">
                        </div>
                    </div>
                    
                    <!-- Informations client -->
                    <div class="flex-grow">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Nom</h4>
                                <p id="detailName" class="text-lg text-gray-900 dark:text-white font-medium"></p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Date de naissance</h4>
                                <p id="detailBirthDate" class="text-lg text-gray-900 dark:text-white font-medium"></p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Téléphone</h4>
                                <p id="detailPhone" class="text-lg text-gray-900 dark:text-white font-medium"></p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Date d'inscription</h4>
                                <p id="detailRegistrationDate" class="text-lg text-gray-900 dark:text-white font-medium"></p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Groupe</h4>
                                <p id="detailGroup" class="text-lg text-gray-900 dark:text-white font-medium"></p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Dernier paiement</h4>
                                <p id="detailLastPayer" class="text-lg text-gray-900 dark:text-white font-medium"></p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg col-span-2">
                                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Adresse</h4>
                                <p id="detailAddress" class="text-lg text-gray-900 dark:text-white font-medium"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-6 space-x-3">
                    <button id="validatePaymentBtn" class="flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Valider le paiement
                    </button>
                    <button onclick="closeDetailsModal()" class="flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-700 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 hidden overflow-y-auto h-full w-full backdrop-blur-sm transition-all duration-300 z-50">
        <div class="relative top-20 mx-auto p-8 border-0 w-[800px] shadow-2xl rounded-xl bg-white dark:bg-gray-800 transform transition-all duration-300">
            <div class="mt-2">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white text-center flex-grow">Modifier le client</h3>
                    <button type="button" onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="editForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-5 rounded-lg">
                            <label class="block text-gray-700 dark:text-gray-200 text-sm font-semibold mb-2" for="name">Nom</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text" name="name" id="editName" 
                                    class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition duration-200">
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-5 rounded-lg">
                            <label class="block text-gray-700 dark:text-gray-200 text-sm font-semibold mb-2" for="birth_date">Date de naissance</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="date" name="birth_date" id="editBirthDate" 
                                    class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition duration-200">
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-5 rounded-lg">
                            <label class="block text-gray-700 dark:text-gray-200 text-sm font-semibold mb-2" for="phone">Téléphone</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <input type="text" name="phone" id="editPhone" 
                                    class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition duration-200">
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-5 rounded-lg">
                            <label class="block text-gray-700 dark:text-gray-200 text-sm font-semibold mb-2" for="group">Groupe</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <select name="group" id="editGroup" 
                                    class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition duration-200">
                                    <option value="Box">Box</option>
                                    <option value="Taekwondo">Taekwondo</option>
                                    <option value="Karaté">Karaté</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-5 rounded-lg col-span-2">
                            <label class="block text-gray-700 dark:text-gray-200 text-sm font-semibold mb-2" for="address">Adresse</label>
                            <div class="relative">
                                <div class="absolute top-3 left-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <textarea name="address" id="editAddress" rows="3" 
                                    class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition duration-200"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-4 mt-8">
                        <button type="button" onclick="closeEditModal()" 
                            class="flex items-center px-6 py-3 rounded-lg bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-700 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Annuler
                        </button>
                        <button type="submit" 
                            class="flex items-center px-6 py-3 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide notification after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const notification = document.getElementById('notification');
            if (notification) {
                setTimeout(function() {
                    notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(function() {
                        notification.remove();
                    }, 500);
                }, 5000);
            }
        });
        
        // La recherche est maintenant gérée côté serveur

        function openEditModal(id, name, birthDate, phone, address, group) {
            document.getElementById('editForm').action = `/clients/${id}`;
            document.getElementById('editName').value = name;
            document.getElementById('editBirthDate').value = birthDate;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editAddress').value = address;
            
            // Sélectionner le groupe
            const groupSelect = document.getElementById('editGroup');
            for (let i = 0; i < groupSelect.options.length; i++) {
                if (groupSelect.options[i].value === group) {
                    groupSelect.selectedIndex = i;
                    break;
                }
            }
            
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function viewDetails(id, name, birthDate, phone, address, registrationDate, lastPayer, profilePicture, group) {
            document.getElementById('detailName').textContent = name;
            document.getElementById('detailBirthDate').textContent = birthDate;
            document.getElementById('detailPhone').textContent = phone;
            document.getElementById('detailAddress').textContent = address;
            document.getElementById('detailRegistrationDate').textContent = registrationDate;
            document.getElementById('detailLastPayer').textContent = lastPayer;
            document.getElementById('detailGroup').textContent = group || '-';
            document.getElementById('detailProfilePic').src = `/storage/${profilePicture}`;
            document.getElementById('detailsModal').classList.remove('hidden');

            // Update the validate payment button to include the client ID
            const validatePaymentBtn = document.getElementById('validatePaymentBtn');
            validatePaymentBtn.onclick = function() {
                window.location.href = `/clients/${id}/validate-payment`;
            };
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }
    </script>
</x-app-layout>