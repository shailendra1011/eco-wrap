<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\UserProductCart;
use App\Models\UserReserveTable;
use App\Payment;
use App\Store;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe;

class OrderController extends Controller
{

    public function __construct()
    {


        // $stripe_secret = "sk_test_51KODQzFGXYFyMMhQw7l6Y39Gr9rjoMOIqLP4NeUCjHFnuGrhtqaw994OxsIGK4sIynQzhwuOyzJKyowjQgHziz0C00uYR6LmE5";
        //  Creating stripe object to perform various tasks as per requirement in methods of class
        $this->stripeObj    =   new Stripe\StripeClient(env('STRIPE_SECRET'));
        // $this->stripeObj    =   new Stripe\StripeClient($stripe_secret);


    }
    public function addOrder(Request $request)
    {
        $data = $request->validate([
            'store_id'               =>  'required',
            'total_price'            =>  'required',
            'delivery_address_id'    =>  'required',
            'payment_mode'           =>  'required',
        ]);
        $data['user_id']                =    Auth::user()->id;
        $data['order_no']               =    $this->generateOrderNumber($request->store_id);
        $data['shipping_charge']        =    $request->shipping_charge;
        $data['order_status']           =    0;
        $data['is_coupon_code_applied'] =   $request->coupon_applied;
        $data['coupon_code_id']         =   $request->counpon_code_id;
        $data['coupon_code_discount']   =   $request->coupon_code_discount;
        $order = Order::create($data);

        foreach ($request->products as $product) {
            $insertArray = [
                'Order_id'           =>   $order->id,
                'product_id'         =>   $product['product_id'],
                'quantity'           =>   $product['quantity'],
                'discount'           =>   $product['discount'],
                'price'              =>   $product['amount']
            ];
            OrderProduct::insert($insertArray);
            UserProductCart::where('user_id', Auth::user()->id)->where('product_id', $product['product_id'])->delete();
        }
        return res(200, trans('CustomMessages.user.signup_success'), (object)[]);
    }

    function generateOrderNumber($store_id)
    {
        $order_no = Order::where('store_id', $store_id)->latest()->first();
        if ($order_no) {
            $last_order_no = $order_no->order_no;
            return $order_no = $last_order_no + 1;
        } else {
            return $order_no = 1001;
        }
    }

    public function reserveTable(Request $request)
    {
        $data = $request->validate([
            'store_id'                 => 'required',
            'reservation_type'         => 'required',
            'date'                     => 'required',
            'booking_person_name'      => 'required',
            'booking_person_name'      => 'required'
        ]);
        $data['user_id']               =  Auth::user()->id;
        $data['time']                  =  $request->time;
        $data['no_of_persons']         =  $request->no_of_persons;
        $data['country_code']          =  $request->country_code;
        $data['booking_person_mobile'] =  $request->booking_person_mobile;
        $data['no_of_persons']         =  $request->no_of_persons;
        $data['description']           =  $request->description;
        UserReserveTable::create($data);
        return res(200, trans('CustomMessages.user.signup_success'), (object)[]);
    }

    public function payStripe(Request $request)
    {



        //  Validating card data
        $request->validate([
            'payment_type'  =>  'required|in:1,2',
            'source_id'     =>  'required_if:payment_type,1',
            'card_no'       =>  'required_if:payment_type,2',
            'expiry'        =>  'required_if:payment_type,2',
            'cvv'           =>  'required_if:payment_type,2',
            'store_id'      =>  'required|exists:stores,id'
        ]);

        try {

            
            //  Exploding expiry date into month and year
            $expiry = explode('/', $request->expiry);

            //  Intiating stripe object


            //  Getting user data from auth user
            $user = User::find(Auth::user()->id);

            //  If stripe customer id does not exists for user then

            if ($user->stripe_id == null) {

                //  Creating stripe customer
                $stripeCustomer =   $this->stripeObj->customers->create([
                    'name'          =>  $user->name,
                    'email'         =>  $user->email,
                    'description'   =>  'Test'
                ]);


                //  Assigning customer id to user
                $user->stripe_id   =   $stripeCustomer->id;

                //  Saving updated data
                $user->save();
            }

            //  If sources id is null i.e. user has entered a new card then
            if ($request->source_id == null) {


                //  Creating card token
               $response = $this->stripeObj->tokens->create([

                    "card" => array(
                        "number"    => $request->input('card_no'),
                        "exp_month" => $expiry[0],
                        "exp_year"  => $expiry[1],
                        "cvc"       => $request->input('cvv')
                    )
                ]);

                //  Saving card using card token and stripe customer data
                $cardData   =   $this->stripeObj->customers->createSource(
                    $user->stripe_id,
                    ['source' => $response['id']]
                );

                //  Creating new card as default source
                $this->stripeObj->customers->update(
                    $user->stripe_id,
                    ['default_source' => $cardData->id]
                );
                //  Assigning source id to source variable
                $source_id  =   $cardData->id;
            } else {
                $source_id  =   $request->source_id;
            }

            
            //  Creating charge through source
            $charge = $this->stripeObj->charges->create([
                'amount'      =>  $request->amount * 100,
                'currency'    =>  'USD',
                'customer'    =>  $user->stripe_id,
                'source'      =>  $source_id,
            ]);
            
            if ($charge['status'] == 'succeeded') {

                $seller = Store::where('id', $request->store_id)->first();
                // Transfer funds to seller
                //  return $seller->stripe_connect_id;

                $transfer = $this->stripeObj->transfers->create([
                    'amount'             => $request->amount * 100,   // $16.00
                    'currency'           => 'USD',
                    'source_transaction' => $charge->id,
                    'destination'        => $seller->stripe_connect_id
                ]);
        

                $data = new Payment;
                $data->user_id = Auth::user()->id;
                $data->store_id = $request->store_id;              
                $data->transaction_details=$charge;
                $data->transfer_details=$transfer;
                $data->status=1;
                $data->save();

                return res_success('success', $data);
                // $transfer = \Stripe\Transfer::create([
                //     'amount' => $request->amount * 100,
                //     'currency' => 'usd',
                //     'destination' => $seller->stripe_connect_id,
                //     'transfer_group' => 'ORDER10',
                // ]);
            } else {
                return "payment failed";
            }
        } catch (Exception $e) {
            // return $e->getMessage();
            return $e->getMessage();
        }
    }

    public function refund(Request $request)
    {
        
        $this->stripeObj->refunds->create([
            // 'charge' => 'ch_3KQy9XFGXYFyMMhQ0w9sfD4j',
            'charge' => $request->charge_id,
        ]);
    }

    public function getSavedCards(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $cards  =   $this->stripeObj->customers->allSources(
            $user->stripe_id
        );
        return res_success('success', $cards);
    }
}
