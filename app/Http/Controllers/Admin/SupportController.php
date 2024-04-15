<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HelpAndSupport;
use App\Notifications\UserQueryReplyNotification;

class SupportController extends Controller
{
    public function index(){

        $total_quries   =   HelpAndSupport::count();
        return view('admin.help_support.queries_list', ['total_quries' => $total_quries]);
    }

    public function queryList(Request $request){
        $search = $request->search;
        $queries = HelpAndSupport::where(function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
            $query->orWhere('mobile', 'LIKE', '%' . $search . '%');
        });
        if ($request->from_date) {
            $queries = $queries->whereDate('created_at', '>=', Carbon::parse($request->from_date));
        }
        if ($request->to_date) {
            $queries = $queries->whereDate('created_at', '<=', Carbon::parse($request->to_date));
        }
        
        $queries = $queries->orderBy('id', 'DESC')->paginate(10);
       return ['data' => $queries];
    }


    public function replyToQuery(Request $request)
    {
        try {

            //  Getting query data object
            $queryData  =   HelpAndSupport::findOrFail($request->query_id);

            //  Updating reply
            $queryData->reply       =   strip_tags(stripslashes(trim($request->query_reply)));
            $queryData->replied_at  =   date('Y-m-d H:i:s');
            $queryData->updated_at  =   date('Y-m-d H:i:s');

            //  Saving updated data
            $queryData->save();

            //  Sending reply email to user as mail notification
            $queryData->user_data->notify(new UserQueryReplyNotification($queryData));
            
            if ($queryData->wasRecentyChanged()) {
                return true;
            } else {
                return false;
            }

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return false;
        }
    }
}
