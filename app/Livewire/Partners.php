<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class Partners extends Component
{
    use WithPagination;

    public $name, $email, $company;
    public $partnerId = null;
    public $perPage = 10;
    public $searchTerm = "";
    public $orderBy = 'name';
    public $orderDirection = 'asc';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'company' => 'required|string|max:255',
    ];

    protected $messages = [
        'name.required' => 'Имя обязательно для заполнения',
        'email.required' => 'Email обязателен для заполнения',
        'email.email' => 'Введите корректный email',
        'email.unique' => 'Такой email уже существует',
        'company.required' => 'Название компании обязательно',
    ];

    public function render()
    {
        $partners = User::role('partner')
            ->withTrashed()
            ->when($this->searchTerm, function($query) {
                return $query->where(function($q) {
                    $q->where('name', 'like', '%'.$this->searchTerm.'%')
                        ->orWhere('email', 'like', '%'.$this->searchTerm.'%')
                        ->orWhere('company', 'like', '%'.$this->searchTerm.'%');
                });
            })
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        return view('livewire.partner', compact('partners'));
    }

    public function create()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'company' => $this->company,
            'password' => Hash::make('password'), // Стандартный пароль
        ]);

        $user->assignRole('partner');

        $this->resetForm();
        session()->flash('success', 'Партнер успешно создан');
    }

    public function edit($id)
    {
        $partner = User::role('partner')->findOrFail($id);
        $this->partnerId = $id;
        $this->name = $partner->name;
        $this->email = $partner->email;
        $this->company = $partner->company;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$this->partnerId,
            'company' => 'required|string|max:255',
        ]);

        $partner = User::role('partner')->findOrFail($this->partnerId);
        $partner->update([
            'name' => $this->name,
            'email' => $this->email,
            'company' => $this->company,
        ]);

        $this->resetForm();
        session()->flash('success', 'Партнер успешно обновлен');
    }

    public function delete($id)
    {
        User::role('partner')->find($id)->delete();
        session()->flash('success', 'Партнер перемещен в архив');
    }

    public function restore($id)
    {
        User::role('partner')->withTrashed()->find($id)->restore();
        session()->flash('success', 'Партнер восстановлен');
    }

    private function resetForm()
    {
        $this->reset([
            'name', 'email', 'company', 'partnerId'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
