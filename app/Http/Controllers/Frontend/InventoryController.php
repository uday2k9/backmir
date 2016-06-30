<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/

use App\Model\Brandmember;  /* Model name*/
use App\Model\ProductIngredient;
use App\Model\ProductFormfactor;
use App\Model\Address;      /* Model name*/
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input;              /* For input */
use Validator;
use Session;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Illuminate\Pagination\Paginator;
use DB;
use Hash;
use Mail;
use Authorizenet;
use App\Helper\helpers;



class InventoryController extends BaseController {

    public function __construct() 
    {
        parent::__construct();
        $obj = new helpers();
        
        view()->share('obj',$obj);
    }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   
    public function inventory(){


      $member1=Session::get('brand_userid');
     
      if(!empty($member1)){
      $memberdetail = Brandmember::find($member1);
      }else{
         $memberdetail=(object)array("email"=>"","fname"=>"","lname"=>""); 
      }

      if(Request::isMethod('post'))
      {
        $name = Request::input('name');
        $ingradient_name = Request::input('ingradient_name');
        $user_email = Request::input('contact_email');
        $subject = 'Request for ingredient';
        $cmessage = Request::input('request_ing');
        
        $setting = DB::table('sitesettings')->where('name', 'email')->first();
        $admin_users_email=$setting->value;
        
        
        $sent = Mail::send('frontend.inventory.ingredientemail', array('admin_users_email'=>$admin_users_email,'name'=>$name,'ingradient_name'=>$ingradient_name,'email'=>$user_email,'messages'=>$cmessage), 
        
        function($message) use ($admin_users_email, $user_email,$ingradient_name,$subject)
        {
          $message->from($admin_users_email);
          $message->to($user_email, $ingradient_name)->cc($admin_users_email)->subject($subject);
          
        });
  
        if(!$sent) 
        {
          Session::flash('error', 'something went wrong!! Mail not sent.'); 
          return redirect('inventory');
        }
        else
        {
            Session::flash('success', 'Message is sent to admin successfully. We will getback to you shortly'); 
            return redirect('inventory');
        }
      }


      $start='a';
      $end='z'; 
      $pageindex=array();
      for($i=$start;$i<$end;$i++){
         
         $inv=DB::table('ingredients')->whereRaw(" name like '".$i."%'")
                     ->orderBy('name', 'ASC')->get();
             $pageindex[$i]=$inv;
      }
      $inv=DB::table('ingredients')->whereRaw(" name like 'z%'")
                     ->orderBy('name', 'ASC')->get();
             $pageindex['z']=$inv;
      
      return view('frontend.inventory.inventory',compact('pageindex','memberdetail'),array('title'=>'Miramix Inventory')); 
    }

    public function inventory_details($inventory_id=false){

      if($inventory_id==''){
		Session::flash('error', 'Please select valid ingrediant.');
		return redirect('inventory');	
	}
        $ingrproducts=ProductIngredient::with('ingredientProducts')->where("ingredient_id",$inventory_id)->get();
       
        //$ingrproducts->setpath("inventory-products/".$inventory_id);
        return view('frontend.inventory.products',compact('ingrproducts'),array('title'=>'Miramix Inventory Products'));
    }


    
}