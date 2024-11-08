<div class="container mt-5">
    <h2 class="text-center mb-4">CRUD Operation</h2>

    <div class="row mb-3">
        <div class="col-md-4">
            <button type="button" wire:click="showModel=true" class="btn btn-primary mb-3">
                Add New Record
            </button>
        </div>
        <div class="col-md-8">
            <input type="text" wire:model.live="search" class="form-control" placeholder="Search by name or email...">
        </div>
    </div>

    

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>
                    <button class="btn btn-warning btn-sm" wire:click="edit({{ $student->id }})">Edit</button>
                    <button class="btn btn-danger btn-sm" wire:confirm="are u sure want to delete?" wire:click="delete({{ $student->id }})">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
        {{ $students->links() }}
    </div>

    <!-- Modal for Adding/Editing -->
    <div wire:ignore.self class="modal fade" :class="$wire.showModel ? 'show' : ''" :style="$wire.showModel ? 'display:block' : 'display:none'" aria-hidden="{{ $showModel ? 'false' : 'true' }}" id="crudModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crudModalLabel">{{ $studentId ? 'Edit Record' : 'Add Record' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="store">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input wire:model="name" type="text" class="form-control" id="name" placeholder="Enter name">
                            @error('name')
                                <span class="text-danger mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input wire:model="email" type="email" class="form-control" id="email" placeholder="Enter email">
                            @error('email')
                                <span class="text-danger mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">{{ $studentId ? 'Update' : 'Save' }}</button>
                        <div wire:loading> 
                            Saving data...
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
