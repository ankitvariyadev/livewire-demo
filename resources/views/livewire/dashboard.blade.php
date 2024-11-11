<div class="container mx-auto mt-10">
    <h2 class="text-center text-2xl font-semibold mb-6">CRUD Operation</h2>

    <div class="flex flex-col md:flex-row md:justify-between items-center mb-6">
        <button type="button" wire:click="showModel=true" class="bg-blue-600 text-white py-2 px-4 rounded mb-4 md:mb-0">
            Add New Record
        </button>
        <input type="text" wire:model.live="search" class="w-full md:w-1/2 p-2 border border-gray-300 rounded" placeholder="Search by name or email...">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto border border-gray-300">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-3 border border-gray-300">ID</th>
                    <th class="p-3 border border-gray-300">Name</th>
                    <th class="p-3 border border-gray-300">Email</th>
                    <th class="p-3 border border-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr class="bg-gray-100 text-center hover:bg-gray-200">
                        <td class="p-3 border border-gray-300">{{ $student->id }}</td>
                        <td class="p-3 border border-gray-300">{{ $student->name }}</td>
                        <td class="p-3 border border-gray-300">{{ $student->email }}</td>
                        <td class="p-3 border border-gray-300">
                            <button class="bg-yellow-500 text-white py-1 px-3 rounded mr-2" wire:click="edit({{ $student->id }})">Edit</button>
                            <button class="bg-red-600 text-white py-1 px-3 rounded" wire:confirm="Are you sure you want to delete?" wire:click="delete({{ $student->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $students->links() }}
    </div>

    <div x-show="$wire.showModel" 
    x-transition:enter="transform transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-10"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transform transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-10"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" 
    wire:ignore.self>
   
   <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">
       <div class="flex justify-between items-center mb-4">
           <h5 class="text-xl font-semibold">{{ $studentId ? 'Edit Record' : 'Add Record' }}</h5>
           <button type="button" @click="$wire.showModel = false" class="text-gray-600 hover:text-gray-900">
               &times;
           </button>
       </div>
       <form wire:submit.prevent="store">
           <div class="mb-4">
               <label for="name" class="block text-gray-700 font-medium">Name</label>
               <input wire:model="name" type="text" id="name" class="w-full mt-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300" placeholder="Enter name">
               @error('name')
                   <span class="text-red-600 mt-2 text-sm">{{ $message }}</span>
               @enderror
           </div>
           <div class="mb-4">
               <label for="email" class="block text-gray-700 font-medium">Email</label>
               <input wire:model="email" type="email" id="email" class="w-full mt-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300" placeholder="Enter email">
               @error('email')
                   <span class="text-red-600 mt-2 text-sm">{{ $message }}</span>
               @enderror
           </div>
           <div class="flex justify-end items-center">
               <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">{{ $studentId ? 'Update' : 'Save' }}</button>
               <div wire:loading class="ml-4 text-gray-500">
                   Saving data...
               </div>
           </div>
       </form>
   </div>
</div>
<div x-show="toastVisible" x-transition:enter="transition ease-in duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" class="fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
    <p>{{ $toastMessage }}</p>
</div>
<div x-data="{ toastVisible: false, toastMessage: '' }" x-init="$wire.on('show-toast', message => { toastVisible = true; toastMessage = message; })">
    <!-- Toast Notification -->
    <div x-show="toastVisible" x-transition:enter="transition ease-in duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" class="fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
        <p x-text="toastMessage"></p>
    </div>
</div>  
</div>
