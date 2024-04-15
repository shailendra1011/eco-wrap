<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Returning view with coupon codes
        return view('admin.coupon_code.coupon-codes');
    }


    public function couponCodes(Request $request)
    { 
        $search = $request->search;
        $couponCodes    =   \App\CouponCode::where(function ($query) use ($search) {
                                $query->where('coupon_code', 'LIKE', '%' . $search . '%');
                                // $query->orWhere('email', 'LIKE', '%' . $search . '%');
                                // $query->orWhere('mobile', 'LIKE', '%' . $search . '%');
                            });
        if ($request->from_date) {
            $couponCodes    =   $couponCodes->whereDate('start_date', '>=', Carbon::parse($request->from_date));
        }
        if ($request->to_date) {
            $couponCodes    =   $couponCodes->whereDate('end_date', '<=', Carbon::parse($request->to_date));
        }
        if ($request->status != '') {
            $couponCodes    =   $couponCodes->whereDate('end_date', '<=', Carbon::parse($request->to_date));
        }
        $couponCodes    =   $couponCodes->orderBy('id', 'DESC')->paginate(10);

       return ['data' => $couponCodes];

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //  Returning view of coupon code form
        return view('admin.coupon_code.add-coupon-code');
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
            $request->merge(['coupon_type'=>'global','created_at'=>date('Y-m-d'),'updated_at'=>date('Y-m-d')]);

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
        //  Getting uses count of coupon
        $couponUseCount     =   \DB::table('orders')->where('coupon_code_id',base64_decode($id))->get()->count();

        //  If coupon has already used then
        if ($couponUseCount) {
            
            //  Returning back with error message
            return back()->with('error','Coupon already used. Not able to edit code now.');
        }

        //  Getting coupon details
        $couponDetails  =   \DB::table('coupon_codes')->whereId(base64_decode($id))->first();

        //  Returning view with data
        return view('admin.coupon_code.edit-coupon-code',compact(['couponDetails']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
            $request->merge(['coupon_type'=>'global','updated_at'=>date('Y-m-d')]);

            //  Inserting data into table
            \DB::table('coupon_codes')->whereId(decrypt($id))->update($request->except(['_token','language','_method']));

            //  Returning back with success message
            return back()->with('success','Coupon has been updated successfully.');

        } catch (\Exception $e) {
            
            return $e->getMessage();
            //  Returning back with error message
            return back()->with('error',$e->getMessage());
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
        //  Checking if coupon code already used by any user in any order 
        $couponUseCount     =   \DB::table('orders')->where('coupon_code_id',base64_decode($id))->get()->count();

        if ($couponUseCount) {
            
            return res_failed('Coupon is used by user. Not able to delete this coupon.');
        }

        dd('Not Found');
        //  Deleting coupon code
        \App\CouponCode::destroy(base64_decode($id));

        //  Returning success response
        return res_success('Coupon has been deleted successfully.');
    }
}
