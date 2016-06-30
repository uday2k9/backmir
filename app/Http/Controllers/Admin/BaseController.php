<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Book;
use App\Model\Mobile;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use Illuminate\Pagination\Paginator;
use DB;

use App\Helper\helpers;



class BaseController extends Controller {

	public function __construct() 
    {
		/* All Site Settings List */
        $sitesettings = DB::table('sitesettings')->get();
        $all_sitesetting = array();
        foreach($sitesettings as $each_sitesetting)
        {
            $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
        }
        
	    view()->share('all_sitesetting',$all_sitesetting);

    }

  
   
   

}
