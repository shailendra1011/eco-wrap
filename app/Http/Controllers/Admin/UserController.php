<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $total_user = User::count();
        return view('admin.user_management.user_management', ['total_users' => $total_user]);
    }

    public function userList(Request $request)
    { 
        $search = $request->search;
        $users = User::where(function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
            $query->orWhere('email', 'LIKE', '%' . $search . '%');
            $query->orWhere('mobile', 'LIKE', '%' . $search . '%');
        });
        if ($request->from_date) {
            $users = $users->whereDate('created_at', '>=', Carbon::parse($request->from_date));
        }
        if ($request->to_date) {
            $users = $users->whereDate('created_at', '<=', Carbon::parse($request->to_date));
        }
        if ($request->status != '') {
            $users = $users->where('user_status', $request->status);
        }
        $users = $users->orderBy('id', 'DESC')->paginate(10);
       return ['data' => $users];

    }

    public function userStatus(Request $request)
    {
        if ($request->status == 0) {
            User::where('id', $request->id)->update(['user_status' => 1]);
        }
        if ($request->status == 1) {
            User::where('id', $request->id)->update(['user_status' => 0]);
        }
        return true;
    }

    public function update(Request $request)
    {
        $updateStatus   =   User::whereId($request->user_id)
                                ->update([
                                    'name'          =>  $request->name,
                                    'mobile'        =>  $request->mobile,
                                    'email'         =>  $request->email,
                                    'user_address'  =>  $request->address,
                                    'updated_at'    =>  date('Y-m-d H:i:s')
                                ]);

        if ($updateStatus) {
            return true;
        } else {
            return false;
        }
        
    }

}
