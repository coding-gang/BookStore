<?php


namespace App\Services;


use App\Contracts\HomeContract;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\viewModels\bookViewModels;
use App\viewModels\productViewModels;
use App\viewModels\showCategoryModel;
use  App\viewModels\bookDetailViewModels;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

class HomeService implements HomeContract
{

    function convert_name($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", '-', $str);
        $str = preg_replace("/( )/", '-', $str);
        return $str;
    }
     function formatNameToSlug($str){

         $strClean = explode(" ", $str);
         $arrSplit = array_diff($strClean, array("-"));

         $arrFormat = array();
         foreach ($arrSplit as $str){
             $strNew = $this->convert_name($str);
             array_push($arrFormat,$strNew);
         }
         $titleSlug =  join("-",$arrFormat);
         return $titleSlug;
     }
         public function setProductByCategory($category,$name ,$booksByCategory){
         global  $parentCategory  ;
         $productViewModels =new productViewModels();
         $productViewModels->setpathName($name);
         $productViewModels->setNameCategory($category->name);
         $productViewModels->setpathId($category->id);

         if($category->slug_name === $name){

             if(count($booksByCategory))
             {

                 foreach ($booksByCategory as $book){
                     $bookViewModels =new bookViewModels();
                     $bookViewModels->setId($book->id);
                     $bookViewModels->setCategory($book->categories->name);
                     $bookViewModels->setIdCategory($book->categories->id);
                     $bookViewModels->setCategorySlug($this->formatNameToSlug($book->categories->name));
                     $bookViewModels->setOriginalPrice($book->original_Price);
                     $bookViewModels->setPercentDiscount($book->percent_discount);
                     $bookViewModels->setTitleSlug($this->formatNameToSlug($book->title));
                     $bookViewModels->setPrice($book->price);
                     $bookViewModels->setImages($book->imagebooks[0]->file);
                     $bookViewModels->setTitle($book->title);
                     $productViewModels->setListBook($bookViewModels);
                 }
             }

             if($category->parent_id !== 0) {
                 $parentCategory = Category::findOrFail($category->parent_id);
             }else{
                 $parentCategory =$category;
             }

         }else{
             $parentCategory =$category;
         }
         $showCategoryModel =new showCategoryModel();
         $showCategoryModel->setId($parentCategory->id);
         $showCategoryModel->setName($parentCategory->name);
         $showCategoryModel->setTitleSlug($this->formatNameToSlug($parentCategory->name));
         if($parentCategory->childs->count() >0){
             foreach ($parentCategory->childs as $child) {
                 $childModel =new showCategoryModel();
                 $childModel->setId($child->id);
                 $childModel->setName($child->name);
                 $childModel->setTitleSlug($this->formatNameToSlug($child->name));
                 $showCategoryModel->setChilds($childModel);
             }
         }
         $productViewModels->setListCategory($showCategoryModel);

         return $productViewModels;
     }
    public function getAll()
    {
         $books =  Book::orderBy('created_at', 'desc')->paginate(5);
         $categorys =  Category::where('parent_id', '=', 0)->get();
        $productViewModels =new productViewModels();
         $productViewModels->setBooks($books);
         foreach ($books as $book){

               $bookViewModels =new bookViewModels();
               $bookViewModels->setId($book->id);
               $bookViewModels->setOriginalPrice($book->original_Price);
               $bookViewModels->setPercentDiscount($book->percent_discount);
               $bookViewModels->setPrice($book->price);
               $bookViewModels->setImages($book->imagebooks[0]->file);
               $bookViewModels->setTitle($book->title);
               $bookViewModels->setAuthor($book->author->full_name);
               $bookViewModels->setCategory($book->categories->name);
             $bookViewModels->setIdCategory($book->categories->id);
             $bookViewModels->setCategorySlug($this->formatNameToSlug($book->categories->name));
             $productViewModels->setListBook($bookViewModels);

         }



        foreach ($categorys as $item){
            $showCategoryModel =new showCategoryModel();
            $showCategoryModel->setId($item->id);
            $showCategoryModel->setName($item->name);
          $showCategoryModel->setTitleSlug($this->formatNameToSlug($item->name));
            if($item->childs->count() >0){
                foreach ($item->childs as $child) {
                    $childModel =new showCategoryModel();
                    $childModel->setId($child->id);
                    $childModel->setName($child->name);
                    $childModel->setTitleSlug($this->formatNameToSlug($child->name));
                    $showCategoryModel->setChilds($childModel);
                }
            }
            $productViewModels->setListCategory($showCategoryModel);
        }
       return $productViewModels ;
    }

