<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index(){
        $user = Auth::user();
        return $user->unreadNotifications;
    }

    public function readNotification(){
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
    }
}
