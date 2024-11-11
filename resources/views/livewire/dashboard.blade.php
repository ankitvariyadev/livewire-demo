<div class="container mx-auto mt-10" x-data="{ showModel: false }" x-init=" @this.on('formSubmitted', () => showModel = false)">
    <h2 class="text-center text-2xl font-semibold mb-6">CRUD Operation</h2>

    <div class="flex flex-col md:flex-row md:justify-between items-center mb-6">
        <button type="button" @click="showModel = true" class="bg-blue-600 text-white py-2 px-4 rounded mb-4 md:mb-0">
            Add New Record
        </button>
        <input type="text" wire:model.live="search" class="w-full md:w-1/2 p-2 border border-gray-300 rounded" placeholder="Search by name or email...">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto border border-gray-300">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-3 border border-gray-300">ID</th>
                    <th class="p-3 border border-gray-300 cursor-pointer" wire:click="sortBy('name')">
                        Name
                        @if ($sortColumn == 'name')
                            @if ($sortDirection == 'asc')
                                &#9650; 
                            @else
                                &#9660; 
                            @endif
                        @endif
                    </th>
                    <th class="p-3 border border-gray-300 cursor-pointer" wire:click="sortBy('email')">
                        Email
                        @if ($sortColumn == 'email')
                            @if ($sortDirection == 'asc')
                                &#9650;
                            @else
                                &#9660;
                            @endif
                        @endif
                    </th>
                    <th class="p-3 border border-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                <tr wire:key="{{ $student->id }}" class="bg-gray-100 text-center hover:bg-gray-200">
                    <td class="p-3 border border-gray-300">{{ $student->id }}</td>
                    <td class="p-3 border border-gray-300">{{ $student->name }}</td>
                    <td class="p-3 border border-gray-300">{{ $student->email }}</td>
                    <td class="p-3 border border-gray-300">
                            <button class="bg-blue-500 text-white py-1 px-3 rounded mr-2" wire:click="edit({{ $student->id }})" @click="showModel = true">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.293 3.707a1 1 0 0 1 1.414 0l5 5a1 1 0 0 1 0 1.414L7.707 19.707a1 1 0 0 1-.447.293L3 21l.586-4.293a1 1 0 0 1 .293-.447L15.879 3.707a1 1 0 0 1 0-1.414z"/></svg>    
                            </button>
                            <button class="bg-red-600 text-white py-1 px-3 rounded" wire:confirm @click="confirmDelete({{ $student->id }})">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6 19c0 .552.448 1 1 1h10c.552 0 1-.448 1-1V7H6v12zm9-14V3H9v2H4v2h16V5h-5z"/></svg>
                            </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $students->links() }}
    </div>

    <div x-show="showModel" x-transition:enter="transform transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-10" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transform transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-10" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" @click.away="showModel = false">
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-xl font-semibold">{{ $studentId ? 'Edit Record' : 'Add Record' }}</h5>
                <button type="button" @click="showModel = false" class="text-gray-600 hover:text-gray-900">
                    &times;
                </button>
            </div>
            <form wire:submit.prevent="store">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium">Name</label>
                    <input wire:model="name" type="text" id="name" class="w-full mt-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300" placeholder="Enter name">
                    @error('name')
                    <span class="text-red-500 mt-2 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium">Email</label>
                    <input wire:model="email" type="email" id="email" class="w-full mt-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300" placeholder="Enter email">
                    @error('email')
                    <span class="text-red-500 mt-2 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end items-center">
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">{{ $studentId ? 'Update' : 'Save' }}
                        <div wire:loading wire:target="store" class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-e-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($showToast)
    <div x-data="{ visible: true }" x-init="setTimeout(() => visible = false, 3000)" x-show="visible" x-transition.opacity.duration.1000ms id="toast-success" class="fixed bottom-4 left-1/2 transform -translate-x-1/2 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
            </svg>
            <span class="sr-only">Check icon</span>
        </div>
        <div class="ms-3 text-sm font-normal">{{ $toastMessage }}</div>
        <button type="button" @click="visible = false" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>
    @endif
</div>
