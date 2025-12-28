<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use App\Services\NotificationManager;
use Livewire\WithPagination;

class NotificationCentre extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, unread, warning, overdue
    public $selectedNotification = null;
    public $showDetailModal = false;

    public function mount()
    {
        // Trigger notification check when page loads
        $manager = new NotificationManager();
        $manager->checkAndNotify();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
        } else {
            Notification::where('organization_id', $user->organization_id)
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        session()->flash('success', 'All notifications marked as read.');
    }

    public function viewDetails($notificationId)
    {
        $this->selectedNotification = Notification::find($notificationId);
        $this->showDetailModal = true;
        $this->markAsRead($notificationId);
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedNotification = null;
    }

    public function render()
    {
        $user = auth()->user();
        
        $query = Notification::query();

        // Filter by user/organization
        if ($user->role === 'admin') {
            $query->where('user_id', $user->id);
        } else {
            $query->where('organization_id', $user->organization_id);
        }

        // Apply filters
        if ($this->filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($this->filter === 'warning') {
            $query->where('type', 'warning');
        } elseif ($this->filter === 'overdue') {
            $query->where('type', 'overdue');
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get counts for badges
        $unreadCount = Notification::where($user->role === 'admin' ? 'user_id' : 'organization_id', 
                                          $user->role === 'admin' ? $user->id : $user->organization_id)
            ->where('is_read', false)
            ->count();

        $warningCount = Notification::where($user->role === 'admin' ? 'user_id' : 'organization_id', 
                                           $user->role === 'admin' ? $user->id : $user->organization_id)
            ->where('type', 'warning')
            ->where('is_read', false)
            ->count();

        $overdueCount = Notification::where($user->role === 'admin' ? 'user_id' : 'organization_id', 
                                           $user->role === 'admin' ? $user->id : $user->organization_id)
            ->where('type', 'overdue')
            ->where('is_read', false)
            ->count();

        return view('livewire.notification-centre', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'warningCount' => $warningCount,
            'overdueCount' => $overdueCount,
        ]);
    }
}
