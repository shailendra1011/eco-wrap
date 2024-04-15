<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\UserOtp;
use Carbon\Carbon;
use Exception;
use Laravel\Ui\Presets\React;
use Validator;

class AuthController extends Controller
{
    // function for signup api
    public function signup(Request $request)
    {
        $lang =  ($request->hasHeader('X-localization')) ? $request->header('X-localization') : 'en';

        $data = $request->validate([
            'country_code'   => 'required',
            'mobile'         => 'required|unique:users',
            'email'          => 'required|email|max:64|unique:users|regex:/(.+)@(.+)\.(.+)/i',
            'name'           => 'required',
            'password'       => 'required|min:6|max:10',
            'user_lattitude' => 'required',
            'user_longitude' => 'required',
            'user_address'   => 'required',
            'city'           => 'required',
            'country'        => 'required',
            'device_token'   => 'required'
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['social_media_type'] = $request->social_media_type;

        //  Generating user's referral code
        $data['referral_code']  =   $this->generateReferralCode();

        //  If request has referred by code then
        if ($request->has('referred_by_code') && $request->referred_by_code != null) {
            
            //  Getting referred by user id
            $referredByUser     =   User::where('referral_code',$request->referred_by_code)->first();
            
            //  If wrong referral code has been sent then
            if (is_null($referredByUser) || empty($referredByUser)) {
                
                //  Returning validation error response
                return res_failed('Invalid referral code. Please enter correct referral code.');
            }
            
            //  Adding points to referred by user's account
            $referredByUser->referral_points    +=  REFERRAL_POINTS;

            //  Saving updated data
            $referredByUser->save();

            //  Sending notification to referred by user that his code has been utilized

            //  Assigning referred by user id to new user
            $data['referred_by_user_id']    =   $referredByUser->id;

        }
        
        if ($request->social_media_type == 1) {
            $data['google_id'] = $request->social_id;
        } elseif ($request->social_media_type == 2) {
            $data['apple_id'] = $request->social_id;
        } elseif ($request->social_media_type == 3) {
            $data['facebook_id'] = $request->social_id;
        }
        
        // insert data into the user table
        $user = User::create($data);

        //  If referral code has been used then
        if (isset($referredByUser)) {
            
            //  Inserting data into user referrals table
            \DB::table('user_referrals')->insert([
                'user_id'               =>  $referredByUser->id,
                'referred_to_user_id'   =>  $user->id,
                'earned_points'         =>  REFERRAL_POINTS,
                'created_at'            =>  date('Y-m-d H:i:s'),
                'updated_at'            =>  date('Y-m-d H:i:s')
            ]);
        }

        // create token
        $resultToken =  $user->createToken('');
        $token = $resultToken->token;
        $token->save();
        $user->access_token = $resultToken->accessToken;
        $user->token_type = 'Bearer';
        return res(200, trans('CustomMessages.user.signup_success'), $user);
    }

    private function generateReferralCode()
    {
        start:

        $referralCode   =   \Str::random(6);

        //  Checking if referral code exists or not
        $exists     =   User::where('referral_code',$referralCode)->get();

        if ($exists->count() > 0) {
            goto start;
        }

        return $referralCode;
    }

    //check users email and phone register or not
    public function checkEmailMobile(Request $request)
    {
        // $data = $request->validate([
        //     'country_code'   => 'required',
        //     'mobile'         => 'required|unique:users',
        //     'email'          => 'required|unique:users'
        // ]); return $data;
        $email = User::where('email', $request->email)->first();
        $mobile = User::where('country_code', $request->country_code)->where('mobile', $request->mobile)->first();
        if ($email) {
            return res(400, trans('CustomMessages.user.email is already registered'), (object)[]);
        } elseif ($mobile) {
            return res(400, trans('CustomMessages.user.mobile is already registered'), (object)[]);
        } else {
            return res(200, trans('CustomMessages.user.email and mobile is not registered'), (object)[]);
        }
    }

    // function for login api
    public function login(Request $request)
    {
        $lang =  ($request->hasHeader('X-localization')) ? $request->header('X-localization') : 'en';

        // login_type =>1 for normal login and login_type =>2 for social login 
        if ($request->login_type == 1) {
            $request->validate([
                'login_via'     =>  'required|in:email,mobile',
                'country_code'   => 'nullable|required_if:login_via,mobile|numeric',
                'mobile'         => 'nullable|required_if:login_via,mobile|exists:users',
                'password'       => 'required',
                'device_token'     => 'required'
            ]);

            if ($request->login_via == 'email') {
                
                $user = User::where('email', $request->email)->first();
                
            } else {

                $user = User::where('country_code', $request->country_code)->where('mobile', $request->mobile)->first();
            }

            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    //update user language
                    User::where('mobile', $request->mobile)->update(['user_language' => $request->language, 'device_token' => $request->device_token]);
                    $tokenResult = $user->createToken('');
                    $token = $tokenResult->token;
                    $token->save();
                    $user->token = $tokenResult->accessToken;
                    $user->token_type = 'Bearer';
                    $user->expire_at = Carbon::parse($tokenResult->token->expire_at)->toDateTimeString();
                    return res_success(trans('CustomMessages.user.login_success'), $user);
                } else {
                    // return message in response in english and spanish language
                    $message  = (object) [
                        'en' => 'Password mismatch',
                        'es' => 'Contraseña no coincide'
                    ];
                    return res_failed(trans('CustomMessages.user.password_missmatch'));
                }
            } else {
                // return message in response in english and spanish language
                $message  = (object) [
                    'en' => 'Invalid credential',
                    'es' => 'Credencial inválida'
                ];
                return res_failed(trans('CustomMessages.user.invalid_credential'));
            }
        } else {
            $request->validate([
                'social_media_type'     => 'required',
                'social_id'             => 'required'
            ]);
            if ($request->social_media_type == 1) {
                $user = User::where('google_id', $request->social_id)->first();
            } elseif ($request->social_media_type == 2) {
                $user = User::where('apple_id', $request->social_id)->first();
            } elseif ($request->social_media_type == 3) {
                $user = User::where('facebook_id', $request->social_id)->first();
            }
            if ($user) {
                //update user language
                User::where('email', $request->email)->update(['user_language' => $request->language]);
                // generate token
                $tokenResult = $user->createToken('');
                $token = $tokenResult->token;
                $token->save();
                $user->token = $tokenResult->accessToken;
                $user->token_type = 'Bearer';
                $user->expire_at = Carbon::parse($tokenResult->token->expire_at)->toDateTimeString();

                return res_success(trans('CustomMessages.user.login_success'), $user);
            } else {

                return res(450, trans('CustomMessages.driver.invalid_credential'), (object)[]);
            }
        }
    }



