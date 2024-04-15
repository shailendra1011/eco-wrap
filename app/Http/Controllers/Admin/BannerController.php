<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StoreBanner::where('status', 1);

            if ($request->from_date) {
                $data = $data->whereDate('created_at', '>=', Carbon::parse($request->from_date));
            }
            if ($request->to_date) {
                $data = $data->whereDate('created_at', '<=', Carbon::parse($request->to_date));
            }

            $storeBanners = $data->paginate(12);

            foreach ($storeBanners as $k => $item) {
                $storeBanners[$k]->store_banner     =   url($item->store_banner);
                $storeBanners[$k]->meta_title       =   $item->meta_title;
                $storeBanners[$k]->meta_description =   $item->meta_description;
                $storeBanners[$k]->created_on       =   $item->created_at->format('d M Y');
            }

            $count = StoreBanner::count();
            return response()->json(['data' => $storeBanners, 'count' => $count], 200);
        }
        return view('admin.banner.banner');
    }

    public function add(Request $request)
    {

        $data = $request->validate([
            // 'name'  =>  'required|string',
            'store_banner'      =>  'required|image|mimes:jpeg,jpg,png|max:2048',
            'meta_title'        =>  'required',
            'meta_description'  =>  'required'
        ]);

        if ($request->hasFile('store_banner')) {

            $url = uploadImage($data['store_banner'], 'banner/' . Auth::user()->id);
            $res = StoreBanner::create([
                'store_id'          => Auth::user()->id,
                'store_banner'      => $url,
                'meta_title'        =>  trim($request->meta_title),
                'meta_description'  =>  trim($request->meta_description)
            ]);
            if ($res) {
                Session::flash('success', 'Banner added successfully.');
                return back();
            }
        }
        Session::flash('failed', 'Opps! Something went wrong.');
        return back();
    }

    public function updateDelete(Request $request)
    {
        if ($request->type == 2) {
            $res = StoreBanner::destroy($request->id);

            if ($res) {
                return res_success('Success');
            }
        } else {
            $status = $request->status == 1 ? 0 : 1;
            StoreBanner::where('id', $request->id)->update([
                'status'            =>  $status
            ]);
            return true;
        }
    }
}
