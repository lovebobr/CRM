<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use WithPagination;
use Spatie\Permission\Models\Role;
class Users extends Component
{
    protected array $rules=[
        'name'=>'required',
        'email'=>'required|email|unique:users,email',
    ];
    protected $messages = [
        'name.required'=>'Имя введено не корректно',
        'email.required'=>'Почта введено не корректно',
        'email.email'=>'Почта не корректно введена',
        'email.unique'=>'Почта должна быть уникальной',



    ];

    public $name, $email;
    public $perPage = 5;
    public $userForUpdate=null;
    public $orderDirection='asc';
    public $orderBy='name';
    public $searchTerm="";
    public $showRoleAssignment = false;
    public $selectedUserRoles = [];
    public $userForRoleAssignment = null;
    public $availableRoles;

    public function mount()
    {
        $this->availableRoles = Role::all();
        // ... остальной mount код ...
    }

    public function assignRoles($userId)
    {
        $this->userForRoleAssignment = User::findOrFail($userId);
        $this->selectedUserRoles = $this->userForRoleAssignment->roles->pluck('name')->toArray(); // Используем name вместо id
        $this->showRoleAssignment = true;
    }
    public function updateUserRoles()
    {
        // Проверяем, что пользователь выбран
        if (!$this->userForRoleAssignment) {
            session()->flash('error', 'Пользователь не выбран!');
            return;
        }

        // Проверяем, что выбраны роли
        if (empty($this->selectedUserRoles)) {
            session()->flash('error', 'Выберите хотя бы одну роль!');
            return;
        }

        // Обновляем роли (syncRoles принимает массив имён, например ['admin', 'manager'])
        $this->userForRoleAssignment->syncRoles($this->selectedUserRoles);

        // Закрываем модальное окно и обновляем данные
        $this->showRoleAssignment = false;
        $this->dispatch('userRolesUpdated');
        session()->flash('success', 'Роли успешно обновлены!');
    }

    public function deleteUser($id){
        $user=User::query()->find($id);
        $user->delete();
    }
    public function restoreUser($id){
        $user=User::query()->onlyTrashed()->find($id);
        $user->restore();
    }
    public function storeUser()
    {
        $data=$this->validate();
        $data['email_verified_at']=now();
        $data['password']='$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $data['remember_token']=Str::random(10);

        User::query()->create($data);
        $this->name="";
        $this->email="";
    }
    public function getUpdateUser($id)
    {
        $user=User::query()->find($id);
        $this->name=$user->name;
        $this->email=$user->email;
        $this->userForUpdate=$user;
    }
    public function updateUser(){
        $data=$this->validate();
        $this->userForUpdate=User::query()->update($data);
        $this->name="";
        $this->email="";
        $this->userForUpdate=null;
    }

    public function render()
    {

//        $userForDelete = User::query()->first();
//        $userForDelete->delete();
        $users=User::query()->withTrashed()->where('name','like','%'.$this->searchTerm.'%')->orderBy($this->orderBy,$this->orderDirection)->paginate($this->perPage);
        $usersCount = User::query()->count();
//        $users = User::all();
        return view('livewire.users',[
            'usersCount' => $usersCount,
            'users' => $users
        ])->layout('layouts.app');
    }

}
