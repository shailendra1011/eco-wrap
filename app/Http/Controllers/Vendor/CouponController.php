<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search;
            //  Getting vnedor's coupon codes
            // \DB::enableQueryLog();
            $data    =   \App\CouponCode::where('store_id',\Auth::user()->id);

            // if (\Session::get('locale') == 'en') {
            //     $data    =   $data->where('coupon_name', '!=', null);
            // }
            // if (\Session::get('locale') == 'es') {
            //     $data    =   $data->where('coupon_name_es', '!=', null);
            // }
            // if (\Session::get('locale') == 'en') {
            //     $data    =   $data->where('coupon_name', 'LIKE', '%' . $search . '%');
            // }
            // if (\Session::get('locale') == 'es') {
            //     $data    =   $data->where('coupon_name_es', 'LIKE', '%' . $search . '%');
            // }

            if ($request->from_date) {
                $data    =   $data->whereDate('created_at', '>=', Carbon::parse($request->from_date));
            }
            if ($request->to_date) {
                $data    =   $data->whereDate('created_at', '<=', Carbon::parse($request->to_date));
            }

            $couponCodes    =   $data->paginate(10);
            // return \DB::getQueryLog();
            // if (\Session::get('locale') == 'es') {
            //     foreach ($couponCodes as $key => $product) {
            //         $product->product_name = $product->product_name_es;
            //         $product->description = $product->description_es;
            //         $product->manufacturer_name = $product->manufacturer_name_es ?? '';
            //         $product->ingredient = $product->ingredient_es ?? '';
            //         $product->direction_to_use = $product->direction_to_use_es ?? '';
            //         $product->other_info = $product->other_info_es ?? '';
            //     }
            // }

            $count = \App\CouponCode::where('store_id', \Auth::user()->id)->count();

            return response()->json(['data' => $couponCodes, 'count' => $count], 200);
        }

        //  Returning view 
        return view('vendor.coupon_code.coupon-codes');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //  Validating request parameters
        $validation     =   \Validator::make($request->all(),[
            'coupon_code'       =>  'required',
            'discount_type'     =>  'required|in:flat,percentage',
            'min_cart_value'    =>  'required|numeric',
            'discount_value'    =>  'required|numeric',
            'maximum_discount'  =>  'required_if:discount_type,percentage|nullable|numeric',
            'start_date'        =>  'required|date',
            'end_date'          =>  'required|date|gte:start_date',
            'description'       =>  'required'
        ]);

        //  If validation fails then
        if ($validation->fails()) {
            return back()->withErrors($validation->errors())->withInput();
        }

        try {

            //  Adding required values to request to insert data
            $request->merge(['store_id'=>\Auth::user()->id,'coupon_type'=>'individual','created_at'=>date('Y-m-d'),'updated_at'=>date('Y-m-d')]);

            //  Inserting data into table
            \DB::table('coupon_codes')->insert($request->except(['_token','language']));

            //  Returning back with success message
            return back()->with('success','Coupon has been created successfully.');

        } catch (\Exception $e) {
            
            return $e->getMessage();
            //  Returning back with error message
            return back()->with('error',$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $update = \App\CouponCode::where('id', $request->coupon_id)->update(['is_active' => $request->status,'updated_at'=>date('Y-m-d H:i:s')]);

            if ($update)
                return response()->json(['message' => 'Product status changed successfully !', 'status' => SUCCESS], SUCCESS);

        } catch (\Exception $e) {
            
            return response()->json(['message' => $e->getMessage(), 'status' => FAIL], FAIL);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
