<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\StoreImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Store;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class VendorProfileController extends Controller
{
    public function index()
    {
        $store = Store::where('id', Auth::user()->id)->with('store_images')->first();
        return view('vendor.edit-vendor-profile', ['store' => $store]);
    }

    public function updateProfile(Request $request)
    {
        //echo "<pre>"; print_r($request->store_name); 
        // echo "<pre>"; print_r($request->store_mobile); 
        
        // die();
        try {
            if ($request->deletedImageId) {
                $imageIds = explode(',', $request->deletedImageId);
                if (count($imageIds)) {
                    foreach ($imageIds as $imageId) {
                        $storeImage = StoreImage::find($imageId);
                        $url = env('APP_URL');
                        $path = str_replace($url . '/storage', '', $storeImage->store_image);
                        if (Storage::disk('public')->exists($path)) {
                            if (Storage::disk('public')->delete($path)) {
                                $storeImage->delete();
                            }
                        }
                    }
                }
            }
            if ($request->images) {
                foreach ($request->images as $image) {
                    $url = uploadImage($image, 'storeImage');
                    StoreImage::create([
                        'store_id' => $request->store_id,
                        'store_image' => $url
                    ]);
                }

                $storeDataUpdated = Store::where('id', $request->store_id)
                    ->update([
                        'store_name' => $request->store_name,
                        'store_name_es' => $request->store_name_es,
                        'store_country_code' => $request->store_country_code,
                        'store_mobile' => $request->store_mobile,
                    ]);
                if ($storeDataUpdated) {
                    $msg = $request->language == 'en' ? 'Profile updated successfully !' : 'Profile updated exitosamente !';
                    return redirect()->route('profile')->with(['message' => $msg, 'type' => 'success']);
                } else {
                    $msg = $request->language == 'en' ? 'Something went wrong !' : 'Algo salió mal !';

                    return redirect()->route('profile')->with(['message' => $msg, 'type' => 'failed']);
                }
            } else {
                // echo $request->store_id; die();
                $storeDataUpdated = Store::where('id', $request->store_id)
                    ->update([
                        'store_name' => $request->store_name,
                        // 'store_name_es' => $request->store_name_es,
                        // 'store_country_code' => $request->store_country_code,
                        'store_mobile' => $request->store_mobile,
                    ]);


                if ($storeDataUpdated) {
                    $msg = $request->language == 'en' ? 'Profile updated successfully !' : 'Profile updated exitosamente !';
                    return redirect()->route('profile')->with(['message' => $msg, 'type' => 'success']);
                } else {
                    $msg = $request->language == 'en' ? 'Something went wrong !' : 'Algo salió mal !';

                    return redirect()->route('profile')->with(['message' => $msg, 'type' => 'failed']);
                }
            }
        } catch (Exception $ex) {
            return redirect()->route('profile')->with(['message' => $ex->getMessage(), 'type' => 'failed']);
        }
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required',

        ]);

        try {
            $user = Auth::user();
            if (Hash::check($request->old_password, $user->password)) {
                $updated =  Store::where('id', Auth::user()->id)->update(['password' => bcrypt($request->password)]);
                if ($updated) {
                    $msg = $request->language == 'en' ? 'Profile updated successfully !' : 'Profile updated exitosamente !';
                    return redirect()->route('profile')->with(['message' => $msg, 'type' => 'success']);
                } else {
                    $msg = $request->language == 'en' ? 'Something went wrong !' : 'Algo salió mal !';

                    return redirect()->route('profile')->with(['message' => $msg, 'type' => 'failed']);
                }
            } else {
                $msg = $request->language == 'en' ? 'Wrong old password !' : 'Algo salió mal !';
                return redirect()->route('profile')->with(['message' => $msg, 'type' => 'failed']);
            }
        } catch (Exception $ex) {
            return redirect()->route('profile')->with(['message' => $ex->getMessage(), 'type' => 'failed']);
        }
    }

    public function changeStoreStatus(Request $request)
    {
        $updateStatus = Store::where('id', Auth::user()->id)->update([
            'store_status' => $request->status
        ]);
        if ($updateStatus) {
            $msg = $request->language == 'en' ? 'Status updated successfully !' : 'Status updated exitosamente !';

            return response()->json(['message' => $msg, 'status' => SUCCESS], SUCCESS);
        } else {
            $msg = $request->language == 'en' ? 'Something went wrong !' : 'Algo salió mal !';

            return response()->json(['message' => $msg, 'status' => FAIL], FAIL);
        }
    }

    public function addProfileInfo(Request $request){      
        $user_id = Auth::user()->id; 
        $usr_details = store::find($user_id);
        return view('vendor.edit-vendor-profile-info',['usr_details'=>$usr_details]);
    }
    // Save vendor profile Info

    // public function saveVendorProfileInfo(Request $request){
    //      $user_id = Auth::user()->id; 
    //      $data =  $request->validate([
    //         'gst'        => 'required',
    //         'payment_receiving_mode'  => 'required',
    //         'bank_name'      => 'required',
    //         'ifsc_code'      => 'required',
    //         'account_no'     => 'required',
    //         'upi_id'        => 'required',
    //     ]);

    //      // Check if adhar_image field exists in the request
    //     if ($request->hasFile('adhar_image')) {
    //         $data['adhar_image'] = 'required';
    //     }

    //     // Check if cancelled_cheque_image field exists in the request
    //     if ($request->hasFile('cancelled_cheque_image')) {
    //         $data['cancelled_cheque_image'] = 'required';
    //     }


    //     $updated = store::where('id', $user_id)->update([
    //         'gst' =>  $request->gst,
    //         'payment_receiving_mode' =>  $request->payment_receiving_mode,
    //         'bank_name'  =>  $request->bank_name,
    //         'ifsc_code'  =>  $request->ifsc_code,
    //         'account_no'  =>  $request->account_no,
    //         'upi_id' =>  $request->upi_id

    //     ]);

    //         // If the adhar_image field exists in the request, update the adhar_image field
    //     if ($request->hasFile('adhar_image')) {
    //         $updatedAdharImage = store::where('id', $user_id)->update([
    //             'adhar_image' =>  $request->adhar_image->store('uploads','public')
    //         ]);
    //     }

    //     // If the cancelled_cheque_image field exists in the request, update the cancelled_cheque_image field
    //     if ($request->hasFile('cancelled_cheque_image')) {
    //         $updatedCancelledChequeImage = store::where('id', $user_id)->update([
    //             'cancelled_cheque_image'  =>  $request->cancelled_cheque_image->store('uploads','public')
    //         ]);
    //     }

    //     // If any of the updates were successful
    //     if($updated || $updatedAdharImage || $updatedCancelledChequeImage){
    //         // Redirect back with a success message
    //         return redirect()->route('addProfileInfo');
    //     }
        
    // }
    

    public function saveVendorProfileInfo(Request $request){
        $user_id = Auth::user()->id; 
        $getstore = store::find($user_id)->toArray();
        if(!empty($getstore['adhar_image']) && !empty($getstore['cancelled_cheque_image'])){
            $data =  $request->validate([
                'gst'        => 'required',
                'payment_receiving_mode'  => 'required',
                'bank_name'      => 'required',
                'ifsc_code'      => 'required',
                'account_no'     => 'required',
                'upi_id'        => 'required'
            ]);
        }else{
            $data =  $request->validate([
                'gst'        => 'required',
                'adhar_image' => 'required',
                'payment_receiving_mode'  => 'required',
                'bank_name'      => 'required',
                'ifsc_code'      => 'required',
                'account_no'     => 'required',
                'cancelled_cheque_image' => 'required',
                'upi_id'        => 'required'
            ]);
        }
        $updatedData = [
            'gst' => $request->gst,
            'payment_receiving_mode' => $request->payment_receiving_mode,
            'bank_name' => $request->bank_name,
            'ifsc_code' => $request->ifsc_code,
            'account_no' => $request->account_no,
            'upi_id' => $request->upi_id
        ];
    

        if ($request->hasFile('adhar_image')) {
            $updatedData['adhar_image'] = $request->adhar_image->store('uploads', 'public');
        }

        if ($request->hasFile('cancelled_cheque_image')) {
            $updatedData['cancelled_cheque_image'] = $request->cancelled_cheque_image->store('uploads', 'public');
        }
        $updated = store::where('id', $user_id)->update($updatedData);
    
        if ($updated) {
            return redirect()->route('addProfileInfo');
        }
        
    }
}
