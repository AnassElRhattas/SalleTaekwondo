<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="AASTKD - Association des Arts et Sports de Taekwondo. Découvrez nos cours de Taekwondo pour tous les niveaux à Casablanca.">
        <meta name="keywords" content="taekwondo, arts martiaux, dojo, casablanca, cours, sport, combat">
        <title>AASTKD - Association des Arts et Sports de Taekwondo</title>
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 dark:bg-gray-900">
        <!-- Barre de Navigation -->
        <nav class="bg-white dark:bg-gray-800 shadow-lg fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-red-600 dark:text-red-500">AASTKD</h1>
                    </div>
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="#programs" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500">Programmes</a>
                        <a href="#location" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500">Localisation</a>
                        <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500">Contact</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500">Tableau de bord</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500">Connexion</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-300">Inscription</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Section d'accueil -->
        <section class="relative pt-24 pb-32 bg-gradient-to-r from-red-600 to-red-800 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-5xl font-bold mb-6">Bienvenue à Notre Dojo de Taekwondo</h1>
                <p class="text-xl mb-8">Découvrez l'Art Martial Coréen</p>
                <div class="space-x-4">
                    <a href="#programs" class="bg-white text-red-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300">Découvrir nos programmes</a>
                    <a href="#location" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:bg-opacity-10 transition duration-300">Notre localisation</a>
                </div>
            </div>
        </section>

        <!-- Section Programmes -->
        <section id="programs" class="py-20 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl font-bold text-center mb-12 text-gray-900 dark:text-white">Nos Programmes d'Entraînement</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                        <h3 class="text-2xl font-semibold mb-4 text-red-600 dark:text-red-500">Cours Débutants</h3>
                        <p class="text-gray-700 dark:text-gray-300">Parfait pour les nouveaux pratiquants. Apprenez les techniques fondamentales et construisez une base solide.</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                        <h3 class="text-2xl font-semibold mb-4 text-red-600 dark:text-red-500">Entraînement Avancé</h3>
                        <p class="text-gray-700 dark:text-gray-300">Entraînement intensif pour les pratiquants expérimentés, focalisé sur les techniques avancées et le combat.</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                        <h3 class="text-2xl font-semibold mb-4 text-red-600 dark:text-red-500">Équipe de Compétition</h3>
                        <p class="text-gray-700 dark:text-gray-300">Programme d'élite pour les athlètes se préparant aux tournois et championnats.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Installations -->
        <section class="py-20 bg-gray-50 dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl font-bold text-center mb-12 text-gray-900 dark:text-white">Nos Installations</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                        <h3 class="text-2xl font-semibold mb-4 text-red-600 dark:text-red-500">Zone d'Entraînement</h3>
                        <p class="text-gray-700 dark:text-gray-300">Salle d'entraînement spacieuse avec tapis et équipements professionnels.</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                        <h3 class="text-2xl font-semibold mb-4 text-red-600 dark:text-red-500">Équipements d'Entraînement</h3>
                        <p class="text-gray-700 dark:text-gray-300">Gamme complète d'équipements incluant paos, boucliers et systèmes de score électroniques.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Horaires -->
        <section class="py-20 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl font-bold text-center mb-12 text-gray-900 dark:text-white">Horaires des Cours</h2>
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-2xl font-semibold mb-4 text-red-600 dark:text-red-500">Cours en Semaine</h3>
                            <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                                <li>Débutants : Lun/Mer/Ven 17h00 - 18h30</li>
                                <li>Avancés : Lun/Mer/Ven 18h30 - 20h00</li>
                                <li>Équipe Compétition : Mar/Jeu 18h00 - 20h00</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-2xl font-semibold mb-4 text-red-600 dark:text-red-500">Cours du Weekend</h3>
                            <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                                <li>Entraînement Libre : Sam 10h00 - 12h00</li>
                                <li>Entraînement Spécial : Dim 14h00 - 16h00</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Localisation -->
        <section id="location" class="py-20 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl font-bold text-center mb-12 text-gray-900 dark:text-white">Notre Localisation</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                        <h3 class="text-2xl font-semibold mb-4 text-red-600 dark:text-red-500">Adresse du Dojo</h3>
                        <div class="space-y-4 text-gray-700 dark:text-gray-300">
                            <p class="flex items-start">
                                <svg class="h-6 w-6 mr-2 text-red-600 dark:text-red-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Rue Chahid Mbarek, med el korri<br>El Jadida, 24020<br>Plus code: 6FXF+JP El Jadida</span>
                            </p>
                            <p class="flex items-start">
                                <svg class="h-6 w-6 mr-2 text-red-600 dark:text-red-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span>+212 6 12 34 56 78</span>
                            </p>
                            <p class="flex items-start">
                                <svg class="h-6 w-6 mr-2 text-red-600 dark:text-red-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>contact@aastkd.ma</span>
                            </p>
                            <div class="mt-6">
                                <h4 class="text-lg font-medium mb-2 text-red-600 dark:text-red-500">Heures d'ouverture</h4>
                                <p>Lundi - Vendredi: 16h00 - 21h00</p>
                                <p>Samedi: 09h00 - 13h00</p>
                                <p>Dimanche: 14h00 - 17h00</p>
                            </div>
                        </div>
                    </div>
                    <div class="h-96 rounded-lg shadow-md overflow-hidden">
                        <iframe class="w-full h-full" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3350.5756913796384!2d-8.507260023555684!3d33.25636747352787!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda91e6bbd3c18c1%3A0x4f7731f7be8add2d!2s24020%20Rue%20Chahid%20Mbarek%2C%20El%20Jadida%2024020!5e0!3m2!1sfr!2sma!4v1690234567890!5m2!1sfr!2sma" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <div class="mt-12 text-center">
                    <a href="#location" class="bg-red-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-red-700 transition duration-300 inline-flex items-center">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Obtenir l'itinéraire
                    </a>
                </div>
            </div>
        </section>

        <!-- Section Contact -->
        <section class="py-20 bg-gray-50 dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl font-bold mb-8 text-gray-900 dark:text-white">Rejoignez Notre Dojo</h2>
                <p class="text-xl mb-8 text-gray-700 dark:text-gray-300">Commencez votre voyage dans les arts martiaux aujourd'hui !</p>
                <div class="space-x-4">
                    <a href="{{ route('register') }}" class="bg-red-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-red-700 transition duration-300">S'inscrire</a>
                    <a href="#" class="bg-gray-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-gray-700 transition duration-300">Nous Contacter</a>
                </div>
            </div>
        </section>

        <!-- Pied de page -->
        <footer class="bg-gray-800 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-red-500 mb-4">AASTKD</h3>
                        <p class="text-gray-400">Association des Arts et Sports de Taekwondo, votre dojo d'excellence pour l'apprentissage des arts martiaux.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Liens Rapides</h3>
                        <ul class="space-y-2">
                            <li><a href="#programs" class="text-gray-400 hover:text-white transition duration-300">Programmes</a></li>
                            <li><a href="#location" class="text-gray-400 hover:text-white transition duration-300">Localisation</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact</h3>
                        <p class="text-gray-400 flex items-start mb-2">
                            <svg class="h-5 w-5 mr-2 text-red-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            +212 6 12 34 56 78
                        </p>
                        <p class="text-gray-400 flex items-start">
                            <svg class="h-5 w-5 mr-2 text-red-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            contact@aastkd.ma
                        </p>
                    </div>
                </div>
                <div class="border-t border-gray-700 pt-8 text-center">
                    <p class="text-gray-400">&copy; {{ date('Y') }} AASTKD. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
