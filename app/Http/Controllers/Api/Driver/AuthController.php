<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    // function for driver signup
    public function driverSignup(Request $request)
    {
        $lang =  ($request->hasHeader('X-localization')) ? $request->header('X-localization') : 'en';

        $data = $request->validate([
            'driver_country_code'   =>  'required',
            'driver_mobile'         =>  'required|unique:drivers',
            'driver_email'          =>  'required|email|max:64|unique:drivers|regex:/(.+)@(.+)\.(.+)/i',
            'driver_name'           =>  'required',
            // 'driver_name_es'     =>  'required_if:language,==,es|max:20',
            'driver_password'       =>  'required|min:6|max:10',
            'driver_lattitude'      =>  'required',
            'driver_longitude'      =>  'required',
            "driver_address"        =>  'required',
            'city'                  =>  'required',
            'country'               =>  'required',
            'device_token'          =>  'nullable'
           
        ]);
        $data['driver_password'] = Hash::make($data['driver_password']);
        // add driver image

        if ($request->hasfile('driver_image')) {
            // $url = uploadImage($request->driver_image, 'driver_image');
            // $data['driver_image'] = $url;

            if ($request->hasfile('driver_image')) {
                $url = $request['driver_image']->store(
                    'driver_image',
                    'public'
                );
                $data['driver_image'] = '/storage/' . $url;
            }
        }
        // add social media
        $data['social_media_type'] = $request->social_media_type;
        if ($request->social_media_type == 1) {
            $data['google_id'] = $request->social_id;
        } elseif ($request->social_media_type == 2) {
            $data['apple_id'] = $request->social_id;
        } elseif ($request->social_media_type == 3) {
            $data['facebook_id'] = $request->social_id;
        }

        // insert data into the user table
        $save = Driver::create($data);
        $driver = Driver::where('id', $save->id)->first();

        // create token
        $resultToken =  $driver->createToken('');
        $token = $resultToken->token;
        $token->save();
        $driver->access_token = $resultToken->accessToken;
        $driver->token_type = 'Bearer';
        // return message in response in english and spanish language

        return res(200, trans('CustomMessages.driver.signup_success'), $driver);
    }

    //check drivers email and phone register or not
    public function checkEmailMobile(Request $request)
    {
        //  $data = $request->validate([
        //      'country_code'   => 'required',
        //      'mobile'         => 'required|unique:users',
        //      'email'          => 'required|unique:users'
        //  ]);
        $email = Driver::where('driver_email', $request->email)->first();
        $mobile = Driver::where('driver_country_code', $request->country_code)->where('driver_mobile', $request->mobile)->first();
        if ($email) {
            return res(400, trans('CustomMessages.user.email is already registered'), (object)[]);
        } elseif ($mobile) {
            return res(400, trans('CustomMessages.user.mobile is already registered'), (object)[]);
        } else {
            return res(200, trans('CustomMessages.user.email and mobile is not registered'), (object)[]);
        }
    }


    public function vehicleDetails(Request $request)
    {
        $data = $request->validate([
            'driver_country_code'   => 'required',
            'driver_mobile'         => 'required',
            'vehicle_type'          => 'required',
            'vehicle_number'        => 'required|string',
            'vehicle_registration'  => 'required',
            'driving_license'       => 'required'
        ]);
        // add vehicle_registration file
        if ($request->hasfile('vehicle_registration')) {
            $url = $request['vehicle_registration']->store(
                'vehicle_registration',
                'public'
            );
            $url = '/storage/' . $url;
            $data['vehicle_registration'] = $url;
        }
        // add driving license
        if ($request->hasfile('driving_license')) {
            $url = $request['driving_license']->store(
                'driving_license',
                'public'
            );
            $url = '/storage/' . $url;
            $data['driving_license'] = $url;
        }
        $data['document_status'] = 0;
        //return $request;
        // insert data into the driver table

        $driver = Driver::where('driver_country_code', $request->driver_country_code)->where('driver_mobile', $request->driver_mobile)->update(['vehicle_type' => $data['vehicle_type'], 'vehicle_number' => $data['vehicle_number'], 'vehicle_registration' => $data['vehicle_registration'], 'driving_license' => $data['driving_license'], 'document_status' => 1]);
        $driver = Driver::where('driver_mobile', $request->driver_mobile)->first();
        if ($driver) {
            return res(200, trans('CustomMessages.driver.doument_add_successfuly'), $driver);
        }
    }

    public function driverlogin(Request $request)
    {
        $lang =  ($request->hasHeader('X-localization')) ? $request->header('X-localization') : 'en';

        // login_type =>1 for normal login and login_type =>2 for social login 
        if ($request->login_type == 1) {
            $request->validate([
                'driver_country_code'    => 'required|numeric',
                'driver_mobile'          => 'required|exists:drivers',
                'password'               => 'required',
               
            ]);
            $driver = Driver::where('driver_country_code', $request->driver_country_code)->where('driver_mobile', $request->driver_mobile)->first();
            if ($driver) {
                if (Hash::check($request->password, $driver->driver_password)) {
                    if ($driver->document_status == 0) {
                        return res(401, trans('CustomMessages.driver.not_applied'), $driver);
                    } elseif ($driver->document_status == 1) {
                        return res(402, trans('CustomMessages.driver.verification_under_process'), $driver);
                    } elseif ($driver->document_status == 3) {

                        return res(403, trans('CustomMessages.driver.admin_reject_doc'), $driver);
                    } else {
                        //update user language
                        Driver::where('driver_mobile', $request->driver_mobile)->update(['driver_language' => $request->language,'device_token'=>$request->device_token]);
                        $tokenResult = $driver->createToken('');
                        $token = $tokenResult->token;
                        $token->save();
                        $driver->token = $tokenResult->accessToken;
                        $driver->token_type = 'Bearer';
                        $driver->expire_at = Carbon::parse($tokenResult->token->expire_at)->toDateTimeString();

                        return res(200, trans('CustomMessages.driver.login_success'), $driver);
                    }
                } else {

                    return res(413, trans('CustomMessages.driver.password_missmatch'), (object)[]);
                }
            } else {

                return res(400, trans('CustomMessages.driver.invalid_credential'), (object)[]);
            }
        } else {
            $request->validate([
                'social_media_type'     => 'required',
                'social_id'             => 'required'
            ]);
            if ($request->social_media_type == 1) {
                $driver = Driver::where('google_id', $request->social_id)->first();
            } elseif ($request->social_media_type == 2) {
                $driver = Driver::where('apple_id', $request->social_id)->first();
            } elseif ($request->social_media_type == 3) {
                $driver = Driver::where('facebook_id', $request->social_id)->first();
            }
            if ($driver) {
                //update user language
                if ($request->driver_language != null) {
                    Driver::where('driver_email', $request->driver_email)->update(['driver_language' => $request->driver_language]);
                }

                if ($request->has('country') && $request->country != null) {
                    $driver->country    =   trim($request->country);
                }

                if ($request->has('device_token') && $request->device_token != null) {
                    $driver->device_token    =   $request->device_token;
                }

                $driver->save();

                if ($driver->document_status == 0) {
                    return res(401, trans('CustomMessages.driver.not_applied'), $driver);
                } elseif ($driver->document_status == 1) {
                    return res(402, trans('CustomMessages.driver.verification_under_process'), $driver);
                } elseif ($driver->document_status == 3) {

                    return res(403, trans('CustomMessages.driver.admin_reject_doc'), $driver);
                }
                // generate token
                else {
                    $tokenResult = $driver->createToken('');
                    $token = $tokenResult->token;
                    $token->save();
                    $driver->token = $tokenResult->accessToken;
                    $driver->token_type = 'Bearer';
                    $driver->expire_at = Carbon::parse($tokenResult->token->expire_at)->toDateTimeString();

                    return res(200, trans('CustomMessages.driver.login_success'), $driver);
                }
            } else {

                return res(450, trans('CustomMessages.driver.invalid_credential'), (object)[]);
            }
        }
    }

    // function for forgot password api
    public function forgotPassword(Request $request)
    {
        $data = $request->validate([
            'driver_country_code'   => 'required|numeric',
            'driver_mobile'         => 'required|exists:drivers',
            'password'              => 'required|min:6'
        ]);
        // store password into Hash format
        $data['password'] = Hash::make($data['password']);
        // Upate User password 
        $driver = Driver::where('driver_country_code', $data['driver_country_code'])->where('driver_mobile', $data['driver_mobile'])->update(['driver_password' => $data['password']]);
        if ($driver) {
            $message  = (object) [
                'en' => 'success',
                'es' => 'éxito'
            ];
            return res(200, trans('CustomMessages.driver.success'), (object)[]);
        } else {
            $message  = (object) [
                'en' => 'Driver Not Registered',
                'es' => 'Conductora no registrada'
            ];
            return res(400, trans('CustomMessages.driver.driver_not_registered'), (object)[]);
        }
    }
}
// $recipient_phone_number = $request->country_code . $request->mobile;
// if ($request->type === 'send') {
//     $url    =   'https://verificationapi-v1.sinch.com/verification/v1/verifications';
//     $content = [
//         "identity" => [
//             "type" => "number",
//             "endpoint" => $recipient_phone_number
//         ],
//         "method" => "sms"
//     ];
//     $data   =   json_encode($content);
//     $ch = curl_init($url);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json', 'Authorization: Basic YWFiODkyMTItZmExOS00MTk5LWI2NDUtZWZjOGI0MDAwZTUyOlRGVWZzTGR2RmtldncwN2NHZVdQQ1E9PQ=='));
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// } else {
//     $url    =   'https://verificationapi-v1.sinch.com/verification/v1/verifications/number/' . $recipient_phone_number;
//     $content = [
//         "method" => "sms",
//         "sms" => [
//             "code" => $request->otp
//         ]
//     ];
//     $data   =   json_encode($content);
//     $ch = curl_init($url);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json', 'Authorization: Basic YWFiODkyMTItZmExOS00MTk5LWI2NDUtZWZjOGI0MDAwZTUyOlRGVWZzTGR2RmtldncwN2NHZVdQQ1E9PQ=='));
//     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// }

// $result = json_decode(curl_exec($ch));
// //  If cURL is not able to send request and has an error then
// if (curl_error($ch)) {
//     echo 'Curl error: ' . curl_error($ch);
//     curl_close($ch);
//     return response()->json([
//         'status_code' => 410,
//         'message' => 'Curl failed!',
//         'results' => (object)[]
//     ]);
// } else {
//     //  Closing cURL
//     curl_close($ch);

//     //  Checking for cURL result 
//     if (isset($result->errorCode)) {
//         return response()->json([
//             'status_code' => 418,
//             'message' => 'Invalid OTP!',
//             'results' => (object)[]
//         ]);
//     }
//     if ($request->type === 'verify') {
//         return $this->login($request);
//     } else {
//         return response()->json([
//             'status_code' => 200,
//             'message' => 'otp sent successfully!',
//             'results' => (object)[]
//         ]);
//     }
// }
// }
