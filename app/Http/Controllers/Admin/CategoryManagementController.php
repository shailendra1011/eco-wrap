<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Models\Subcategory;

class CategoryManagementController extends Controller
{
    public function index()
    {
        $total_category = Category::count();
        return view('admin.category.category', ['total_category' => $total_category]);
    }
    public function categoryList(Request $request)
    {
        $search = $request->search;
        $category_list = Category::where(function ($query) use ($search) {
            $query->where('category_name', 'LIKE', '%' . $search . '%');
        });
        $category_list = $category_list->orderBy('id', 'DESC')->paginate(10);
        foreach ($category_list as $list) {
            $list->category_image = url('storage/' . $list->category_image);
        }
        return ['data' => $category_list];
    }


    public function subCategory(Request $request)
    {  
        $total_category = Subcategory::where('category_id',$request->id)->get()->count();
        $category_id=$request->id;
        return view('admin.category.subcategory', ['total_category' => $total_category,'category_id'=>$category_id]);
    }

    public function subCategoryList(Request $request)
    {
        $search = $request->search;
        $category_list = Subcategory::when($search != '',function ($query) use ($search) {
            $query->where('subcategory_name', 'LIKE', '%' . $search . '%');
            $query->where('subcategory_name_pt', 'LIKE', '%' . $search . '%');
            $query->where('subcategory_name_es', 'LIKE', '%' . $search . '%');
        });
        $category_list = $category_list->orderBy('id', 'DESC')->where('category_id',$request->category_id)->paginate(10);
        return ['data' => $category_list];
    }

    public function addCategory(Request $request)
    {  
        $data = $request->validate([
            'category_name'    => 'required',
            'category_name_es' => 'required',
            'category_name_pt' => 'required'
        ]);
        $category = new Category;
        $category->category_name    = $data['category_name'];
        $category->category_name_es = $data['category_name_es'];
        $category->category_name_pt = $data['category_name_pt'];
        $category->category_status  = 1;
        $category->save();
        return true;
    }

    public function addSubCategory(Request $request)
    {  
        $data = $request->validate([
            'subcategory_name'    => 'required',
            'subcategory_name_es' => 'required',
            'subcategory_name_pt' => 'required'
        ]);
        $subcategory = new Subcategory;
        $subcategory->category_id         = $request->category_id;
        $subcategory->subcategory_name    = $data['subcategory_name'];
        $subcategory->subcategory_name_es = $data['subcategory_name_es'];
        $subcategory->subcategory_name_pt = $data['subcategory_name_pt'];
        $subcategory->status              = 1;
        $subcategory->save();
        return true;
    }

    public function subCategoryStatus(Request $request)
    {  
        $status = $request->status;
        Subcategory::where('id',$request->id)->update(['status'=>$status]);
        return true;
    }

    public function destroyCategory($category_id)
    {
        try {
            
        
            //  Checking if category exits for any store or not
            $storeWithCategory  =   \App\Store::where('category_id',$category_id)->get()->count();

            //  If store found with category
            if ($storeWithCategory > 0) {
                
                //  Returning error response
                return response()->json(['status'=>false,'message'=>'Store exists with category. Not able to delete this category.'],400);

            } else {

                //  Deleting sub category with related to category
                Subcategory::where('category_id',$category_id)->delete();

                //  Deleting category
                Category::destroy($category_id);


                //  Returning success response
                return response()->json(['status'=>true,'message'=>'Category deleted successfully.'],200);
            }

        } catch (\Exception $e) {
            
            //  Returning server error response
            return response()->json(['status'=>false,'message'=>$e->getMessage()],500);
        }
    }


    public function destroySubcategory($subcategory_id)
    {
        try {
            
        
            //  Checking if sub category exits for any product or not
            $productWithSubCategory =   \App\Models\Product::where('subcategory_id',$subcategory_id)->get()->count();

            //  If store found with category
            if ($productWithSubCategory > 0) {
                
                //  Returning error response
                return response()->json(['status'=>false,'message'=>'Product exists with subcategory. Not able to delete this subcategory.'],400);

            } else {

                //  Deleting sub category
                Subcategory::destroy($subcategory_id);


                //  Returning success response
                return response()->json(['status'=>true,'message'=>'Subcategory deleted successfully.'],200);
            }

        } catch (\Exception $e) {
            
            //  Returning server error response
            return response()->json(['status'=>false,'message'=>$e->getMessage()],500);
        }
    }

}
