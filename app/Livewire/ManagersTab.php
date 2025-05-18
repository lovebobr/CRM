<?php

namespace App\Livewire;

use App\Models\Lead;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagersTab extends Component
{
    public $managers;
    public $name, $email, $managerId;
    public $defaultPassword = 'default123';
    public $selectedManager = null;
    public $managerLeads = [];

    public function mount()
    {
        $this->loadManagers();
    }

    public function loadManagers()
    {
        $this->managers = User::whereHas('roles', function ($query) {
            $query->where('name', 'manager');
        })->get();
    }

    public function showManagerLeads($managerId)
    {
        $this->selectedManager = User::find($managerId);
        $this->managerLeads = Lead::where('manager_id', $managerId)
            ->with('status')
            ->orderBy('created_at', 'desc')
            ->get();
    }
    public function render()
    {
        return view('livewire.managers-tab');
    }



    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->managerId = null;
    }

    public function create()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->defaultPassword), // Хешируем дефолтный пароль
        ]);

        $user->assignRole('manager');

        $this->resetForm();
        $this->loadManagers();

        session()->flash('message', 'Менеджер создан с паролем: '.$this->defaultPassword);
    }

    public function edit($id)
    {
        $manager = User::findOrFail($id);

        $this->managerId = $manager->id;
        $this->name = $manager->name;
        $this->email = $manager->email;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$this->managerId,
        ]);

        $manager = User::findOrFail($this->managerId);
        $manager->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->resetForm();
        $this->loadManagers();
    }

    public function delete($id)
    {
        $manager = User::findOrFail($id);
        if ($manager->hasRole('manager')) {
            $manager->removeRole('manager');
            $this->loadManagers();
        }
    }

    public function resetPassword($id)
    {
        $manager = User::findOrFail($id);
        $manager->update([
            'password' => Hash::make($this->defaultPassword)
        ]);

        session()->flash('message', 'Пароль сброшен на: '.$this->defaultPassword);
    }
}
