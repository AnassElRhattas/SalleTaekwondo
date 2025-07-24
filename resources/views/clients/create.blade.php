<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Client') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('clients.store') }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf

                        <div class="flex gap-6">
                            <div class="flex-1 space-y-6">
                                <div>
                                    <x-input-label for="name" :value="__('Name (الاسم الكامل)')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="birth_date" :value="__('Birth Date (تاريخ الازدياد)')" />
                                    <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date')" required />
                                    <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="phone" :value="__('Phone (رقم الهاتف)')" />
                                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="address" :value="__('Address (العنوان)')" />
                                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address')" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>
                            </div>

                            <div class="w-48 space-y-2">
                                <x-input-label for="profile_picture" :value="__('Profile Picture (الصورة الشخصية)')" class="text-sm font-medium" />
                                <div class="flex items-center justify-center w-full">
                                    <label for="profile_picture" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-6 h-6 mb-2 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Click to upload</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG or GIF</p>
                                        </div>
                                        <input id="profile_picture" name="profile_picture" type="file" accept="image/*" class="hidden" />
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="Birth_contract" :value="__('Birth contract (عقد الازدياد)')" class="text-lg font-medium" />
                            <div class="flex items-center justify-center w-full">
                                <label for="Birth_contract" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG or GIF</p>
                                    </div>
                                    <input id="Birth_contract" name="Birth_contract" type="file" accept="image/*" class="hidden" />
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('Birth_contract')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Add Client') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
    // Function to handle image preview
    function handleImagePreview(inputId) {
        document.getElementById(inputId).addEventListener('change', function (event) {
            const file = event.target.files[0];
            const label = event.target.closest('label');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Clear existing content
                    label.innerHTML = '';
                    
                    // Create and add the preview image
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'object-cover w-full h-full rounded-lg';
                    
                    // Add the file input back
                    const input = event.target.cloneNode(true);
                    label.appendChild(img);
                    label.appendChild(input);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Initialize preview for both upload fields
    handleImagePreview('profile_picture');
    handleImagePreview('Birth_contract');
</script>
</x-app-layout>