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
use App\Helper\helpers;
use DB;
use Hash;
use Mail;


class CmsController extends BaseController {
    
    public function __construct() 
    {
	   parent::__construct();
	   $obj = new helpers();
        
        view()->share('obj',$obj);
	 if(!$obj->checkBrandLogin())
        {
            $brandlogin = 0; // Logged as a member
        }
        else
        {
            $brandlogin = 1; // Logged as a brand
        }
        view()->share('brandlogin',$brandlogin);
    }

    public function showContent($param)
    {
        $cms = DB::table('cmspages')
                    ->where('slug',$param)
                    ->first();
        //print_r($cms);  exit;          
        //echo $param;
	//exit;
	if(isset($cms)){
        return view('frontend.cms.cms',compact('cms'),array('title'=>'Miramix '.$cms->title,'meta_name'=>$cms->meta_name,'meta_description'=>$cms->meta_description,'meta_keyword'=>$cms->meta_keyword));
	}else{
	    
	    
	    $all_brand_member = DB::table('brandmembers')->where('slug', $param)->first();
	
 if(!isset($all_brand_member) && $param!='page-not-found'){

 		return view('frontend.404',array('title'=>'MIRAMIX | Page not found','brand_active'=>'active'));		
	
      }else{
	
	 $whereClause = array('role'=>1,'status'=>1,'admin_status'=>1,'subscription_status'=>'active','id'=>$all_brand_member->id);
        $brand = DB::table('brandmembers')->where($whereClause)->first();
        if(!isset($brand)){
           return redirect('brands'); 
        }
     
        $page=Request::input('page');
	if(!empty($page)){
	    $current_page = filter_var($page, FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
	    if(!is_numeric($current_page)){die('Invalid page number!');} //incase of invalid page number
	    if($current_page<1){$current_page=1;}
	}else{
	    $current_page = 1; //if there's no page number, set it to 1
	}
        
        
        
        //$total_brand_pro = 0;
        $item_per_page=3;
        $product = DB::table('products')
                 ->select(DB::raw('products.id,products.brandmember_id,products.product_name,products.product_slug,products.image1, MIN(`product_formfactor_durations`.`actual_price`) as `min_price`,MAX(`product_formfactor_durations`.`actual_price`) as `max_price`'))
                 ->leftJoin('product_formfactors', 'products.id', '=', 'product_formfactors.product_id')
                 ->leftJoin('product_formfactor_durations', 'product_formfactor_durations.product_formfactor_id', '=', 'product_formfactors.id')                 
                 ->where('products.brandmember_id', '=', $all_brand_member->id)
                 ->where('products.visiblity', 0)
                 ->where('products.active', 1)
                 ->where('products.is_deleted', 0)
                 ->where('product_formfactors.servings', '!=',0)
                 ->where('products.discountinue', 0)                
                 ->where('product_formfactor_durations.actual_price','!=', 0)
                 ->groupBy('product_formfactors.product_id')
                ;

       $sortby=Request::input('sortby');
	 if(!empty($sortby)){
	    
	    if($sortby=='popularity'){
		$product->orderBy('popularity', 'DESC');
	    }elseif($sortby=='price'){
		$product->orderBy('min_price', 'ASC');
	    }elseif($sortby=='date'){
		$product->orderBy('created_at', 'DESC');
	    }else{
		$product->orderBy('popularity', 'DESC');
	    }
	    
	 }else{
		$product->orderBy('popularity', 'DESC');
	    }
          $product=$product->paginate($item_per_page);
          
        $total_brand_product = DB::table('products')
                ->select(DB::raw('products.id'))
                 ->Join('product_formfactors', 'products.id', '=', 'product_formfactors.product_id')
                 ->leftJoin('product_formfactor_durations', 'product_formfactor_durations.product_formfactor_id', '=', 'product_formfactors.id')                 
                 ->where('products.brandmember_id', '=', $all_brand_member->id)
                 ->where('product_formfactors.servings', '!=',0)
                 ->where('products.active', 1)
                 ->where('products.visiblity', 0)
                 ->where('products.is_deleted', 0)
                 ->where('products.discountinue', 0)                 
                 ->where('product_formfactor_durations.actual_price','!=', 0)
                 ->groupBy('product_formfactors.product_id')
                 ->get();



          // $obj = new helpers();
          // echo $obj->get_last_query();

       // echo "<pre/>";print_r($product); exit;
        
        $total_brand_pro = count($total_brand_product);
        
        
        $total_pages=ceil($total_brand_pro/$item_per_page);
	
	$offset = ($current_page - 1)  * $item_per_page;

	
        $from = $offset + 1;
        $to = min(($offset + $item_per_page), $total_brand_pro);
            
          $brand_slug=  $param;

        $product->setPath($brand_slug);
        if($current_page==1 && (!Request::isMethod('post'))){
        return view('frontend.brand.brand_details',compact('all_brand_member','total_brand_pro','product','item_per_page','current_page','total_pages','from','to','brand_slug'),array('title'=>'MIRAMIX | Brand Listing','brand_active'=>'active'));
        }else{
        return view('frontend.brand.brand_details_next',compact('all_brand_member','total_brand_pro','product','item_per_page','current_page','total_pages','from','to','brand_slug'),array('title'=>'MIRAMIX | Brand Listing','brand_active'=>'active'));            
        }
   	 }
	}
	
    }
    
    
    public function contactUs(){
	$member1=Session::get('brand_userid');
	$member2=Session::get('member_userid');
	if(!empty($member1)){
	$memberdetail = Brandmember::find($member1);
	}elseif(!empty($member2)){
	   $memberdetail = Brandmember::find($member2); 
	}else{
	   $memberdetail=(object)array("email"=>"","fname"=>"","lname"=>""); 
	}
	
	 if(Request::isMethod('post'))
        {
				$user_name = Request::input('contact_name');
				$user_email = Request::input('contact_email');
				$subject = Request::input('contact_subject');
				$cmessage = Request::input('message');
				
				$setting = DB::table('sitesettings')->where('name', 'email')->first();
				$admin_users_email=$setting->value;
				
				
				$sent = Mail::send('frontend.cms.contactemail', array('name'=>$user_name,'email'=>$user_email,'messages'=>$cmessage,'admin_users_email'=>$admin_users_email), 
				
				function($message) use ($admin_users_email, $user_email,$user_name)
				{
					$message->from($admin_users_email);
					$message->to($user_email, $user_name)->cc($admin_users_email)->subject(Request::input('contact_subject'));
					
				});
	
				if( ! $sent) 
				{
					Session::flash('error', 'something went wrong!! Mail not sent.'); 
					return redirect('contact-us');
				}
				else
				{
				    Session::flash('success', 'Message is sent to admin successfully. We will getback to you shortly'); 
				    return redirect('contact-us');
				}
	    
	}
	
	return view('frontend.cms.contactus',compact('memberdetail'),array('title'=>'Miramix - Contact Us'));	
    }
}
?>