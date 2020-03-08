<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function notifications()
    {
        // 未読リプライを既読にする
        auth()->user()->unreadNotifications->markAsRead();

        return view('users.notifications', [
           'notifications' => auth()->user()->notifications()->paginate(5),
        ]);
    }
}
