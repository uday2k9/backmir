<?php namespace App\Http\Controllers\Admin;

use App\Book;
use App\Model\Mobile;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;

class PostController extends Controller {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
  public function index()
  {
    return view('admin.post.index');
  }

    
	
	
	
}