    public function getByCategory($name, $id,$key)
    {
        global $productViewModels;
        $category =  Category::findOrFail($id);

        if($key !== null ){

            if($key === 'gia-thap'){
                $arrSort = $category->books->sortBy("price");

            } elseif($key === 'gia-cao'){
                $arrSort = $category->books->sortByDesc("price");

            }
            elseif ($key === 'ten-giam') {
                $arrSort = $category->books->sortByDesc("title");

            }elseif ($key === 'ten-tang'){
                $arrSort = $category->books->sortBy("title");

            }elseif ($key === 'biaMem') {
                $arrSort = $category->books->where('formality','=','Soft Cover');

            }
            else{
                $arrSort = $category->books->where('formality','=','Hard Cover');

            }
            $productViewModels = $this->setProductByCategory($category,$name,$arrSort);

        }else{

            $productViewModels = $this->setProductByCategory($category,$name,$category->books);

        }

        return $productViewModels;

    }
    public function setHtml($products){
        global $html  ;

        $arrHtml =array();
        if(count($products->getListBook())) {

            foreach ($products->getListBook() as $item) {

                $html .= '<div class="col-sm-4">' .
                    '<div class="product-image-wrapper">' .
                    '<div class="single-products">' .
                    '<div class="productinfo text-center">' .
                    '<img src="' . $item->getImages() . '"' . ' alt="img-' . $item->getTitle() . '"/>' .
                    '<h2>' . $item->getPrice() . 'đ</h2>' .
                    '<p>' . $item->getTitle() . '</p>' .
                    '</div>' .
                    '<div class="product-overlay" >' .
                    '<div class="overlay-content" >' .
                    '<h2>' . $item->getPrice() . 'đ</h2>' .
                    '<p>' . $item->getTitle() . '</p>' .
                    '<a data-img="' . $item->getImages() . '" data-author="'. $item->getAuthor() .'" data-value="'. $item->getId() .'" class="btn btn-default add-to-cart attToCart"></i > Thêm vào giỏ </a >' .
                    '</div>' .
                    '</div>' .
                    '</div>' .
                    '<div class="choose">' .
                    '<ul class="nav nav-pills nav-justified" >' .
                    '<li ><a href = "" ><i class="fa fa-plus-square" ></i > Thêm Vào Danh Sách Yêu Thích </a ></li >' .
                    '</ul>' .
                    '</div>' .
                    '</div>' .
                    '</div>';
            }
            $html .= '<ul class="pagination">' .
                '<li class="active" ><a href = "" > 1</a ></li >' .
                '<li ><a href = "" > 2</a ></li >' .
                '<li ><a href = "" > 3</a ></li >' .
                '<li ><a href = "" >&raquo;</a ></li >' .
                '</ul>';
        }else{

            $html =  '<div class="content-404">'.
                '<p > Sản phẩm không có </p >'.
                '</div>';
        }
      $sideBarHtml =  '<div class="brands-name">'.
           '<ul class="nav nav-pills nav-stacked">'.
               '<li><a  id="category" data-name="'.$products->getpathName() .'" data-sort="biaMem"  data-type="sortFormality"'.
                       ' data-value="'.$products->getpathId().'" href="'.route('home.product',['category'=>$products->getpathName(),'id'=>$products->getpathId(),'sortFormality'=>'biaMem']).'" >'.
                       '<span class="pull-right"></span>Bìa Mềm</a>'.
               '</li>'.
              '<li><a   id="category" data-name="' .$products->getpathName() .'" data-sort="biaCung"  data-type="sortFormality"'.
                        ' data-value="'. $products->getpathId() .'" href="' . route('home.product',['category'=>$products->getpathName(),'id'=>$products->getpathId(),'sortFormality'=>'biaCung']). '"> <span class="pull-right"></span>Bìa Cứng</a></li>'.
         '</ul>'.
       '</div>';

        $sideBarSortHtml = '<h2>Sắp xếp theo</h2>'.
             '<div class="brands-name">'.
            '<ul class="nav nav-pills nav-stacked">'.
                '<li><a id="category" data-name="{{$products->getpathName()}}" data-sort="gia-cao" data-type="sort"'.
                       ' data-value="' . $products->getpathId() . '" href="' . route('home.product',['category'=>$products->getpathName(),'id'=>$products->getpathId(),'sort'=>'gia-cao']). '">'.
                        '<span class="pull-right"></span>Giá cao</a></li>'.
                '<li><a id="category" data-name="' . $products->getpathName() . '" data-sort="gia-thap" data-type="sort"'.
                       ' data-value="'. $products->getpathId() .'" href="' .route('home.product',['category'=>$products->getpathName(),'id'=>$products->getpathId(),'sort'=>'gia-thap']) .'">'.
                        '<span class="pull-right"></span>Giá rẽ</a>'.
                '</li>'.
                '<li><a id="category" data-name="{{$products->getpathName()}}" data-sort="ten-giam" data-type="sortname"'.
                       ' data-value="' .$products->getpathId() . '" href="' . route('home.product',['category'=>$products->getpathName(),'id'=>$products->getpathId(),'sortname'=>'ten-giam']) .'">'.
                        ' <span class="pull-right"></span>Giảm theo tên sách</a>'.
                '</li>'.
                '<li><a  id="category" data-name="' .$products->getpathName() . '" data-sort="ten-tang" data-type="sortname"'.
                        ' data-value="' . $products->getpathId() . '" href="' . route('home.product',['category'=>$products->getpathName(),'id'=>$products->getpathId(),'sortname'=>'ten-tang']).'">'.
                        '<span class="pull-right"></span>Tăng theo tên sách</a></li>'.
            '</ul>'.
        '</div>'.
    '</div>';

     array_push($arrHtml,$html);
       array_push($arrHtml, $sideBarHtml);
       array_push($arrHtml,$sideBarSortHtml);
        return $arrHtml;
    }


