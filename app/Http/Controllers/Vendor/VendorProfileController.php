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
}
