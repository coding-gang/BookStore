<?php


namespace App\Services;

use App\Contracts\BookContract;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Models\ImageBook;
use App\Models\Publisher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class BookService implements BookContract
{

    public function getAll()
    {
      $books =  Book::all();
      return $books;
    }

    public function show($id)
    {
         $book =  Book::findOrFail($id);
         return $book;
    }

    public function create()
    {
        $result = array();
        $author =   Author::all();
        array_push($result,$author);
        $publisher = Publisher::all();
        array_push($result,$publisher);
        $categories = Category::all();
        array_push($result,$categories);
        return $result ;

    }

    public function update($request, $id)
    {
        $input =$request->all();
        unset($input['input24']);
        unset($input['_token']);
        unset($input['_method']);
        $dateUpdate =Carbon::now();
        $input['updated_at'] =$dateUpdate->toDateTimeString();

        DB::table('books')->where('id', $id)->update($input);

        if($request['input24'] !== null){
            foreach ($request->file('input24')  as $file){
                $name = time() . $file->getClientOriginalName();
                $file->move('imagesBook', $name);
                ImageBook::create(['file' => $name,'book_id'=>$id]);
            }
        }
        return "Update success!";
    }

    public function delete($id)
    {
         $book = Book::findOrFail($id);
         $images = $book->imagebooks;
         foreach ($images as $img){
             ImageBook::destroy($img->id);
         }
         $book->delete();
         return "Delete success!";
    }

    public function edit($id)
    {
        $result = array();
        $book =  Book::findOrFail($id);
        array_push($result,$book);
        $author =   Author::all();
        array_push($result,$author);
        $publisher = Publisher::all();
        array_push($result,$publisher);
        $categories = Category::all();
        array_push($result,$categories);
        return $result ;
    }

    public function deleteImage($request, $id)
    {

        ImageBook::destroy($request['id']);
        return "Delete success!";
    }

    public function store($request)
    {
        $input =$request->all();
        unset($input['input-file']);
        $book = Book::create($input);
        if($request['input-file'] !== null) {
            foreach ($request->file('input-file') as $file) {
                $name = time() . $file->getClientOriginalName();
                $file->move('imagesBook', $name);
                ImageBook::create(['file' => $name, 'book_id' => $book->id]);
            }
        }
        return "Create success!";
    }
}
