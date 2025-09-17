<?php
namespace App\Livewire;

use App\Models\Status;
use App\Models\Lead;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Leads extends Component
{
    use WithPagination;

    public $phone, $status_id, $description, $manager_id, $source;
    public $isEditMode = false;
    public $leadForUpdate = null;
    public $perPage = 10;
    public $orderDirection = 'asc';
    public $orderBy = 'created_at';
    public $searchTerm = "";
    public $incomingLeads = [];
    public $showIncomingLeads = false;
    protected array $messages = [
        'phone.required' => 'Телефон обязателен для заполнения.',
        'phone.string' => 'Телефон должен быть строкой.',
        'phone.min' => 'Телефон должен содержать минимум 10 символов.',
        'phone.max' => 'Телефон не должен превышать 20 символов.',
        'phone.regex' => 'Телефон должен состоять только из цифр и может начинаться с +.',
        'phone.unique' => 'Такой телефон уже существует.',

        'status_id.required' => 'Статус обязателен для выбора.',
        'status_id.exists' => 'Выбранный статус не существует.',

        'description.string' => 'Описание должно быть строкой.',
        'description.max' => 'Описание не должно превышать 1000 символов.',

        'manager_id.exists' => 'Выбранный менеджер не существует.',
    ];

    public function rules()
    {
        return [
            'phone' => 'required|string|min:10|max:20|regex:/^\+?[0-9]{10,20}$/|unique:leads,phone' . ($this->leadForUpdate ? ',' . $this->leadForUpdate->id : ''),
            'status_id' => 'required|exists:statuses,id',
            'description' => 'nullable|string|max:1000',
            'manager_id' => 'nullable|exists:users,id',
            'source' => 'nullable|string|max:255',
        ];
    }

    public function getUpdateLead($id)
    {
        $lead = Lead::with(['manager'])->find($id);
        $this->phone = $lead->phone;
        $this->status_id = $lead->status_id;
        $this->description = $lead->description;
        $this->manager_id = $lead->manager_id;
        $this->leadForUpdate = $lead;
        $this->source = $lead->source;
    }

    public function updateLead()
    {
        $data = $this->validate();
        $this->leadForUpdate->update($data);
        $this->resetForm();
    }

    public function restoreLead($id)
    {
        $lead = Lead::onlyTrashed()->find($id);
        $lead->restore();
    }

    public function storeLead()
    {
        $data = $this->validate();

        if (!isset($data['status_id'])) {
            $data['status_id'] = Status::getDefaultStatus()->id;
        }

        Lead::create($data);
        $this->resetForm();
    }

    public function deleteLead($id)
    {
        $lead=Lead::query()->find($id);
        $lead->delete();

    }

    public function assignToMe($leadId)
    {
        $lead = Lead::find($leadId);
        $lead->update(['manager_id' => auth()->id()]);
    }

    private function resetForm()
    {
        $this->reset([
            'phone', 'status_id', 'description', 'manager_id', 'source',
            'leadForUpdate', 'isEditMode'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        $this->loadIncomingLeads();
    }

    public function loadIncomingLeads()
    {
        // Получаем последние 10 заявок, созданных через API
        $this->incomingLeads = Lead::whereNotNull('source')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function toggleIncomingLeads()
    {
        $this->showIncomingLeads = !$this->showIncomingLeads;
        if ($this->showIncomingLeads) {
            $this->loadIncomingLeads();
        }
    }
    public function render()
    {
        $leads = Lead::with(['status', 'manager'])
            ->withTrashed()
            ->when($this->searchTerm, function($query) {
                $query->where('phone', 'like', '%'.$this->searchTerm.'%')
                    ->orWhere('description', 'like', '%'.$this->searchTerm.'%')
                    ->orWhere('source', 'like', '%'.$this->searchTerm.'%');
            })
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        $managers = User::whereHas('roles', function($query) {
            $query->where('name', 'manager');
        })->get();

        return view('livewire.lead', [
            'statuses' => Status::all(),
            'leads' => $leads,
            'managers' => $managers,
        ]);
    }
}

