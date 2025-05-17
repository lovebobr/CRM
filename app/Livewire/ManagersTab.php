<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
class ManagersTab extends Component
{

    public $managers;
    public $name, $email, $password, $managerId;

    public function mount()
    {
        $this->loadManagers();
    }

    public function render()
    {
        return view('livewire.managers-tab');
    }

    public function loadManagers()
    {
        $this->managers = User::whereHas('roles', function ($query) {
            $query->where('name', 'manager');
        })->get();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->managerId = null;
    }

    public function create()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        $user->assignRole('manager');

        $this->resetForm();
        $this->loadManagers();
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
            'email' => 'required|email|unique:users,email,' . $this->managerId,
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
}