    public function getByProductByCategory($name,$id)
    {

       $products = $this->getByCategory($name,$id,null);

         $html = $this->setHtml($products);

          return $html;
    }

    public function sortPriceById($name, $id, $key)
    {

       global $productViewModels ;
        $category =  Category::findOrFail($id);
        if($key === 'gia-thap'){
         $arrSort = $category->books->sortBy("price");
            $productViewModels = $this->setProductByCategory($category,$name,$arrSort);
        }else{
            $arrSort = $category->books->sortByDesc("price");
            $productViewModels = $this->setProductByCategory($category,$name,$arrSort);
        }
        $html = $this->setHtml($productViewModels)[0];
        return $html;
    }

    public function sortNameById($name, $id, $key)
    {
        global $productViewModels ;
        $category =  Category::findOrFail($id);
        if($key === 'ten-tang'){
            $arrSort = $category->books->sortBy("title");
            $productViewModels = $this->setProductByCategory($category,$name,$arrSort);
        }else{
            $arrSort = $category->books->sortByDesc("title");
            $productViewModels = $this->setProductByCategory($category,$name,$arrSort);
        }
        $html = $this->setHtml($productViewModels)[0];
        return $html;
    }

    public function sortFormalityById($name, $id, $key)
    {
        global $productViewModels ;
        $category =  Category::findOrFail($id);
        if($key === 'biaMem'){
            $arrSort = $category->books->where('formality','=','Soft Cover');

            $productViewModels = $this->setProductByCategory($category,$name,$arrSort);
        }else{
            $arrSort = $category->books->where('formality','=','Hard Cover');

            $productViewModels = $this->setProductByCategory($category,$name,$arrSort);
        }
        $html = $this->setHtml($productViewModels)[0];

        return $html;
    }
    public function getProductDetail(string $title, $id)
    {
        global $bookDetailViewModels;
        $book = Book::findOrFail($id);
        $categoryBook =$book->categories->id;
        $bookRaLationShip = Book::where('id','<>',$id)->get();
        $bookRecent =Book::where('id','<>',$id)->orderBy('created_at', 'desc')->paginate(5);
        $bookDetailViewModels = new bookDetailViewModels();

        $bookDetailViewModels->setCategorySlug($this->formatNameToSlug($book->categories->name));
        $bookDetailViewModels->setCategory($book->categories->name);
        $bookDetailViewModels->setIdCategory($book->categories->id);
            $bookDetailViewModels->setOriginalPrice($book->original_Price);
            $bookDetailViewModels->setPercentDiscount($book->percent_discount);
            $bookDetailViewModels->setPrice($book->price);
            $bookDetailViewModels->setTitle($book->title);
            $bookDetailViewModels->setAuthor($book->author->fullname);
            $bookDetailViewModels->setAmount(1);
            $bookDetailViewModels->setPublisher($book->publisher->name);
            $bookDetailViewModels->setWeight($book->weight);
            $bookDetailViewModels->setNumber_of_pages($book->number_of_pages);
            $bookDetailViewModels->setFormality($book->formality);
            $bookDetailViewModels->setId($book->id);
            $bookDetailViewModels->setSize($book->size);
            foreach ($book->imagebooks as $item) {
                $bookDetailViewModels->setlistImages($item->file);
            }
            $bookDetailViewModels->setDescribe($book->describe);
            foreach ($bookRaLationShip as $book) {
                 if($book->categories->id === $categoryBook ) {
                     $bookViewModels = new bookViewModels();
                     $bookViewModels->setId($book->id);
                     $bookViewModels->setTitleSlug($this->formatNameToSlug($book->title));
                     $bookViewModels->setPrice($book->price);
                     $bookViewModels->setImages($book->imagebooks[0]->file);
                     $bookViewModels->setTitle($book->title);
                     $bookViewModels->setPercentDiscount($book->percent_discount);
                     $bookViewModels->setOriginalPrice($book->original_Price);
                     $bookViewModels->setCategory($book->categories->name);
                     $bookViewModels->setIdCategory($book->categories->id);
                     $bookViewModels->setCategorySlug($this->formatNameToSlug($book->categories->name));
                     $bookDetailViewModels->setListBookAll($bookViewModels);
                 }
            }
            foreach ($bookRecent as $book) {
                $bookViewModels = new bookViewModels();
                $bookViewModels->setId($book->id);
                $bookViewModels->setTitleSlug($this->formatNameToSlug($book->title));
                $bookViewModels->setPrice($book->price);
                $bookViewModels->setImages($book->imagebooks[0]->file);
                $bookViewModels->setTitle($book->title);
                $bookViewModels->setPercentDiscount($book->percent_discount);
                $bookViewModels->setOriginalPrice($book->original_Price);
                $bookViewModels->setCategory($book->categories->name);
                $bookViewModels->setIdCategory($book->categories->id);
                $bookViewModels->setCategorySlug($this->formatNameToSlug($book->categories->name));
                $bookDetailViewModels->setListBookRecent($bookViewModels);

        }

        return $bookDetailViewModels;
    }
}
