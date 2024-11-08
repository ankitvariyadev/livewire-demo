<?php
namespace App\Livewire;

use App\Models\Student;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')] 
class Dashboard extends Component
{
    use WithPagination;

    public string $search = '';
    public string $name = '';
    public string $email = '';
    public bool $showModel = false;
    public ?int $studentId = null; 

    public function edit(int $id): void 
    {
        $student = Student::query()->findOrFail($id);
        $this->studentId = $student->id;
        $this->name = $student->name;
        $this->email = $student->email;
        $this->showModel = true;
    }

    public function store(): void
    {
        $validatedData = $this->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', Rule::unique('students', 'email')->ignore($this->studentId)],
        ]);

            Student::query()
                ->updateOrCreate(
                    ['id' => $this->studentId],
                    $validatedData,
                );

        $this->reset(['showModel', 'name', 'email', 'studentId']);
    }

    public function delete(int $id): void
    {
        $student = Student::findOrFail($id);
        $student->delete();
    }

    public function render()
    {
        return view('livewire.dashboard', ['students' => Student::query()->when($this->search, fn($query) => $query->where('name', 'like' , '%'.$this->search.'%')  )->paginate(5)]);
    }
}
