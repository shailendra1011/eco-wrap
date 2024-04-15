<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreImage;
use Illuminate\Http\Request;
use App\Store;
use Illuminate\Support\Carbon;
use App\Models\Admin\Category;

class VendorController extends Controller
{
    public function index()
    {
        $total_vendor   =   Store::count();
        $categories     =   Category::where('category_status',1)->get();
        return view('admin.vendor_management.vendor_management', ['total_vendor' => $total_vendor,'categories'=>$categories]);
    }

    public function vendorList(Request $request)
    {
        $search = $request->search;
        $users = Store::where(function ($query) use ($search) {
            $query->where('store_name', 'LIKE', '%' . $search . '%');
            $query->orWhere('email', 'LIKE', '%' . $search . '%');
            $query->orWhere('store_mobile', 'LIKE', '%' . $search . '%');
        });
        if ($request->from_date) {
            $users = $users->whereDate('created_at', '>=', Carbon::parse($request->from_date));
        }
        if ($request->to_date) {
            $users = $users->whereDate('created_at', '<=', Carbon::parse($request->to_date));
        }
        if ($request->status != '') {
            $users = $users->where('status', $request->status);
        }
        $vendors = $users->orderBy('id', 'DESC')->paginate(10);
        foreach($vendors as $vendor){ 
            if($vendor->category_id == 1){
                $vendor->category = 'Food';
            }elseif($vendor->category_id == 2){
                $vendor->category = 'Pharmacy';
            }else{
                $vendor->category = 'Products';
            }
            $vendor_image = StoreImage::where('store_id',$vendor->id)->first();
            if($vendor_image){
                $vendor->store_image = $vendor_image->store_image;
            }
        }
       return ['data' => $vendors];

    }

    public function vendorStatus(Request $request)
    { 
        if ($request->status == 0) { 
            Store::where('id', $request->id)->update(['status' => 1]);
        }
        if ($request->status == 1) { 
            Store::where('id', $request->id)->update(['status' => 0]);
        }
        return true;
    }

    public function update(Request $request)
    {
        //  Creating vendor object using vendor model
        $updateStatus   =   Store::where('id',$request->vendor_id)
                                ->update([
                                    "store_name"    =>  trim($request->name),
                                    "store_mobile"  =>  trim($request->mobile),
                                    "email"         =>  trim($request->email),
                                    "store_address" =>  trim($request->address),
                                    "category_id"   =>  $request->category,
                                    "updated_at"    =>  date('Y-m-d H:i:s')
                                ]);

        if ($updateStatus) {
            return true;
        } else {
            return false;
        }
    }
}
