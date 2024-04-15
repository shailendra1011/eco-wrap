<?php

namespace App\Http\Controllers;

use App\Models\StoreBanner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StoreBanner::where('store_id', Auth::user()->id);

            if ($request->from_date) {
                $data = $data->whereDate('created_at', '>=', Carbon::parse($request->from_date));
            }
            if ($request->to_date) {
                $data = $data->whereDate('created_at', '<=', Carbon::parse($request->to_date));
            }

            $ingredients = $data->paginate(12);

            foreach ($ingredients as $k => $item) {
                $ingredients[$k]->store_banner = url($item->store_banner);
                $ingredients[$k]->created_on = $item->created_at->format('d M Y');
            }

            $count = StoreBanner::count();
            return response()->json(['data' => $ingredients, 'count' => $count], 200);
        }
        return view('vendor.banner.banner');
    }

    public function add(Request $request)
    {

        $data = $request->validate([
            // 'name'  =>  'required|string',
            'store_banner' =>  'required|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        if ($request->hasFile('store_banner')) {

            $url = uploadImage($data['store_banner'], 'banner/' . Auth::user()->id);
            $res = StoreBanner::create([
                'store_id' => Auth::user()->id,
                'store_banner' => $url
            ]);
            if ($res) {
                Session::flash('success', 'Banner added successfully.');
                return back();
            }
        }
        Session::flash('failed', 'Opps! Something went wrong.');
        return back();
    }

    public function delete(Request $request)
    {
        $res = StoreBanner::destroy($request->id);

        if ($res) {
            return res_success('Success');
        }
        return res_failed(('Invalid banner id.'));
    }
}
