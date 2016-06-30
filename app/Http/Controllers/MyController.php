<?php namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use Illuminate\Pagination\Paginator;
use DB;
use Cart;

use App\Helper\helpers;



class MyController extends Controller {

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
        return view('books.index',compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $obj = new helpers();
        echo $obj->somethingOrOther();

        return view('books.create',compact('obj'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


  
            $image = Input::file('image');
            $filename  = time() . '.' . $image->getClientOriginalExtension();

            $path = public_path('uploads/img/' . $filename);
 
        
            Image::make($image->getRealPath())->resize(200, 200)->save($path);
            $user->image = $filename;
            $user->save();
           



        $obj = new helpers();
        echo "<pre>";print_r(Input::file('image'));exit;

        $book=Request::all();
        //echo "<pre>";print_r($_FILES['image']['name']);exit;
       
        $destinationPath = 'uploads/img/'; // upload path
        $thumb_path = 'uploads/img/thumb/';
        $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
        $fileName = rand(111111111,999999999).'.'.$extension; // renameing image
        Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
        
        $obj->createThumbnail($fileName,300,200,$destinationPath,$thumb_path);

       

        $book['image']=$fileName;
        Book::create($book);
        Session::flash('success', 'Upload successfully'); 
        return redirect('image');
        
    }

   

    public function cart(){

        //Cart::destroy();
        Cart::add('1234', 'Product 1', 1, 10.00, array('size' => 'large'));

        $content = Cart::content();
        echo "<pre>";print_r($content);


    }







}
