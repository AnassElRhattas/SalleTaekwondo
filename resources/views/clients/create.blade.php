<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add New Client') }}
            </h2>
            <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-600 focus:outline-none focus:border-gray-900 dark:focus:border-gray-500 focus:ring ring-gray-300 dark:ring-gray-700 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div id="success-alert" class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded flex justify-between items-center">
                            <div>
                                <p class="font-bold">Succès!</p>
                                <p>{{ session('success') }}</p>
                            </div>
                            <button onclick="document.getElementById('success-alert').remove()" class="text-green-700 hover:text-green-900">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                    
                    <!-- Progress Indicator -->
                    <!-- <div class="mb-6">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progression du formulaire</span>
                            <span id="form-progress-percentage" class="text-sm font-medium text-gray-700 dark:text-gray-300">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                            <div id="form-progress-bar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                        </div>
                    </div> -->

                    <form method="POST" action="{{ route('clients.store') }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Section Informations Personnelles -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Informations Personnelles') }}</h3>
                                
                                <div>
                                    <x-input-label for="name" :value="__('Name (الاسم الكامل)')" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full pl-10" :value="old('name')" required autofocus placeholder="Nom complet du client" />
                                    </div>
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="birth_date" :value="__('Birth Date (تاريخ الازدياد)')" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full pl-10" :value="old('birth_date')" required />
                                    </div>
                                    <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="phone" :value="__('Phone (رقم الهاتف)')" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </div>
                                        <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full pl-10" :value="old('phone')" placeholder="06XXXXXXXX" pattern="[0-9]{10}" />
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: 10 chiffres sans espaces</p>
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="address" :value="__('Address (العنوان)')" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full pl-10" :value="old('address')" placeholder="Adresse complète" />
                                    </div>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>
                                <div class="w-full space-y-2">
                                    <x-input-label for="profile_picture" :value="__('Profile Picture (الصورة الشخصية)')" class="text-sm font-medium" />
                                    <div class="flex items-center justify-center w-full">
                                        <label for="profile_picture" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 transition-all duration-300 ease-in-out relative overflow-hidden">
                                            <div id="profile_picture_placeholder" class="flex flex-col items-center justify-center pt-5 pb-6 absolute inset-0 transition-opacity duration-300">
                                                <svg class="w-8 h-8 mb-3 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Photo de profil</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Cliquez pour télécharger</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG ou GIF</p>
                                            </div>
                                            <div id="profile_picture_preview" class="hidden w-full h-full">
                                                <!-- Preview will be inserted here by JS -->
                                            </div>
                                            <input id="profile_picture" name="profile_picture" type="file" accept="image/*" class="hidden" />
                                        </label>
                                    </div>
                                    <div class="flex justify-center mt-2">
                                        <button type="button" id="remove_profile_picture" class="text-xs text-red-600 hover:text-red-800 hidden">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Section Informations Sportives -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Informations Sportives') }}</h3>

                                
                                
                                <div>
                                    <x-input-label for="group" :value="__('Groupe (المجموعة)')" />
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <select id="group" name="group" class="mt-1 block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                            <option value="" disabled {{ old('group') ? '' : 'selected' }}>{{ __('Sélectionnez un groupe') }}</option>
                                            <option value="Box" {{ old('group') == 'Box' ? 'selected' : '' }}>Box</option>
                                            <option value="Taekwondo" {{ old('group') == 'Taekwondo' ? 'selected' : '' }}>Taekwondo</option>
                                            <option value="Karaté" {{ old('group') == 'Karaté' ? 'selected' : '' }}>Karaté</option>
                                        </select>
                                    </div>
                                    <x-input-error :messages="$errors->get('group')" class="mt-2" />
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="weight" :value="__('Weight (الوزن) en kg')" />
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                                </svg>
                                            </div>
                                            <x-text-input id="weight" name="weight" type="number" step="0.01" class="mt-1 block w-full pl-10" :value="old('weight')" placeholder="Poids en kg" />
                                        </div>
                                        <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="height" :value="__('Height (الطول) en cm')" />
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 3a4 4 0 100 8 4 4 0 000-8z" />
                                                </svg>
                                            </div>
                                            <x-text-input id="height" name="height" type="number" step="0.01" class="mt-1 block w-full pl-10" :value="old('height')" placeholder="Taille en cm" />
                                        </div>
                                        <x-input-error :messages="$errors->get('height')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <x-input-label for="Birth_contract" :value="__('Birth contract (عقد الازدياد)')" class="text-lg font-medium" />
                                    <div class="flex items-center justify-center w-full">
                                        <label for="Birth_contract" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 transition-all duration-300 ease-in-out relative overflow-hidden">
                                            <div id="Birth_contract_placeholder" class="flex flex-col items-center justify-center pt-5 pb-6 absolute inset-0 transition-opacity duration-300">
                                                <svg class="w-10 h-10 mb-3 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <p class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Acte de naissance</p>
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Cliquez pour télécharger</span> ou glissez-déposez</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG ou GIF</p>
                                            </div>
                                            <div id="Birth_contract_preview" class="hidden w-full h-full">
                                                <!-- Preview will be inserted here by JS -->
                                            </div>
                                            <input id="Birth_contract" name="Birth_contract" type="file" accept="image/*" class="hidden" />
                                        </label>
                                    </div>
                                    <div class="flex justify-center mt-2">
                                        <button type="button" id="remove_Birth_contract" class="text-xs text-red-600 hover:text-red-800 hidden">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('Birth_contract')" class="mt-2" />
                                </div>
                            </div>


                        </div>



                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <x-primary-button type="submit" id="submit-button" class="bg-blue-600 hover:bg-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    {{ __('Add Client') }}
                                </x-primary-button>
                                <button type="reset" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-600 focus:outline-none focus:border-gray-900 dark:focus:border-gray-500 focus:ring ring-gray-300 dark:ring-gray-700 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    {{ __('Reset') }}
                                </button>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <span class="text-red-500">*</span> Champs obligatoires
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation and progress tracking
        const requiredFields = document.querySelectorAll('input[required], select[required]');
        const progressBar = document.getElementById('form-progress-bar');
        const progressPercentage = document.getElementById('form-progress-percentage');
        const submitButton = document.getElementById('submit-button');
        
        // Function to update progress bar
        function updateProgressBar() {
            let filledCount = 0;
            requiredFields.forEach(field => {
                if (field.value.trim() !== '') {
                    filledCount++;
                }
            });
            
            const percentage = Math.round((filledCount / requiredFields.length) * 100);
            progressBar.style.width = `${percentage}%`;
            progressPercentage.textContent = `${percentage}%`;
            
            // Change progress bar color based on completion
            if (percentage < 30) {
                progressBar.classList.remove('bg-yellow-500', 'bg-green-500');
                progressBar.classList.add('bg-red-500');
            } else if (percentage < 70) {
                progressBar.classList.remove('bg-red-500', 'bg-green-500');
                progressBar.classList.add('bg-yellow-500');
            } else {
                progressBar.classList.remove('bg-red-500', 'bg-yellow-500');
                progressBar.classList.add('bg-green-500');
            }
        }
        
        // Add event listeners to all required fields
        requiredFields.forEach(field => {
            field.addEventListener('input', updateProgressBar);
            field.addEventListener('change', updateProgressBar);
        });
        
        // Initialize progress bar
        updateProgressBar();
        
        // Phone number validation
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                // Remove non-numeric characters
                this.value = this.value.replace(/\D/g, '');
                
                // Limit to 10 digits
                if (this.value.length > 10) {
                    this.value = this.value.slice(0, 10);
                }
            });
        }
        
        // Enhanced image preview handling
        function handleEnhancedImagePreview(inputId) {
            const input = document.getElementById(inputId);
            const placeholder = document.getElementById(`${inputId}_placeholder`);
            const preview = document.getElementById(`${inputId}_preview`);
            const removeButton = document.getElementById(`remove_${inputId}`);
            
            input.addEventListener('change', function(event) {
                const file = event.target.files[0];
                
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Clear existing preview content
                        preview.innerHTML = '';
                        
                        // Create and add the preview image
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'object-cover w-full h-full rounded-lg';
                        preview.appendChild(img);
                        
                        // Show preview, hide placeholder
                        placeholder.classList.add('opacity-0');
                        preview.classList.remove('hidden');
                        removeButton.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Remove image functionality
            if (removeButton) {
                removeButton.addEventListener('click', function() {
                    input.value = ''; // Clear the file input
                    preview.innerHTML = ''; // Clear the preview
                    preview.classList.add('hidden');
                    placeholder.classList.remove('opacity-0');
                    removeButton.classList.add('hidden');
                });
            }
        }
        
        // Initialize enhanced preview for both upload fields
        handleEnhancedImagePreview('profile_picture');
        handleEnhancedImagePreview('Birth_contract');
        
        // Form submission validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            let isValid = true;
            let firstInvalidField = null;
            
            // Check all required fields
            requiredFields.forEach(field => {
                if (field.value.trim() === '') {
                    isValid = false;
                    field.classList.add('border-red-500');
                    if (!firstInvalidField) firstInvalidField = field;
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            // Scroll to first invalid field if form is invalid
            if (!isValid) {
                event.preventDefault();
                firstInvalidField.focus();
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
        
        // Auto-dismiss success alert after 5 seconds
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => {
                    successAlert.remove();
                }, 500);
            }, 5000);
        }
    });
</script>
</x-app-layout>