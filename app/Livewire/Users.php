<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use WithPagination;
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
    public $perPage = 10;
    public $userForUpdate=null;
    public $orderDirection='asc';
    public $orderBy='name';
    public $searchTerm="";
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
