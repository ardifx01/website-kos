<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_date';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $eventFilter = '';
    public $tableFilter = '';
    public $userFilter = '';
    public $showModal = false;
    public $selectedLog = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField',
        'sortDirection',
        'eventFilter' => ['except' => ''],
        'tableFilter' => ['except' => ''],
        'userFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEventFilter()
    {
        $this->resetPage();
    }

    public function updatingTableFilter()
    {
        $this->resetPage();
    }

    public function updatingUserFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function viewLog($logId)
    {
        $this->selectedLog = ActivityLog::with(['user', 'creator'])->find($logId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedLog = null;
    }

    public function getRecords()
    {
        return ActivityLog::with(['user', 'creator'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('event', 'like', '%' . $this->search . '%')
                      ->orWhere('table_name', 'like', '%' . $this->search . '%')
                      ->orWhere('ip_address', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($userQuery) {
                          $userQuery->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->eventFilter, function ($query) {
                $query->where('event', $this->eventFilter);
            })
            ->when($this->tableFilter, function ($query) {
                $query->where('table_name', $this->tableFilter);
            })
            ->when($this->userFilter, function ($query) {
                $query->where('user_id', $this->userFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        $records = $this->getRecords();
        $users = User::select('id', 'name')->orderBy('name')->get();
        $events = ActivityLog::select('event')->distinct()->orderBy('event')->pluck('event');
        $tables = ActivityLog::select('table_name')->distinct()->orderBy('table_name')->pluck('table_name');

        return view('livewire.activity-log-manager', compact('records', 'users', 'events', 'tables'));
    }
}