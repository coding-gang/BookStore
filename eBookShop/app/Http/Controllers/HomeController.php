<?php

namespace App\Http\Controllers;

use App\Contracts\HomeContract;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private  $homeContract;
    public function __construct(HomeContract $homeContract)
    {
             $this->homeContract =$homeContract;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products =  $this->homeContract->getAll();
      // dd($products);
        return view('app.overview',compact('products'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param string $name
     * @return \Illuminate\Http\Response
     */
    public function getByCategory(Request $request,$name,$id)
    {

        $products =  $this->homeContract->getAll();
        global $product;
        if ($request->ajax()) {
            global $product;
            if ($request->has('sort')) {
                $key = $request->input('sort');
                $product = $this->homeContract->sortPriceById($name, $id, $key);
            } elseif ($request->has('sortname')) {

                $key = $request->input('sortname');
                $product = $this->homeContract->sortNameById($name, $id, $key);
            } elseif ($request->has('sortFormality')) {

                $key = $request->input('sortFormality');
                $product = $this->homeContract->sortFormalityById($name, $id, $key);

            } else {

                $product = $this->homeContract->getByProductByCategory($name, $id);

            }

            return response()->json($product);
        } else {
            $query = $request->query->all();
            if(array_key_exists('show',$query) && count($query) ===1){

                $product = $this->homeContract->getByCategory($name, $id, $query['show']);
                return view('app.product', compact('product','products'));
            }else if (array_key_exists('show',$query) && count($query)){
                 global $keys;
                if(array_key_exists('sort',$query)){
                             $keys = $query['sort'];
                    }
                    else if(array_key_exists('sortname',$query)){
                    $keys = $query['sortname'];
                }else{
                    $keys = $query['sortFormality'];
                }
                    $product = $this->homeContract->showRecordWithSort($name, $id, $query['show'],$keys);
                return view('app.product', compact('product','products'));
                }
            if ($request->has('sort')) {
                $key = $request->input('sort');

                $product = $this->homeContract->getByCategory($name, $id, $key);
            } elseif ($request->has('sortname')) {
                $key = $request->input('sortname');
                $product = $this->homeContract->getByCategory($name, $id, $key);
            } elseif ('sortFormality') {
                $key = $request->input('sortFormality');

                $product = $this->homeContract->getByCategory($name, $id, $key);
            } else {
                $product = $this->homeContract->getByCategory($name, $id, "");
            }

        }

        return view('app.product', compact('product','products'));
    }
        /**
         * Update the specified resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @param int $id
         * @param string $name
         * @return \Illuminate\Http\Response
         */
        public function getProduct(Request $request ,string $name,$id)
       {
           $products =  $this->homeContract->getAll();
           $productDetail = $this->homeContract->getProductDetail($name,$id);
           $arrAjaxInfoBook =array();

           array_push($arrAjaxInfoBook,$productDetail->getDescribe());
           array_push($arrAjaxInfoBook,$productDetail->getPrice());
           array_push($arrAjaxInfoBook,$productDetail->getTitle());
           array_push($arrAjaxInfoBook,$productDetail->getPublisher());
           array_push($arrAjaxInfoBook,$productDetail->getOriginalPrice());
           array_push($arrAjaxInfoBook,$productDetail->getPercentDiscount());
           array_push($arrAjaxInfoBook,$productDetail->getFormality());
           array_push($arrAjaxInfoBook,$productDetail->getAuthor());
           array_push($arrAjaxInfoBook,$productDetail->getPublisher());
           array_push($arrAjaxInfoBook,$productDetail->getlistImages());

           if ($request->ajax()) {
               return response()->json($arrAjaxInfoBook);
           }

           return view('app.productDetail',compact('products','productDetail'));
       }


}
