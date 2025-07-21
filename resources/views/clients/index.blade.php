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
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">birth date</th>
                                    <th scope="col" class="px-6 py-3">Phone</th>
                                    <th scope="col" class="px-6 py-3">Address</th>
                                    <th scope="col" class="px-6 py-3">Registration Date</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">{{ $client->name }}</td>
                                        <td class="px-6 py-4">{{ $client->birth_date }}</td>
                                        <td class="px-6 py-4">{{ $client->phone }}</td>
                                        <td class="px-6 py-4">{{ $client->address }}</td>
                                        <td class="px-6 py-4">{{ $client->created_at }}</td>
                                        <td class="px-6 py-4 flex space-x-2">
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
    </script>
</x-app-layout>