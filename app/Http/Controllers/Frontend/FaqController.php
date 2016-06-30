<?php
namespace App\Http\Controllers\Frontend;

use App\Model\Brandmember;  /* Model name*/
use App\Model\Subscription;
use App\Model\Address;      /* Model name*/
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input;              /* For input */
use Validator;
use Session;
use Imagine\Image\Box;
use Image\Image\ImageInterface;

use DB;
use Hash;
use Mail;


class FaqController extends BaseController {
    
    public function __construct() 
    {
	   parent::__construct();
	  
    }
    
    public function faqList()
    {
        //ehco "ee"; exit;
       $allfaq =  DB::table('faqs')->get();
       return view('frontend.faq.faq',compact('allfaq'),array('title'=>'Miramix FAQ','module_head'=>'Miramix FAQ'));
    }
    
}
?>