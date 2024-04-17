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
            // 'store_image' => ['required'],
            'store_mobile' => ['required'],
            'store_name' => ['required', 'nullable', 'string', 'max:255'],
            'category' => ['required'],
            'address' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:stores'],
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
            'store_mobile' => $data['store_mobile'],
            'email' => $data['email'],
            'address' => $data['address'],   
            'store_url' => $data['store_url'],
            'category_id' => $data['category'],
            'password' => Hash::make($data['password']),
        ];
        // dd($insertStoreData);
        $storeData = Store::create($insertStoreData);
        // if ($storeData) {
        //     $url = uploadImage($data['store_image'], 'storeImage');

        //     StoreImage::create([
        //         'store_id' => $storeData->id,
        //         'store_image' => $url
        //     ]);

        //     // Store::where('id', $storeData->id)->update(['trial_ends_at' => now()->addDays(30),]);
        // }

        return $storeData;
    }
}
