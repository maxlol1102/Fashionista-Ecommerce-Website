<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Get page and size from the request, with defaults if not provided
        $page = $request->query('page', 1);
        $size = $request->query('size', 12);

        // Get the order parameter
        $order = $request->query('order', -1);

        // Determine order column and direction
        switch ($order) {
            case 1:
                $o_column = 'created_at';
                $o_order = 'DESC';
                break;
            case 2:
                $o_column = 'created_at';
                $o_order = 'ASC';
                break;
            case 3:
                $o_column = 'regular_price';
                $o_order = 'ASC';
                break;
            case 4:
                $o_column = 'regular_price';
                $o_order = 'DESC';
                break;
            default:
                $o_column = 'id';
                $o_order = 'DESC';
                break;
        }

        // Fetch products with pagination and ordering
        // $products = Product::orderBy($o_column, $o_order)->paginate($size);
        $brands = Brand::orderBy('name','ASC')->get(); 
        $q_brands = $request->query("brands");
        $categories = Category::orderBy("name","ASC")->get();
        $q_categories = $request->query("categories",""); 
        $products = Product::where(function($query) use($q_brands){
                                $query->whereIn('brand_id',explode(',',$q_brands))->orWhereRaw("'".$q_brands."'=''");
                            })  
                            ->where(function($query) use($q_categories){
                                $query->whereIn('category_id',explode(',',$q_categories))->orWhereRaw("'".$q_categories."'=''");
                            })  
                    ->orderBy('created_at','DESC')->orderBy($o_column,$o_order)->paginate($size);

         
        // Return view witproducts and pagination settings
      
        return view('shop', [
            'products' => $products,
            'page' => $page,
            'size' => $size,
            'order' => $order,
            'brands'=> $brands,
            'q_brands'=>$q_brands,
            'categories'=>$categories,
            'q_categories'=>$q_categories
        ]);
    }


    public function productDetails($slug)
    {
        $product = Product::where('slug',$slug)->first();    
        $rproducts = Product::where('slug','!=',$slug)->inRandomOrder('id')->get()->take(8);
        return view('details',['product'=>$product,'rproducts'=>$rproducts]);
    }
}
