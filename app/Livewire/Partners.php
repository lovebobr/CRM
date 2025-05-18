<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Partner;

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
        'email' => 'required|email|unique:partners,email',
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
        $partners = Partner::withTrashed() // Добавьте withTrashed()
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

        Partner::create([
            'name' => $this->name,
            'email' => $this->email,
            'company' => $this->company,
        ]);

        $this->resetForm();
        session()->flash('success', 'Партнер успешно создан');
    }

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        $this->partnerId = $id;
        $this->name = $partner->name;
        $this->email = $partner->email;
        $this->company = $partner->company;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:partners,email,'.$this->partnerId,
            'company' => 'required|string|max:255',
        ]);

        $partner = Partner::findOrFail($this->partnerId);
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
        Partner::find($id)->delete();
        session()->flash('success', 'Партнер перемещен в архив');
    }

    public function restore($id)
    {
        Partner::withTrashed()->find($id)->restore();
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