    // function for forgot password api
    public function forgotPassword(Request $request)
    {
        $data = $request->validate([
            'country_code'   => 'required|numeric',
            'mobile'         => 'required|exists:users',
            'password'       => 'required|min:6'
        ]);
        // store password into Hash format
        $data['password'] = Hash::make($data['password']);
        // Upate User password 
        $user = User::where('country_code', $request->country_code)->where('mobile', $data['mobile'])->update(['password' => $data['password']]);
        // return success response
        if ($user) {
            // return message in response in english and spanish language
            $message  = (object) [
                'en' => 'success',
                'es' => 'éxito'
            ];
            return res(200, trans('CustomMessages.user.success'), (object)[]);
        } else {
            // return message in response in english and spanish language
            $message  = (object) [
                'en' => 'User Not Registered',
                'es' => 'Usuario no registrado'
            ];
            return res(400, trans('CustomMessages.user.user_not_registered'), (object)[]);
        }
    }

    public function sendSms(Request $request)
{    
        $recipient_phone_number = $request->country_code . $request->mobile;
        if ($request->type === 'send') {
            $url    =   'https://verificationapi-v1.sinch.com/verification/v1/verifications';
            $content = [
                "identity" => [
                    "type" => "number",
                    "endpoint" => $recipient_phone_number
                ],
                "method" => "sms"
            ];
            $data   =   json_encode($content);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json', 'Authorization: Basic YWFiODkyMTItZmExOS00MTk5LWI2NDUtZWZjOGI0MDAwZTUyOlRGVWZzTGR2RmtldncwN2NHZVdQQ1E9PQ=='));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        } else {
            $url    =   'https://verificationapi-v1.sinch.com/verification/v1/verifications/number/' . $recipient_phone_number;
            $content = [
                "method" => "sms",
                "sms" => [
                    "code" => $request->otp
                ]
            ];
            $data   =   json_encode($content);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json', 'Authorization: Basic YWFiODkyMTItZmExOS00MTk5LWI2NDUtZWZjOGI0MDAwZTUyOlRGVWZzTGR2RmtldncwN2NHZVdQQ1E9PQ=='));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $result = json_decode(curl_exec($ch));
        //  If cURL is not able to send request and has an error then
        if (curl_error($ch)) {
            echo 'Curl error: ' . curl_error($ch);
            curl_close($ch);
            return response()->json([
                'status' => 410,
                'message' => 'Curl failed!',
                'results' => (object)[]
            ]);
        } else {
            //  Closing cURL
            curl_close($ch);

            //  Checking for cURL result 
            if (isset($result->errorCode)) {
                return response()->json([
                    'status' => 418,
                    'message' => 'Invalid OTP!',
                    'results' => (object)[]
                ]);
            }
            if ($request->type === 'verify') {
                return res_success('otp verified');
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'otp sent successfully!',
                    'results' => (object)[]
                ]);
            }
        }
    }


    public function deleteAccount()
    {
        try {

            //  Deleting user account
            User::destroy(Auth::user()->id);

            return res_success(trans('CustomMessages.user.user_deleted'));

        } catch (\Throwable $th) {
            //  Returning validation error response
            return res_failed(trans('CustomMessages.user.user_delete_failed'));
        }


    }
}
