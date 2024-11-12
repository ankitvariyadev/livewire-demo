<?php
namespace App\Livewire;

use App\Models\Student;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')] 
class Dashboard extends Component
{
    use WithPagination;

    public string $search = '';
    public string $name = '';
    public string $email = '';
    public ?int $studentId = null; 
    public string $toastMessage = '';
    public bool $showToast =  false;
    public string $sortColumn = 'name';
    public string $sortDirection = 'asc';

    public function edit(int $id): void 
    {
        $student = Student::query()->findOrFail($id);

        $this->studentId = $student->id;

        $this->name = $student->name;

        $this->email = $student->email;
    }

    public function rules() : array 
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', Rule::unique('students', 'email')->ignore($this->studentId)]
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

    public function delete(int $id): void
    {
        $student = Student::findOrFail($id);

        $student->delete();

        $this->showToast = true;

        $this->toastMessage = 'Record deleted successfully.';
    }

    public function sortBy($column) : void
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render() : View
    {
        $students = Student::query()
            ->when($this->search, fn($query) => $query->where('name', 'like' , '%'.$this->search.'%'))
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        return view('livewire.dashboard', ['students' => $students]);
    }
}
