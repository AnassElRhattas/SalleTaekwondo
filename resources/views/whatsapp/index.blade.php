<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion WhatsApp - Rappels d\'abonnement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Statut du service -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Statut du Service</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if($isServiceAvailable)
                                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        @else
                                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Service WhatsApp</p>
                                        <p class="text-sm text-gray-500">
                                            @if($isServiceAvailable)
                                                ✅ Disponible
                                            @else
                                                ❌ Non disponible
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if($isWhatsAppConnected)
                                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        @else
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Connexion WhatsApp</p>
                                        <p class="text-sm text-gray-500">
                                            @if($isWhatsAppConnected)
                                                ✅ Connecté
                                            @else
                                                ⚠️ Non connecté
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if(!$isServiceAvailable)
                            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-red-800">
                                    <strong>⚠️ Service non disponible :</strong> Veuillez démarrer le service WhatsApp avec la commande :
                                    <code class="bg-red-100 px-2 py-1 rounded text-sm">cd whatsapp-service && npm start</code>
                                </p>
                            </div>
                        @elseif(!$isWhatsAppConnected)
                            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-yellow-800">
                                    <strong>📱 WhatsApp non connecté :</strong> Scannez le code QR ci-dessous avec votre téléphone.
                                </p>
                                <div id="qrcode-container" class="mt-4 flex justify-center">
                                    <div class="p-4 bg-white border rounded-lg shadow-sm">
                                        <img id="qrcode-image" src="" alt="QR Code WhatsApp" class="w-48 h-48 hidden">
                                        <div id="qrcode-loading" class="w-48 h-48 flex items-center justify-center">
                                            <svg class="animate-spin h-8 w-8 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                        <div id="qrcode-error" class="w-48 h-48 hidden flex items-center justify-center text-red-500 text-center">
                                            Code QR non disponible
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">🚀 Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            <!-- Bouton Test -->
                            <button id="testBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Tester les rappels
                            </button>
                            
                            <!-- Bouton Envoyer -->
                            <button id="sendBtn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center" 
                                    @if(!$isServiceAvailable || !$isWhatsAppConnected) disabled @endif>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Envoyer les rappels
                            </button>
                            
                            <!-- Bouton Actualiser -->
                            <button id="refreshBtn" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Actualiser le statut
                            </button>
                        </div>
                    </div>

                    <!-- Zone de résultats -->
                    <div id="results" class="hidden">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">📋 Résultats</h3>
                        <div id="resultContent" class="bg-gray-50 p-4 rounded-lg border">
                            <!-- Le contenu sera injecté ici -->
                        </div>
                    </div>

                    <!-- Zone de chargement -->
                    <div id="loading" class="hidden text-center py-8">
                        <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-indigo-500 hover:bg-indigo-400 transition ease-in-out duration-150 cursor-not-allowed">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Traitement en cours...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Reminders Confirmation Modal -->
    <div id="sendRemindersModal" class="fixed inset-0 bg-black bg-opacity-40 hidden overflow-y-auto h-full w-full backdrop-blur-sm transition-all duration-300 z-50">
        <div class="relative top-20 mx-auto p-8 border-0 w-[500px] shadow-2xl rounded-xl bg-white dark:bg-gray-800 transform transition-all duration-300">
            <div class="mt-2">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-blue-600 dark:text-blue-400 text-center flex-grow">Confirmer l'envoi</h3>
                    <button type="button" onclick="closeSendRemindersModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="text-center mb-8">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <p class="text-lg text-gray-700 dark:text-gray-200 mb-2">Êtes-vous sûr de vouloir envoyer les rappels WhatsApp ?</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Cette action enverra des messages à tous les clients concernés.</p>
                </div>
                
                <div class="flex items-center justify-center space-x-4 mt-8">
                    <button type="button" onclick="closeSendRemindersModal()" 
                        class="flex items-center px-6 py-3 rounded-lg bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-700 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Annuler
                    </button>
                    <button type="button" onclick="confirmSendReminders()" 
                        class="flex items-center px-6 py-3 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Envoyer les rappels
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const testBtn = document.getElementById('testBtn');
            const sendBtn = document.getElementById('sendBtn');
            const refreshBtn = document.getElementById('refreshBtn');
            const loading = document.getElementById('loading');
            const results = document.getElementById('results');
            const resultContent = document.getElementById('resultContent');

            // Fonction pour afficher le chargement
            function showLoading() {
                loading.classList.remove('hidden');
                results.classList.add('hidden');
                testBtn.disabled = true;
                sendBtn.disabled = true;
                refreshBtn.disabled = true;
            }

            // Fonction pour masquer le chargement
            function hideLoading() {
                loading.classList.add('hidden');
                testBtn.disabled = false;
                sendBtn.disabled = false;
                refreshBtn.disabled = false;
            }

            // Fonction pour afficher les résultats
            function showResults(data, isSuccess = true) {
                hideLoading();
                results.classList.remove('hidden');
                
                const bgColor = isSuccess ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';
                const textColor = isSuccess ? 'text-green-800' : 'text-red-800';
                const icon = isSuccess ? '✅' : '❌';
                
                resultContent.className = `p-4 rounded-lg border ${bgColor}`;
                resultContent.innerHTML = `
                    <div class="${textColor}">
                        <p class="font-medium">${icon} ${data.message}</p>
                        ${data.output ? `<pre class="mt-2 text-sm whitespace-pre-wrap">${data.output}</pre>` : ''}
                    </div>
                `;
            }

            // Test des rappels
            testBtn.addEventListener('click', function() {
                showLoading();
                
                fetch('/whatsapp/test-reminders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    showResults(data, data.success);
                })
                .catch(error => {
                    hideLoading();
                    showResults({message: 'Erreur de connexion : ' + error.message}, false);
                });
            });

            // Envoi des rappels
            sendBtn.addEventListener('click', function() {
                openSendRemindersModal();
            });

            // Chargement du QR code
            function loadQRCode() {
                if (!document.getElementById('qrcode-container')) return;
                
                const qrcodeImage = document.getElementById('qrcode-image');
                const qrcodeLoading = document.getElementById('qrcode-loading');
                const qrcodeError = document.getElementById('qrcode-error');
                
                fetch('/whatsapp/qrcode')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.qrCode) {
                            qrcodeImage.src = data.qrCode;
                            qrcodeImage.classList.remove('hidden');
                            qrcodeLoading.classList.add('hidden');
                            qrcodeError.classList.add('hidden');
                        } else {
                            qrcodeImage.classList.add('hidden');
                            qrcodeLoading.classList.add('hidden');
                            qrcodeError.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        qrcodeImage.classList.add('hidden');
                        qrcodeLoading.classList.add('hidden');
                        qrcodeError.classList.remove('hidden');
                        console.error('Erreur lors du chargement du QR code:', error);
                    });
            }

            // Charger le QR code au chargement de la page
            if (!isWhatsAppConnected) {
                loadQRCode();
                
                // Rafraîchir le QR code toutes les 30 secondes
                setInterval(loadQRCode, 30000);
            }

            // Actualiser le statut
            refreshBtn.addEventListener('click', function() {
                location.reload();
            });

            function openSendRemindersModal() {
                document.getElementById('sendRemindersModal').classList.remove('hidden');
            }

            function closeSendRemindersModal() {
                document.getElementById('sendRemindersModal').classList.add('hidden');
            }

            function confirmSendReminders() {
                closeSendRemindersModal();
                showLoading();
                
                fetch('/whatsapp/send-reminders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    showResults(data, data.success);
                })
                .catch(error => {
                    hideLoading();
                    showResults({message: 'Erreur de connexion : ' + error.message}, false);
                });
            }
        });
    </script>
</x-app-layout>