<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function notification_list()
    {
        $notification = Notification::select('id','notification','created_at')->where('user_id', Auth::user()->id)->get();
        return res(200, trans('CustomMessages.user.signup_success'), $notification);

    }
}
