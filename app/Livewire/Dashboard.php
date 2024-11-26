<?php

namespace App\Livewire;

use App\Models\Student;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('CRUD Operation')]
class Dashboard extends Component
{
    use WithPagination;

    public string $search = '';
    public string $name = '';
    public string $email = '';
    #[Locked]
    public ?int $studentId = null;

    public string $sortColumn = 'name';
    public string $sortDirection = 'asc';
    public array $student = [];

    public array $columns = ['id', 'name', 'email']; 
    public array $selectedColumns = ['id', 'name', 'email']; 
    public array $unselectedColumns = []; 

    public function mount(): void
    {
        $cachedColumns = Cache::get('selected_columns');
        if($cachedColumns){
            $this->selectedColumns = $cachedColumns;
            $this->unselectedColumns = array_diff($this->columns, $this->selectedColumns);
        }else{
            $this->selectedColumns = ['id', 'name', 'email'];
            $this->unselectedColumns = [];
        }
    }

    public function saveColumnSelection(): void
    {
        $this->unselectedColumns = array_diff($this->columns, $this->selectedColumns);

        $this->selectedColumns = array_values($this->selectedColumns);

        $this->unselectedColumns = array_values($this->unselectedColumns);
        
        Cache::put('selected_columns', $this->selectedColumns, now()->addDays(1));
    }

    public function edit(Student $student): void
    {
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
        Student::updateOrCreate(
            ['id' => $this->studentId],
            $this->validate()
        );
        
        Toaster::success($this->studentId ? "Record Updated Successfully." : "Record Deleted Successfully.");

        $this->reset('studentId', 'name', 'email');

        $this->dispatch('formSubmitted');
    }

    public function destroy(Student $student): void
    {
        $student->delete();

        Toaster::success("record deleted successfully");
    }

    public function sortBy(string $column): void
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
        $this->selectedColumns = filled($this->selectedColumns) ? $this->selectedColumns : ['id', 'name', 'email'];

        $students = Student::query()
            ->select($this->selectedColumns) 
            ->when($this->search, fn ($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        $this->student = $students->getCollection()->toArray();
            return view('livewire.dashboard', [
                'students' => $students,
            ]);
    }
}
