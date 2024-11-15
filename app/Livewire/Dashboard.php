<?php

namespace App\Livewire;

use App\Models\Student;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('crud operation')]
class Dashboard extends Component
{
    use WithPagination;

    public string $search = '';

    public string $name = '';

    public string $email = '';

    #[Locked]
    public ?int $studentId = null;

    public string $toastMessage = '';

    public bool $showToast = false;

    public string $sortColumn = 'name';

    public string $sortDirection = 'asc';

    public array $selectedColumns = ['id', 'name', 'email'];

    public array $selectedColumnsTemp = [];

    public function saveColumnSelection(): void
    {
        $this->reset('selectedColumns');
        $this->selectedColumns = $this->selectedColumnsTemp;
    }

    public function edit(int $id): void
    {
        $student = Student::query()->findOrFail($id);

        $this->studentId = $student->id;

        $this->name = $student->name;

        $this->email = $student->email;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', Rule::unique('students', 'email')->ignore($this->studentId)],
        ];
    }

    public function store(): void
    {
        Student::query()
            ->updateOrCreate(
                ['id' => $this->studentId],
                $this->validate(),
            );

        $this->showToast = true;

        $this->toastMessage = $this->studentId ? 'Record updated successfully.' : 'Record added successfully.';

        $this->reset('studentId', 'name', 'email');

        $this->dispatch('formSubmitted');
    }

    public function destroy(int $id): void
    {
        $student = Student::findOrFail($id);

        $student->delete();

        $this->showToast = true;

        $this->toastMessage = 'Record deleted successfully.';
    }

    public function sortBy($column): void
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render(): View
    {
        $students = Student::query()
            ->select($this->selectedColumns)
            ->when($this->search, fn ($query) => $query->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        $columnNames = array_keys($students->first()->getAttributes());

        $this->reset('selectedColumns');

        $this->selectedColumns = $columnNames;

        return view('livewire.dashboard', ['students' => $students]);
    }
}
