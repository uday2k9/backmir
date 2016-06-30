<?php namespace App\Http\Controllers\Admin;

use App\Book;
use App\Model\Mobile;
use App\Http\Requests;
use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Illuminate\Pagination\Paginator;
use DB;

class BookController extends Controller {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function index()
   {
      //
        //$books=Book::all();
		//$books=Book::paginate(2);
		//$books = DB::table('books')->orderBy('id','DESC')->get();
		$books = DB::table('books')->orderBy('id','DESC')->paginate(2);
        //echo '<pre>';print_r($books); exit;
	    $books->setPath('book');
        return view('admin.books.index',compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('admin.books.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $book=Request::all();
       
        $destinationPath = 'uploads/img/'; // upload path
        $thumb_path = 'uploads/img/thumb/';
        $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
        $fileName = rand(111111111,999999999).'.'.$extension; // renameing image
        Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
       // $this->create_thumbnail($thumb_path,$fileName,$extension);
        $book['image']=$fileName;
        Book::create($book);
        Session::flash('success', 'Upload successfully'); 
        return redirect('admin/book');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $book=Book::find($id);
       return view('admin.books.show',compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book=Book::find($id);
        return view('admin.books.edit',compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
           $bookUpdate=Request::all();
           $book=Book::find($id);
           $book->update($bookUpdate);
           return redirect('admin/book');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response	7278876384
     */
    public function destroy($id)
    {
        //
        Book::find($id)->delete();
        return redirect('admin/book');
    }

    public function create_thumbnail($path, $filename, $extension)
    {
        $width  = 50;
        $height = 50;
        $mode   = ImageInterface::THUMBNAIL_OUTBOUND;
        $size   = new Box($width, $height);

        $thumbnail   = Imagine::open("{$path}/{$filename}.{$extension}")->thumbnail($size, $mode);
        $destination = "{$filename}.thumb.{$extension}";

        $thumbnail->save("{$path}/{$destination}");
    }
}
