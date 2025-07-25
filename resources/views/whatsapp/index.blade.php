<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rappels WhatsApp') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Génération de fichier CSV pour rappels WhatsApp</h3>
                    
                    <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                        <p class="text-blue-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            Ce système génère un fichier CSV contenant les informations nécessaires pour envoyer des rappels WhatsApp
                            aux clients dont l'abonnement expire dans les 3 prochains jours ou a déjà expiré. Le fichier est formaté
                            spécifiquement pour être utilisé avec l'extension WA Web Utils.
                        </p>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-center">
                            <div class="bg-gray-100 rounded-lg p-4 flex-1">
                                <p class="font-medium">Clients avec abonnements expirants aujourd'hui :</p>
                                <p class="text-2xl font-bold mt-2">{{ $expiringCount }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-medium mb-2">Fichier CSV pour aujourd'hui</h4>
                        
                        @if ($fileExists)
                            <div class="flex items-center space-x-4">
                                <span class="text-green-600">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Le fichier <strong>{{ $filename }}</strong> est disponible
                                </span>
                                <a href="{{ route('whatsapp.download') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-download mr-2"></i>
                                    Télécharger
                                </a>
                            </div>
                        @else
                            <div class="text-yellow-600 mb-4">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Aucun fichier n'a encore été généré pour aujourd'hui
                            </div>
                            
                            <form action="{{ route('whatsapp.generate') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-file-csv mr-2"></i>
                                    Générer et télécharger
                                </button>
                            </form>
                        @endif
                    </div>
                    
                    <div class="mt-8 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium mb-2">Instructions</h4>
                        <ol class="list-decimal list-inside space-y-2">
                            <li>Cliquez sur <strong>Générer et télécharger</strong> pour créer le fichier CSV</li>
                            <li>Importez ce fichier dans l'extension <strong>WA Web Utils</strong> sur WhatsApp Web</li>
                            <li>Vérifiez que les messages sont correctement formatés</li>
                            <li>Envoyez les messages via l'extension</li>
                        </ol>
                    </div>
                    
                    <div class="mt-4 bg-yellow-50 p-4 rounded-lg">
                        <h4 class="font-medium mb-2">Format du fichier CSV</h4>
                        <p class="mb-2">Le fichier CSV généré contient les colonnes suivantes :</p>
                        <ul class="list-disc list-inside space-y-1 ml-2">
                            <li><strong>Name</strong> : Nom du client</li>
                            <li><strong>Phone</strong> : Numéro de téléphone au format international (212...)</li>
                            <li><strong>Name</strong> : Nom du client (répété)</li>
                            <li><strong>PhraseExperation</strong> : Phrase d'expiration ("votre abonnement expire dans" ou "votre abonnement a expiré il y a")</li>
                            <li><strong>Days</strong> : Nombre de jours avant/après expiration</li>
                        </ul>
                    </div>
                    
                    @if (session('error'))
                        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>