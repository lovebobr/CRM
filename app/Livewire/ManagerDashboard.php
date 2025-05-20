<?php


namespace App\Livewire;

use App\Models\Lead;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Partner;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManagerDashboard extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $searchTerm = "";
    public $statusFilter = '';
    public $partnerFilter = '';
    public $orderBy = 'created_at';
    public $orderDirection = 'desc';

    // Для редактирования заявки
    public $editingLeadId = null;
    public $phone = '';
    public $description = '';
    public $status_id = '';
    public $partner_id = '';

    // Для назначения партнера
    public $selectedLeadId;
    public $selectedPartnerId;

    protected $rules = [
        'phone' => 'required|string|max:20',
        'description' => 'required|string',
        'status_id' => 'required|exists:statuses,id',
        'partner_id' => 'nullable|exists:partners,id'
    ];
    public function render()
    {
        $manager = Auth::user();

        $leads = Lead::where('manager_id', $manager->id)
            ->with(['status', 'partner', 'manager'])
            ->when($this->searchTerm, function($query) {
                $query->where(function($q) {
                    $q->where('phone', 'like', '%'.$this->searchTerm.'%')
                        ->orWhere('description', 'like', '%'.$this->searchTerm.'%');
                });
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status_id', $this->statusFilter);
            })
            ->when($this->partnerFilter, function($query) {
                $query->where('partner_id', $this->partnerFilter);
            })
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);

        $statuses = Status::all();
        $partners = Partner::all();

        return view('livewire.manager-dashboard', [
            'manager' => $manager,
            'leads' => $leads,
            'statuses' => $statuses,
            'partners' => $partners,
            'allPartners' => $partners
        ]);
    }
    public function editLead($leadId)
    {
        $lead = Lead::findOrFail($leadId);
        $this->editingLeadId = $leadId;
        $this->phone = $lead->phone;
        $this->description = $lead->description;
        $this->status_id = $lead->status_id;
        $this->partner_id = $lead->partner_id;
    }

    public function updateLead()
    {
        $this->validate();

        $lead = Lead::findOrFail($this->editingLeadId);
        $lead->update([
            'phone' => $this->phone,
            'description' => $this->description,
            'status_id' => $this->status_id,
            'partner_id' => $this->partner_id ?: null
        ]);

        $this->cancelEdit();
        session()->flash('success', 'Заявка успешно обновлена');
    }

    public function cancelEdit()
    {
        $this->reset(['editingLeadId', 'phone', 'description', 'status_id', 'partner_id']);
        $this->resetErrorBag();
    }

    public function selectLeadForAssignment($leadId)
    {
        $this->selectedLeadId = $leadId;
    }

    public function assignToPartner()
    {
        $this->validate([
            'selectedPartnerId' => 'required|exists:partners,id'
        ]);

        $lead = Lead::findOrFail($this->selectedLeadId);
        $lead->update(['partner_id' => $this->selectedPartnerId]);

        $this->reset(['selectedLeadId', 'selectedPartnerId']);
        session()->flash('success', 'Заявка успешно назначена партнеру');
    }
}
