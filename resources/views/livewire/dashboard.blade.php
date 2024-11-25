<div class="container mx-auto mt-10" x-data="{ showModel: false, show: false }" x-init=" @this.on('formSubmitted', () => showModel = false)">
    <h2 class="text-center text-2xl font-semibold mb-6">CRUD Operation</h2>

    <div class="flex flex-col md:flex-row md:justify-between items-center mb-6">
        <button type="button" @click="showModel = true" class="bg-blue-600 text-white py-2 px-4 rounded mb-4 md:mb-0">
            Add New Record
        </button>
        <button @click="$dispatch('toggle-modal', { show: true })" class="bg-blue-600 text-white py-2 px-4 rounded mb-4 md:mb-0">Select Columns</button>
        <input type="text" wire:model.live="search" class="w-full md:w-1/2 p-2 border border-gray-300 rounded" placeholder="Search by name or email...">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto border border-gray-300">
            <thead class="bg-gray-800 text-white text-center">
            <tr>
                @foreach($selectedColumns as $column)
                    <th class="p-3 border border-gray-300" @click="$wire.sortBy({{ $column }})">{{ $column }}</th>
                @endforeach
                <th class="p-3 border border-gray-300" @click="show=true">Actions</th>
            </tr>
            </thead>
            <tbody x-sort>
                @foreach ($students as $student)
                <tr wire:key="{{ $student->id }}" class="bg-gray-100 text-center hover:bg-gray-200" x-sort:item>
                    @if($student->id)
                        <td class="p-3 border border-gray-300">{{ $student->id }}</td>
                    @endif
                    @if($student->name)
                        <td class="p-3 border border-gray-300">{{ $student->name }}</td>
                    @endif
                    @if($student->email)
                        <td class="p-3 border border-gray-300">{{ $student->email }}</td>
                    @endif
                    <td class="p-3 border border-gray-300">
                        <button class="bg-blue-500 text-white py-1 px-3 rounded mr-2" @click="showModel = true; $wire.edit({{ $student->id }})">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12.293 3.707a1 1 0 0 1 1.414 0l5 5a1 1 0 0 1 0 1.414L7.707 19.707a1 1 0 0 1-.447.293L3 21l.586-4.293a1 1 0 0 1 .293-.447L15.879 3.707a1 1 0 0 1 0-1.414z" />
                            </svg>
                        </button>
                        <button class="bg-red-600 text-white py-1 px-3 rounded" wire:confirm wire:click="destroy({{ $student->id }})">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M6 19c0 .552.448 1 1 1h10c.552 0 1-.448 1-1V7H6v12zm9-14V3H9v2H4v2h16V5h-5z" />
                            </svg>
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
                    <input wire:model="name" type="text" id="name" class="w-full mt-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300" placeholder="Enter name" autocomplete="name">
                    @error('name')
                    <span class="text-red-500 mt-2 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium">Email</label>
                    <input wire:model="email" type="email" id="email" class="w-full mt-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300" placeholder="Enter email" autocomplete="email">
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

    <div x-data="{ 
        selectedColumns: @entangle('selectedColumns'), 
        unselectedColumns: @entangle('unselectedColumns'), 
        toggleColumn(column) {
            if (this.selectedColumns.includes(column)) {
                this.selectedColumns = this.selectedColumns.filter(c => c !== column);
                this.unselectedColumns.push(column);
            } else {
                this.unselectedColumns = this.unselectedColumns.filter(c => c !== column);
                this.selectedColumns.push(column);
            }
        } 
    }">
    <!-- Column Selection Modal -->
    <div x-data="{ show: false }" x-on:toggle-modal.window="show = $event.detail.show" x-show="show" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-md">
            <h2 class="text-xl font-semibold mb-4">Select Columns</h2>
            <div class="grid grid-cols-2 gap-4">
                <!-- Checked List -->
                <div>
                    <h3 class="font-medium mb-2">Selected Columns</h3>
                    <ul>
                        <template x-for="column in selectedColumns" :key="column">
                            <li class="flex items-center mb-2">
                                <input type="checkbox" :value="column" checked @change="toggleColumn(column)" class="mr-2">
                                <span x-text="column"></span>
                            </li>
                        </template>
                    </ul>
                </div>
                <!-- Unchecked List -->
                <div>
                    <h3 class="font-medium mb-2">Unselected Columns</h3>
                    <ul>
                        <template x-for="column in unselectedColumns" :key="column">
                            <li class="flex items-center mb-2">
                                <input type="checkbox" :value="column" @change="toggleColumn(column)" class="mr-2">
                                <span x-text="column"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button @click="$dispatch('toggle-modal', { show: false })" class="bg-red-500 text-white px-4 py-2 rounded">Close</button>
                <button @click="$dispatch('toggle-modal', { show: false }); $wire.saveColumnSelection()" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Save</button>
            </div>
        </div>
    </div>
</div>