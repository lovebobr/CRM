<?php
namespace App\Livewire;

use App\Models\Status;
use App\Models\Lead;
use App\Models\User;
use Livewire\Component;
use WithPagination;

class Leads extends Component
{
    public $name, $phone, $email, $status_id;
    public $isEditMode = false;
    public $leadForUpdate=null;
    public $perPage = 10;
    public $orderDirection='asc';
    public $orderBy='name';
    public $searchTerm="";

    protected array $messages = [
        'name.required' => 'Имя обязательное для заполнения',
        'name.string' => 'Имя должно быть строкой.',
        'name.min' => 'Имя должно содержать минимум 3 символа.',
        'name.max' => 'Имя не должно превышать 255 символов.',

        'phone.required' => 'Телефон обязателен для заполнения.',
        'phone.string' => 'Телефон должен быть строкой.',
        'phone.min' => 'Телефон должен содержать минимум 10 символов.',
        'phone.max' => 'Телефон не должен превышать 20 символов.',
        'phone.regex' => 'Телефон должен состоять только из цифр и может начинаться с +.',
        'phone.unique' => 'Такой телефон уже существует.',

        'email.required' => 'Email обязателен для заполнения.',
        'email.email' => 'Введите корректный email.',
        'email.max' => 'Email не должен превышать 255 символов.',
        'email.unique' => 'Такой email уже существует.',

        'status_id.required' => 'Статус обязателен для выбора.',
        'status_id.exists' => 'Выбранный статус не существует.'
    ];

    public function getUpdateLead($id)
    {
        $lead=Lead::query()->find($id);
        $this->name=$lead->name;
        $this->email=$lead->email;
        $this->phone=$lead->phone;
        $this->status_id = $lead->status_id;
        $this->leadForUpdate=$lead;
    }
    public function updateLead(){
        $data=$this->validate();
        $this->leadForUpdate=Lead::query()->update($data);
        $this->name="";
        $this->phone="";
        $this->email="";
        $this->status_id="";
        $this->leadForUpdate=null;
    }
    public function restoreLead($id){
        $lead=Lead::query()->onlyTrashed()->find($id);
        $lead->restore();
    }
    public function storeLead()
    {
        $data = $this->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255|unique:leads,email',
            'phone' => 'required|string|min:10|max:20|regex:/^\+?[0-9]{10,20}$/|unique:leads,phone',
            'status_id' => 'required|exists:statuses,id',
        ]);

        Lead::query()->create($data);
        $this->reset(['name','phone','email','status_id']);
        $this->resetErrorBag();
        $this->resetValidation();
    }
    public function deleteLead($id){
        $lead=Lead::find($id);
        $lead->delete();
    }
    public function render()
    {
        $leads=Lead::query()->withTrashed()->where('name','like','%'.$this->searchTerm.'%')->orderBy($this->orderBy,$this->orderDirection)->paginate(10);

        return view('livewire.lead',[
            'statuses'=>Status::all(),
            'leads'=>Lead :: with('status')->get(),
            'leads' => $leads
        ]);
    }

}
