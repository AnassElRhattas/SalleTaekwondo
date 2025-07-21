<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AASTKD</title>
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
                <a href="#programs" class="bg-white text-red-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300">Découvrir nos programmes</a>
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
        <footer class="bg-gray-800 text-white py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p>&copy; {{ date('Y') }} Dojo Elite. Tous droits réservés.</p>
            </div>
        </footer>
    </body>
</html>
