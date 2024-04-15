<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StoreImage;
use App\Providers\RouteServiceProvider;
use App\Store;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

       
        return Validator::make($data, [
            'language' => ['string'],
            'store_image' => ['required'],
            // 'store_mobile' => ['required'],
            // 'store_country_code' => ['required', 'regex:/^\+\d{1,3}$/'],
            'store_name' => ['required', 'nullable', 'string', 'max:255'],
            'category' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:stores'],
            // 'current_location' => ['required'],
            // 'country'   => ['required','exists:languages,country'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {


        $insertStoreData = [
            $data['language'] == 'en' ? 'store_name' : 'store_name_es' => $data['store_name'],
            'store_country_code' => $data['store_country_code'],
            'store_mobile' => $data['store_mobile'],
            'email' => $data['email'],
            'store_latitude' => $data['store_latitude'],
            'store_longitude' => $data['store_longitude'],
            'store_address' =>   $data['current_location']??null,
            'country'       => "india",
            'store_url' => $data['store_url'],
            'category_id' => $data['category'],
            'password' => Hash::make($data['password']),
            'store_language' => $data['language'] == 'en' ? 1 : 2,



        ];
        $storeData = Store::create($insertStoreData);
        if ($storeData) {
            $url = uploadImage($data['store_image'], 'storeImage');

            StoreImage::create([
                'store_id' => $storeData->id,
                'store_image' => $url
            ]);

            // Store::where('id', $storeData->id)->update(['trial_ends_at' => now()->addDays(30),]);
        }

        return $storeData;
    }
}
