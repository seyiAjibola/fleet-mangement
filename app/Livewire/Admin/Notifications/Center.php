<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Component;

class Center extends Component
{
    public bool $expanded = false;

    public function markAsRead(string $notificationId): void
    {
        $notification = auth()->user()
            ->notifications()
            ->whereKey($notificationId)
            ->firstOrFail();

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $user = auth()->user();

        return view('livewire.admin.notifications.center', [
            'unreadCount' => $user->unreadNotifications()->count(),
            'recentNotifications' => $user->notifications()->latest()->limit($this->expanded ? 30 : 6)->get(),
        ]);
    }
}
