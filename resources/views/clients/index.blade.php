<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Clients List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Search Bar -->
                    <div class="mb-4">
                        <input type="text" id="searchInput" placeholder="Search by name..." class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition duration-200">
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">#</th>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">birth date</th>
                                    <th scope="col" class="px-6 py-3">Phone</th>
                                    <th scope="col" class="px-6 py-3">Address</th>
                                    <th scope="col" class="px-6 py-3">Registration Date</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
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
                                        <td class="px-6 py-4">{{ $client->created_at }}</td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <button onclick="viewDetails({{ $client->id }}, '{{ $client->name }}', '{{ $client->birth_date }}', '{{ $client->phone }}', '{{ $client->address }}', '{{ $client->created_at }}', '{{ $client->payer_abon }}')" 
                                                class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">View</button>
                                            <button onclick="openEditModal({{ $client->id }}, '{{ $client->name }}', '{{ $client->birth_date }}', '{{ $client->phone }}', '{{ $client->address }}')" 
                                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">Edit</button>
                                            <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                                                    onclick="return confirm('Are you sure you want to delete this client?')">Delete</button>
                                            </form>
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

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-40 hidden overflow-y-auto h-full w-full backdrop-blur-sm transition-all duration-300">
        <div class="relative top-20 mx-auto p-8 border-0 w-[500px] shadow-2xl rounded-xl bg-white dark:bg-gray-800 transform transition-all duration-300">
            <div class="mt-2">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Client Details</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Name</h4>
                        <p id="detailName" class="text-lg text-gray-900 dark:text-white"></p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Birth Date</h4>
                        <p id="detailBirthDate" class="text-lg text-gray-900 dark:text-white"></p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Phone</h4>
                        <p id="detailPhone" class="text-lg text-gray-900 dark:text-white"></p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Address</h4>
                        <p id="detailAddress" class="text-lg text-gray-900 dark:text-white"></p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Registration Date</h4>
                        <p id="detailRegistrationDate" class="text-lg text-gray-900 dark:text-white"></p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Last Payer</h4>
                        <p id="detailLastPayer" class="text-lg text-gray-900 dark:text-white"></p>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
    <button id="validatePaymentBtn"
            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg mr-3">
        Valider le paiement
    </button>
    <button onclick="closeDetailsModal()" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg">Close</button>
</div>

            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 hidden overflow-y-auto h-full w-full backdrop-blur-sm transition-all duration-300">
        <div class="relative top-20 mx-auto p-8 border-0 w-[500px] shadow-2xl rounded-xl bg-white dark:bg-gray-800 transform transition-all duration-300">
            <div class="mt-2">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Edit Client</h3>
                <form id="editForm" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-gray-700 dark:text-gray-200 text-sm font-semibold mb-2" for="name">Name</label>
                        <input type="text" name="name" id="editName" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition duration-200">
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-200 text-sm font-semibold mb-2" for="birth_date">Birth Date</label>
                        <input type="date" name="birth_date" id="editBirthDate" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition duration-200">
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-200 text-sm font-semibold mb-2" for="phone">Phone</label>
                        <input type="text" name="phone" id="editPhone" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition duration-200">
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-200 text-sm font-semibold mb-2" for="address">Address</label>
                        <textarea name="address" id="editAddress" rows="3" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition duration-200"></textarea>
                    </div>
                    <div class="flex items-center justify-end space-x-4 mt-8">
                        <button type="button" onclick="closeEditModal()" 
                            class="px-6 py-3 rounded-lg bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-500 transition duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const name = row.children[1].textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        function openEditModal(id, name, birthDate, phone, address) {
            document.getElementById('editForm').action = `/clients/${id}`;
            document.getElementById('editName').value = name;
            document.getElementById('editBirthDate').value = birthDate;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editAddress').value = address;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function viewDetails(id, name, birthDate, phone, address, registrationDate, lastPayer) {
            document.getElementById('detailName').textContent = name;
            document.getElementById('detailBirthDate').textContent = birthDate;
            document.getElementById('detailPhone').textContent = phone;
            document.getElementById('detailAddress').textContent = address;
            document.getElementById('detailRegistrationDate').textContent = registrationDate;
            document.getElementById('detailLastPayer').textContent = lastPayer;
            document.getElementById('detailsModal').classList.remove('hidden');
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }
    </script>
</x-app-layout>