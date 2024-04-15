<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\DriverCms;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;

class SettingController extends Controller
{
    public function updateProfile(Request $request)
    {
        if ($request->hasfile('driver_image')) {
            $url = $request['driver_image']->store(
                'driver_image',
                'public'
            );
            //return $url;
            //$data['driver_image'] = $url;

            // $url = uploadImage($request->driver_image, 'driver_image');
            $url = '/storage/' . $url;
            Driver::where('id', Auth::user()->id)->update(['driver_name' => $request->driver_name,  'driver_lattitude' => $request->driver_latitude, 'driver_longitude' => $request->driver_longitude, 'driver_address' => $request->driver_address, 'city' => $request->city, 'driver_image' => $url]);

            $driver = Driver::where('id', Auth::user()->id)->first();
            // return $driver;
            return res(200, trans('CustomMessages.user.signup_success'), $driver);
        } else {
            Driver::where('id', Auth::user()->id)->update(['driver_name' => $request->driver_name, 'driver_lattitude' => $request->driver_latitude, 'driver_longitude' => $request->driver_longitude, 'driver_address' => $request->driver_address, 'city' => $request->city]);
            $driver = Driver::where('id', Auth::user()->id)->first();
            return res(200, trans('CustomMessages.user.signup_success'), $driver);
        }
    }
    public function vehicleDetailUpdate(Request $request)
    { 
        if ($request->hasfile('vehicle_registration')) {
            // $url = uploadImage($request->vehicle_registration, 'vehicle_registration');
            $url = $request['vehicle_registration']->store(
                'vehicle_registration',
                'public'
            );
            $url = '/storage/' . $url;
            Driver::where('id', Auth::user()->id)->update(['vehicle_type' => $request->vehicle_type, 'vehicle_number' => $request->vehicle_number, 'vehicle_registration' => $url, 'document_status' => 1]);
            $driver = Driver::where('id', Auth::user()->id)->first();
            return res(200, trans('CustomMessages.user.signup_success'), $driver);
        } elseif ($request->hasfile('driving_license')) {
            // $url = uploadImage($request->driving_license, 'driving_license');
            $url = $request['driving_license']->store(
                'driving_license',
                'public'
            );
            $url = '/storage/' . $url;
            Driver::where('id', Auth::user()->id)->update(['vehicle_type' => $request->vehicle_type, 'vehicle_number' => $request->vehicle_number, 'driving_license' => $url, 'document_status' => 1]);
            $driver = Driver::where('id', Auth::user()->id)->first();
            return res(200, trans('CustomMessages.user.signup_success'), $driver);
        } else {

            Driver::where('id', Auth::user()->id)->update(['vehicle_type' => $request->vehicle_type, 'vehicle_number' => $request->vehicle_number, 'document_status' => 1]);
            $driver = Driver::where('id', Auth::user()->id)->first();
            return res(200, trans('CustomMessages.user.signup_success'), $driver);
        }
    }

    public function changeLanguage(Request $request)
    {
        $data = $request->validate([
            'language'  =>  'required',
            'country'   =>  'required'
        ]);
        
        Driver::where('id', Auth::user()->id)->update(['driver_language' => $request->language,'country'=>$request->country]);

        return res(200, trans('CustomMessages.user.signup_success'), (object)[]);
    }

    public function cms(Request $request)
    {
        $data = $request->validate([
            'type' => 'required'
        ]);
        $cms = DriverCms::select('cms')->where('type', $request->type)->first();
        return res(200, trans('CustomMessages.user.signup_success'), $cms);
    }
}
