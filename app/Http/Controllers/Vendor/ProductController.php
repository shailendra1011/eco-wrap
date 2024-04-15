<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Subcategory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search;
            $data = Product::where('store_id', Auth::user()->id);
            if (Session::get('locale') == 'en') {
                $data = $data->where('product_name', '!=', null);
            }
            if (Session::get('locale') == 'es') {
                $data = $data->where('product_name_es', '!=', null);
            }
            if (Session::get('locale') == 'en') {
                $data = $data->where('product_name', 'LIKE', '%' . $search . '%');
            }
            if (Session::get('locale') == 'es') {
                $data = $data->where('product_name_es', 'LIKE', '%' . $search . '%');
            }

            if ($request->from_date) {
                $data = $data->whereDate('created_at', '>=', Carbon::parse($request->from_date));
            }
            if ($request->to_date) {
                $data = $data->whereDate('created_at', '<=', Carbon::parse($request->to_date));
            }

            $products = $data->with('productImages', 'subCategory')->paginate(10);
            if (Session::get('locale') == 'es') {
                foreach ($products as $key => $product) {
                    $product->product_name = $product->product_name_es;
                    $product->description = $product->description_es;
                    $product->manufacturer_name = $product->manufacturer_name_es ?? '';
                    $product->ingredient = $product->ingredient_es ?? '';
                    $product->direction_to_use = $product->direction_to_use_es ?? '';
                    $product->other_info = $product->other_info_es ?? '';
                }
            }

            $count = Product::where('store_id', Auth::user()->id)->count();
            return response()->json(['data' => $products, 'count' => $count], 200);
        }

        $loginUser = Auth::user();
        // checkin login user category
        if ($loginUser->category_id == 1) {
            return view('vendor.products.food.product');
        }
        if ($loginUser->category_id == 2) {
            return view('vendor.products.pharmacy.product');
        }
        if ($loginUser->category_id == 3) {
            return view('vendor.products.product.product');
        }
    }

    public function showAddForm()
    {
        $loginUser = Auth::user();
        $subCategories = Subcategory::where('category_id', $loginUser->category_id);
        if (Session::get('locale') == 'en') {
            $subCategories = $subCategories->select('id', 'subcategory_name')->get();
        } else {
            $subCategories = $subCategories->select('id', 'subcategory_name_es as subcategory_name')->get();
        }


        // checking login user category
        if ($loginUser->category_id == 1) {
            return view('vendor.products.food.add-product', ['subcategories' => $subCategories]);
        }
        if ($loginUser->category_id == 2) {
            return view('vendor.products.pharmacy.add-product', ['subcategories' => $subCategories]);
        }
        if ($loginUser->category_id == 3) {
            return view('vendor.products.product.add-product', ['subcategories' => $subCategories]);
        }
    }

    public function addProduct(Request $request)
    {
        $loginUserCategory = Auth::user()->category_id;
        if ($loginUserCategory == 1) { // for food
            if ($request->language == 'en') {
                $validData = $request->validate([
                    'product_name' => 'required',
                    'product_name_es' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'discount' => 'nullable',
                    'delivery_charge' => 'required|numeric',
                    'size' => 'nullable',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'description' => 'required',
                    'other_info' => 'required',
                    'other_info_es' => 'nullable',
                    'description_es' => 'nullable',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
            if ($request->language == 'es') {
                $validData = $request->validate([
                    'product_name_es' => 'required',
                    'product_name' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'discount' => 'nullable',
                    'delivery_charge' => 'required|numeric',
                    'size' => 'nullable',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'description' => 'nullable',
                    'description_es' => 'required',
                    'other_info' => 'nullable',
                    'other_info_es' => 'required',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }

            if ($request->language == 'pt') {
                $validData = $request->validate([
                    'product_name_pt' => 'required',
                    'product_name' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'discount' => 'nullable',
                    'delivery_charge' => 'required|numeric',
                    'size' => 'nullable',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'description' => 'nullable',
                    'description_pt' => 'required',
                    'other_info' => 'nullable',
                    'other_info_pt' => 'required',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
        }
        if ($loginUserCategory == 3) { // for product
            if ($request->language == 'en') {
                $validData = $request->validate([
                    'product_name' => 'required',
                    'product_name_es' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'discount' => 'nullable',
                    'delivery_charge' => 'required|numeric',
                    'size' => 'required',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'description' => 'required',
                    'other_info' => 'required',
                    'other_info_es' => 'nullable',
                    'description_es' => 'nullable',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
            if ($request->language == 'es') {
                $validData = $request->validate([
                    'product_name_es' => 'required',
                    'product_name' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'discount' => 'nullable',
                    'delivery_charge' => 'required|numeric',
                    'size' => 'required',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'description' => 'nullable',
                    'description_es' => 'required',
                    'other_info' => 'nullable',
                    'other_info_es' => 'required',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
            if ($request->language == 'pt') {
                $validData = $request->validate([
                    'product_name_pt' => 'required',
                    'product_name' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'discount' => 'nullable',
                    'delivery_charge' => 'required|numeric',
                    'size' => 'required',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'description' => 'nullable',
                    'description_pt' => 'required',
                    'other_info' => 'nullable',
                    'other_info_pt' => 'required',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
        }
        if ($loginUserCategory == 2) { // for parmacy
            if ($request->language == 'en') {
                $validData = $request->validate([
                    'product_name' => 'required',
                    'product_name_es' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'discount' => 'nullable',
                    'delivery_charge' => 'required|numeric',
                    'size' => 'nullable',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'description' => 'required',
                    'manufacturer_name' => 'required',
                    'manufacturer_name_es' => 'nullable',
                    'ingredients' => 'required',
                    'ingredients_es' => 'nullable',
                    'direction_to_use' => 'required',
                    'direction_to_use_es' => 'nullable',
                    'sachet_capsule' => 'nullable',
                    'other_info' => 'required',
                    'other_info_es' => 'nullable',
                    'description_es' => 'nullable',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
            if ($request->language == 'es') {
                $validData = $request->validate([
                    'product_name_es' => 'required',
                    'product_name' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'discount' => 'nullable',
                    'delivery_charge' => 'required|numeric',
                    'size' => 'nullable',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'manufacturer_name' => 'nullable',
                    'manufacturer_name_es' => 'required',
                    'ingredients' => 'nullable',
                    'ingredients_es' => 'required',
                    'direction_to_use' => 'nullable',
                    'direction_to_use_es' => 'required',
                    'sachet_capsule' => 'nullable',
                    'description' => 'nullable',
                    'description_es' => 'required',
                    'other_info' => 'nullable',
                    'other_info_es' => 'required',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
            if ($request->language == 'pt') {
                $validData = $request->validate([
                    'product_name_pt' => 'required',
                    'product_name' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'discount' => 'nullable',
                    'delivery_charge' => 'required|numeric',
                    'size' => 'nullable',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'manufacturer_name' => 'nullable',
                    'manufacturer_name_pt' => 'required',
                    'ingredients' => 'nullable',
                    'ingredients_pt' => 'required',
                    'direction_to_use' => 'nullable',
                    'direction_to_use_pt' => 'required',
                    'sachet_capsule' => 'nullable',
                    'description' => 'nullable',
                    'description_pt' => 'required',
                    'other_info' => 'nullable',
                    'other_info_pt' => 'required',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
        }

        try {
            
            if ($validData) {
                $validData['store_id'] = Auth::user()->id;
                $validData['product_language']  =   $request->language;
                $validData['subcategory_id'] = $request->category;
                $validData['product_status'] = $request->product_status ? 1 : 0;
                $productId = Product::create($validData)->id;
                if ($productId) {
                    foreach ($request->images as $image) {
                        $url = uploadImage($image, 'product/' . $productId);
                        $res = ProductImage::create([
                            'product_id' => $productId,
                            'product_image' => $url
                        ]);
                    }

                    $msg = $request->language == 'en' ? 'Product added successfully !' : 'Producto agregado exitosamente !';
                    return redirect()->route('product')->with(['message' => $msg, 'type' => 'success']);
                } else {
                    $msg = $request->language == 'en' ? 'Something went wrong !' : 'Algo salió mal !';

                    return redirect()->route('product')->with(['message' => $msg, 'type' => 'failed']);
                }
            }
        } catch (Exception $ex) {

            return redirect()->route('product')->with(['message' => $ex->getMessage(), 'type' => 'failed']);
        }
    }


    public function changeProductStatus(Request $request)
    {
        $update = Product::where('id', $request->product_id)->update(['product_status' => $request->status]);
        if ($update)
            return response()->json(['message' => 'Product status changed successfully !', 'status' => SUCCESS], SUCCESS);

        return response()->json(['message' => 'Something went wrong ', 'status' => FAIL], FAIL);
    }

   



    public function showEditProductForm($product_id)
    {

        $msg = '';
        $product = Product::where('id', $product_id);
        if (Session::get('locale') == 'en') {
            $msg = 'Product not available';
            $product->where('product_name', '!=', null);
        }
        if (Session::get('locale') == 'es') {
            $msg = 'Producto no disponible';
            $product->where('product_name_es', '!=', null);
        }
        $product = $product->with('subCategory', 'productImages')->first();

        $loginUser = Auth::user();
        $subCategories = Subcategory::where('category_id', $loginUser->category_id);
        if (Session::get('locale') == 'en') {
            $subCategories = $subCategories->select('id', 'subcategory_name')->get();
        } else {
            $subCategories = $subCategories->select('id', 'subcategory_name_es as subcategory_name')->get();
        }

        if ($product) {
            if ($loginUser->category_id == 1) {  // for food
                return view('vendor.products.food.edit-food', ['product' => $product, 'subcategories' => $subCategories]);
            }
            if ($loginUser->category_id == 2) { // for pharmacy

                return view('vendor.products.pharmacy.edit-pharmacy', ['product' => $product, 'subcategories' => $subCategories]);
            }
            if ($loginUser->category_id == 3) { // for product
                return view('vendor.products.product.edit-product', ['product' => $product, 'subcategories' => $subCategories]);
            }
        }

        return redirect()->route('product')->with(['message' => $msg, 'type' => 'failed']);
    }

    public function editProduct(Request $request)
    {

        $loginUserCategory = Auth::user()->category_id;
        if ($loginUserCategory == 1) { // for food
            if ($request->language == 'en') {
                $validData = $request->validate([
                    'product_name' => 'required',
                    'product_name_es' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'delivery_charge'   =>  'required|numeric',
                    'size' => 'nullable',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'description' => 'required',
                    'other_info' => 'required',
                    'other_info_es' => 'nullable',
                    'description_es' => 'nullable',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
            if ($request->language == 'es') {
                $validData = $request->validate([
                    'product_name_es' => 'required',
                    'product_name' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'delivery_charge'   =>  'required|numeric',
                    'size' => 'nullable',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'description' => 'nullable',
                    'description_es' => 'required',
                    'other_info' => 'nullable',
                    'other_info_es' => 'required',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
        }
        if ($loginUserCategory == 3) { // for product
            if ($request->language == 'en') {
                $validData = $request->validate([
                    'product_name' => 'required',
                    'product_name_es' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'delivery_charge'   =>  'required|numeric',
                    'size' => 'required',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'description' => 'required',
                    'other_info' => 'required',
                    'other_info_es' => 'nullable',
                    'description_es' => 'nullable',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
            if ($request->language == 'es') {
                $validData = $request->validate([
                    'product_name_es' => 'required',
                    'product_name' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'delivery_charge'   =>  'required|numeric',
                    'size' => 'required',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'description' => 'nullable',
                    'description_es' => 'required',
                    'other_info' => 'nullable',
                    'other_info_es' => 'required',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
        }
        if ($loginUserCategory == 2) { // for parmacy
            if ($request->language == 'en') {
                $validData = $request->validate([
                    'product_name' => 'required',
                    'product_name_es' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'delivery_charge'   =>  'required|numeric',
                    'size' => 'nullable',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'description' => 'required',
                    'manufacturer_name' => 'required',
                    'manufacturer_name_es' => 'nullable',
                    'ingredients' => 'required',
                    'ingredients_es' => 'nullable',
                    'direction_to_use' => 'required',
                    'direction_to_use_es' => 'nullable',
                    'sachet_capsule' => 'nullable',
                    'other_info' => 'required',
                    'other_info_es' => 'nullable',
                    'description_es' => 'nullable',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
            if ($request->language == 'es') {
                $validData = $request->validate([
                    'product_name_es' => 'required',
                    'product_name' => 'nullable',
                    'category' => 'required',
                    'price' => 'required|numeric',
                    'delivery_charge'   =>  'required|numeric',
                    'size' => 'nullable',
                    'quantity' => 'required|numeric',
                    'product_status' => 'nullable',
                    'manufacturer_name' => 'nullable',
                    'manufacturer_name_es' => 'required',
                    'ingredients' => 'nullable',
                    'ingredients_es' => 'required',
                    'direction_to_use' => 'nullable',
                    'direction_to_use_es' => 'required',
                    'sachet_capsule' => 'nullable',
                    'description' => 'nullable',
                    'description_es' => 'required',
                    'other_info' => 'nullable',
                    'other_info_es' => 'required',
                    'images[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
            }
        }
        $validData['subcategory_id'] = $request->category;
        $validData['product_status'] = $request->product_status ? 1 : 0;
        unset($validData['category']);
        $isUpdated = Product::where('id', $request->product_id)->update($validData);
        if ($isUpdated) {
            if ($request->deletedImageId) {
                $imageIds = explode(',', $request->deletedImageId);
                if (count($imageIds)) {
                    foreach ($imageIds as $imageId) {
                        $productImage = ProductImage::find($imageId);
                        $url = env('APP_URL');
                        $path = str_replace($url . '/storage', '', $productImage->product_image);
                        if (Storage::disk('public')->exists($path)) {
                            if (Storage::disk('public')->delete($path)) {
                                $productImage->delete();
                            }
                        }
                    }
                }
            }
            if ($request->images) {
                foreach ($request->images as $image) {
                    $url = uploadImage($image, 'product/' . $request->product_id);
                    $res = ProductImage::create([
                        'product_id' => $request->product_id,
                        'product_image' => $url
                    ]);
                }
            }
            return redirect()->route('product')->with(['message' => 'Success', 'type' => 'success']);
        }
        return redirect()->route('product')->with(['message' => 'Failed', 'type' => 'failed']);
    }


    public function destroy($product_id)
    {
        try {
            //  Checking if deleting product exists in user's cart, order or in wishlist
            $productExistense   =   \App\Models\OrderProduct::where('product_id',$product_id)->get()->count();

            //  If deleting product do not exists in order products table then
            if ($productExistense == 0) {
                
                //  Getting product count from user's cart table
                $productExistense   =   \App\Models\UserProductCart::where('product_id',$product_id)->get()->count();

                //  If products not found in user cart then
                if ($productExistense == 0) {
                    
                    //  Getting product count from user's wishlist
                    $productExistense   =   \App\Models\UserWishlist::where('product_id',$product_id)->get()->count();

                    //  If product found then
                    if ($productExistense > 0) {
                        
                        //  Returning response with success code 
                        return response()->json(['status'=>412,'message'=>'Not able to delete product as this product is in orders.']);

                    } else {

                        \DB::beginTransaction();

                        //  Deleting product from table
                        Product::destroy($product_id);

                        //  Deleting product image
                        $productImages  =   ProductImage::where('product_id',$product_id)->get();

                        //  Traversing through each image
                        foreach ($productImages as $productImage) {

                            //  Deleting image from folder
                            unlink($productImage->product_image);

                            //  Deleting product image data
                            $productImage->delete();
                        }

                        \DB::commit();

                        //  Returning success response
                        return response()->json(['status'=>200,'message'=>'Product deleted successfully.']);

                    }

                } else {

                    //  Returning response with success code 
                    return response()->json(['status'=>412,'message'=>'Not able to delete product as this product is in orders.']);

                }

            } else {
                
                //  Returning response with success code 
                return response()->json(['status'=>412,'message'=>'Not able to delete product as this product is in orders.']);

            }
        } catch (\Exception $e) {
            
            \DB::rollback();

            //  Returning response with success code 
            return response()->json(['status'=>500,'message'=>'Not able to delete product as this product is in orders.'],500);

        }
    }
}
