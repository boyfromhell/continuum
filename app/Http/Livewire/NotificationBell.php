<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;

class NotificationBell extends Component
{
    public $unreadNotifications;

    public $notificationsState = false;

    public $notifications;

    public function mount(User $user)
    {
        $this->unreadNotifications = $user->unreadNotifications;

        $this->notificationsState = $user->unreadNotifications->isNotEmpty();
    }

    public function confirm(DatabaseNotification $notification)
    {
        $this->unreadNotifications->filter(function ($unreadNotification) use ($notification) {
            return $unreadNotification->data['link'] == $notification->data['link'];
        })->each(function ($unreadNotification) {
            return $unreadNotification->markAsRead();
        });

        return redirect($notification->data['link']);
    }

    public function render()
    {
        $this->notifications = tap($this->unreadNotifications->unique(function ($unreadNotification) {
            return $unreadNotification->data['link'];
        }))->values();

        return view('livewire.notification-bell');
    }
}
