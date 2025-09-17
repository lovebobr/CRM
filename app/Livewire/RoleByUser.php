<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;

class RoleByUser extends Component
{
    public $roleName = '';
    public $selectedPermissions = [];
    public $availablePermissions = [];
    public $editingRoleId = null;
    public $isEditing = false;

    public function mount()
    {
        $this->availablePermissions = Permission::all()->pluck('name')->toArray();
    }

    public function createRole()
    {
        $this->validate([
            'roleName' => 'required|min:3|unique:roles,name',
            'selectedPermissions' => 'array'
        ]);

        $role = Role::create(['name' => $this->roleName]);
        $role->syncPermissions($this->selectedPermissions);

        $this->resetForm();
        Session::flash('success', 'Роль успешно создана!');
    }

    public function editRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        $this->editingRoleId = $role->id;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->isEditing = true;
    }

    public function updateRole()
    {
        $this->validate([
            'roleName' => 'required|min:3|unique:roles,name,'.$this->editingRoleId,
            'selectedPermissions' => 'array'
        ]);

        $role = Role::findOrFail($this->editingRoleId);
        $role->update(['name' => $this->roleName]);
        $role->syncPermissions($this->selectedPermissions);

        $this->resetForm();
        Session::flash('success', 'Роль успешно обновлена!');
    }

    public function deleteRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->delete();
        Session::flash('success', 'Роль успешно удалена!');
    }

    public function resetForm()
    {
        $this->reset(['roleName', 'selectedPermissions', 'editingRoleId', 'isEditing']);
    }

    public function saveRole()
    {
        $this->isEditing ? $this->updateRole() : $this->createRole();
    }

    public function render()
    {
        return view('livewire.role-by-user', [
            'roles' => Role::with('permissions')->latest()->get()
        ]);
    }
}
