<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    // Show notifications list
    public function notifikasi(){
        $user = Auth::user();
        $notifications = $user->notifications()->orderByDesc('created_at')->get();
        $unreadCount = $user->unreadNotifications->count();

        return view('notifikasi', compact('notifications', 'unreadCount'));
    }

    // Mark a single notification as read and redirect to its target url
    public function markRead($id)
    {
        $user = Auth::user();

        $notification = $user->notifications()->where('id', $id)->first();

        if (! $notification) {
            abort(404);
        }

        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('notifikasi');

        return redirect($url);
    }

    // Mark all unread notifications for current user as read
    public function markAllRead()
    {
        $user = Auth::user();
        foreach ($user->unreadNotifications as $n) {
            $n->markAsRead();
        }

        return redirect()->back()->with('success', 'All notifications marked as read');
    }
}
