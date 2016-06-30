<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/

use App\Model\Brandmember; /* Model name*/
use App\Model\Newsletter;  /* Model name*/
use App\Model\Searchtag;   /* Model name*/
use App\Model\FormFactor;  /* Model name*/
use App\Http\Requests;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Request;
use Mail;
use Input; /* For input */
use Validator;
use Session;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Illuminate\Pagination\Paginator;
use DB;
use Hash;
use Auth;
use Cookie;
use App\Helper\helpers;
use Cart;
use App\Model\Subscription;
use App\Model\Subscriptionhistory;
use App\Model\Brandcoupons;
use App\Model\Product;
use Redirect;
//use Socialize;
use App\Model\Address; 

class HomeController extends BaseController {

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
    public function index()
    { 
    	// Removing
		//if(substr($_SERVER['SERVER_NAME'],0,4) != "www." && $_SERVER['SERVER_NAME'] != '192.168.1.112' && $_SERVER['SERVER_NAME'] != 'localhost')
		//header('Location: http://www.'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);


		
	    $body_class = 'home';

	    $phone_class = 'telP_top hover';	

		$page=Request::input('page');
		if(!empty($page)){
		    $current_page = filter_var($page, FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
		    if(!is_numeric($current_page)){die('Invalid page number!');} //incase of invalid page number
		    if($current_page<1){$current_page=1;}
		}else{
		    $current_page = 1; //if there's no page number, set it to 1
		}
		
		

		$item_per_page=6;
		
		// Custom Time Frame
		/*$products =DB::table('products')
	                 ->select(DB::raw('products.id,products.brandmember_id,products.product_name,products.product_slug,products.image1, MIN(`actual_price`) as `min_price`,MAX(`actual_price`) as `max_price`,products.created_at, AVG(product_rating.rating_value) as rating '))
	                 ->leftJoin('product_formfactors', 'products.id', '=', 'product_formfactors.product_id')
	                 ->leftJoin('brandmembers', 'products.brandmember_id', '=', 'brandmembers.id')
			 ->leftJoin('product_rating', 'products.id', '=', 'product_rating.product_id')
	                 ->where('subscription_status', "active")
	                 ->where('brandmembers.admin_status', 1)
	                 ->where('is_deleted', 0)
	                 ->whereRaw('products.active="1"')
			 ->whereRaw('products.visiblity="0"')
			 ->where('product_formfactors.actual_price','!=', 0)
	                 ->groupBy('product_formfactors.product_id')
			 ->groupBy('product_rating.product_id')	 ;*/

	    $products =DB::table('products')
	             ->select(DB::raw('products.id,products.brandmember_id,products.product_name,products.product_slug,products.image1, MIN(product_formfactor_durations.actual_price) as `min_price`,MAX(product_formfactor_durations.actual_price) as `max_price`,products.created_at, AVG(product_rating.rating_value) as rating '))
	             ->leftJoin('product_formfactors', 'products.id', '=', 'product_formfactors.product_id')
	             ->leftJoin('brandmembers', 'products.brandmember_id', '=', 'brandmembers.id')
	             ->leftJoin('product_rating', 'products.id', '=', 'product_rating.product_id')
	             ->leftJoin('product_formfactor_durations', 'product_formfactor_durations.product_formfactor_id', '=', 'product_formfactors.id')
	             ->where('subscription_status', "active")
	             ->where('brandmembers.admin_status', 1)
	             ->where('is_deleted', 0)
	             ->whereRaw('products.active="1"')
	             ->whereRaw('products.visiblity="0"')
	             ->where('product_formfactor_durations.actual_price','!=', 0)
	             ->groupBy('product_formfactors.product_id')
	             ->groupBy('product_rating.product_id');
	        
	                
		$tags=Request::input('tags');
		$sku=Request::input('sku');
		$form_factor=Request::input('form_factor');

		if(!empty($sku)){
		   $products->whereRaw('products.sku="'.$sku.'"');
		    }
		  elseif(!empty($tags)){
		  	
		    $tag=explode(",",$tags);
		    $ptags='';
		    $array_set="";

		    foreach($tag as $createarray){

		    	if($array_set==""){
		    		//echo $createarray;
		    		//echo "==0==";
		    		$exp=explode("||",$createarray);
					if($exp[1]=="Tags"){$type="tags";}
					if($exp[1]=="Brand"){$type="brand_name";}
					if($exp[1]=="Product"){$type="product_name";}
					if($exp[1]=="Ingredient"){$type="ing_tags";}
					if($exp[1]=="Feeling"){$type="fill_tags";}
					$name= trim($exp[0]);
					$condition_arr = array('name'=>$name,'type'=>$type);
					$Searchtag = Searchtag::where($condition_arr)->get()->toArray();
					//print_r($Searchtag);
					foreach ($Searchtag as $key => $ptagx) {
						if($array_set==""){
							$array_set=$ptagx['product_id'];

						}else{
							$array_set=$array_set.','.$ptagx['product_id'];
						}
						# code...
					}


		    	}
		    	else{
		    		//echo $createarray;
		    		//echo "==1==";
		    		if(isset($createarray) && count($createarray) > 0) {
			    		$exp=explode("||",$createarray);
						if($exp[1]=="Tags"){$type="tags";}
						if($exp[1]=="Brand"){$type="brand_name";}
						if($exp[1]=="Product"){$type="product_name";}
						if($exp[1]=="Ingredient"){$type="ing_tags";}
						if($exp[1]=="Feeling"){$type="fill_tags";}
						$name= trim($exp[0]);
						$condition_arr = array('name'=>$name,'type'=>$type);
						$Searchtag = Searchtag::where($condition_arr)->whereRaw('product_id IN('.$array_set.')')->get()->toArray();
						//print_r($Searchtag);
						$array_set="";
						foreach ($Searchtag as $key => $ptagx) {
							if($array_set==""){
								$array_set=$ptagx['product_id'];

							}else{
								$array_set=$array_set.','.$ptagx['product_id'];
							}
							# code...
						}
					}
		    	}

		    }
		    	//echo $array_set;
	    	if(!empty($array_set)){ 
			 $products->whereRaw('products.id IN('.$array_set.')');
			}

				    // foreach($tag as $t){
				    // 	$exp=explode("||",$t);
				    // 	if($exp[1]=="Tags"){$type="tags";}
				    // 	if($exp[1]=="Brand"){$type="brand_name";}
				    // 	if($exp[1]=="Product"){$type="product_name";}
				    // 	if($exp[1]=="Ingredient"){$type="ing_tags";}
				    // 	if($exp[1]=="Feeling"){$type="fill_tags";}
				    // 	$name= trim($exp[0]);
				    // 	if($ptags==''){
				    // 		$ptags="(name='".$name."' AND type='".$type."')";
				    // 	}
				    // 	else{
				    // 		$ptags=$ptags." OR (name='".$name."' AND type='".$type."')";
				    // 	}

				    // }
				// // foreach($tag as $t){
				// // $ptags .=trim($t)."','";
				// // }
				// // $ptags=rtrim($ptags,",'");
				// //print_r($ptags);
				// $tagpro = DB::table('searchtags')->whereRaw($ptags)->get();
				// //print_r($tagpro);
				// $pids='';
				// foreach($tagpro as $tagp){
				// $pids .=$tagp->product_id.",";
				// }

				// $pids=rtrim($pids,",");

				// $i=1;

				// if(!empty($pids)){ 
				// $products->whereRaw('products.id IN('.$pids.')');
				// }
		  
		  }
	    
	    $form_factor=Request::input('form_factor');

	    $ff="";
	    if(!empty($form_factor)){

	    	foreach ($form_factor as $key => $formr) {
	    		# code...
	    		if($formr!=""){
		    		if($ff==""){
		    			$ff=$formr;

		    		}
		    		else{

		    			$ff=$ff.",".$formr;
		    		}
		    	}
	    	}
	    	if($ff!=""){
	    		$products->whereRaw('product_formfactors.formfactor_id IN ('.$ff.')');
	    	}
	    	

	    }
		$sortby=Request::input('sortby');
		
		 if(!empty($sortby)){
		    
		    if($sortby=='popularity'){
			$products->orderBy('rating', 'DESC');
		    }elseif($sortby=='pricelow'){
			$products->orderBy('min_price', 'ASC');
		    }
		    elseif($sortby=='pricehigh'){
			$products->orderBy('min_price', 'DESC');
		    }
		    elseif($sortby=='date'){
			$products->orderBy('created_at', 'DESC');
		    }else{
			$products->orderBy('rating', 'DESC');
		    }
		    
		 }else{
		    $products->orderBy('rating', 'DESC');
		 }
		 
		 $products=$products->paginate($item_per_page);
		 
		 $queries = DB::getQueryLog();

		 


	      /*$products2 = DB::table('products')
	                 ->select(DB::raw('products.id,products.brandmember_id,products.product_name,products.product_slug,products.image1, MIN(`actual_price`) as `min_price`,MAX(`actual_price`) as `max_price`,products.created_at'))
	                 ->leftJoin('product_formfactors', 'products.id', '=', 'product_formfactors.product_id')
	                 ->leftJoin('brandmembers', 'products.brandmember_id', '=', 'brandmembers.id')
	                 ->where('subscription_status', "active")
	                 ->where('brandmembers.admin_status', 1)
			 
	                 ->where('is_deleted', 0)
	                 ->whereRaw('products.active="1"')
			  ->whereRaw('products.visiblity="0"')
	         ->where('product_formfactors.actual_price','!=', 0)
	                 ->groupBy('product_formfactors.product_id');   */

	    // Custom Time Frame

			$products2 = DB::table('products')
			 ->select(DB::raw('products.id,products.brandmember_id,products.product_name,products.product_slug,products.image1, MIN(`product_formfactor_durations`.`actual_price`) as `min_price`,MAX(`product_formfactor_durations`.`actual_price`) as max_price, products.created_at'))
			 ->leftJoin('product_formfactors', 'products.id', '=', 'product_formfactors.product_id')
			 ->leftJoin('brandmembers', 'products.brandmember_id', '=', 'brandmembers.id')
			 ->leftJoin('product_formfactor_durations', 'product_formfactor_durations.product_formfactor_id', '=', 'product_formfactors.id')                 
			 ->where('subscription_status', "active")
			 ->where('brandmembers.admin_status', 1)
			 ->where('is_deleted', 0)
			 ->whereRaw('products.active="1"')
			 ->whereRaw('products.visiblity="0"')
			 ->where('product_formfactor_durations.actual_price','!=', 0)
			 ->groupBy('product_formfactors.product_id');
	        
		$tags=Request::input('tags');
		$sku=Request::input('sku');
		$form_factor=Request::input('form_factor');
		  $ff="";
		    if(!empty($form_factor)){
		    	foreach ($form_factor as $key => $formr) {
		    		# code...
		    		if($formr!=""){
			    		if($ff==""){
			    			$ff=$formr;

			    		}
			    		else{

			    			$ff=$ff.",".$formr;
			    		}
			    	}
		    	}
		    	if($ff!=""){
		    		$products2->whereRaw('product_formfactors.formfactor_id IN ('.$ff.')');
		    	}

		    }
		  if(!empty($sku)){
		       $products2->whereRaw('products.sku="'.$sku.'"'); 
		    }
		  elseif(!empty($tags)){
		    
		
		    if($array_set!=""){ 
		    	$products2->whereRaw('products.id IN('.$array_set.')');
		   }
		  }
		
		  $p2=$products2->get();
		  $total_records=count($p2);
		
		
		$total_pages=ceil($total_records/$item_per_page);
		
		 $offset = ($current_page - 1)  * $item_per_page;

		    // Some information to display to the user
		    $from = $offset + 1;
		    $to = min(($offset + $item_per_page), $total_records);
		    if($to==0){
			$from=0;
		    }

		    $formfactor = FormFactor::get();
		    
		    
		if($current_page==1 && (!Request::isMethod('post'))){
	        return view('frontend.home.index',compact('body_class','phone_class','products','item_per_page','current_page','total_records','total_pages','from','to','formfactor'),array('title'=>'MIRAMIX | Home'));
		}else{ 
		    
		return view('frontend.home.indexnextpage',compact('body_class','phone_class','products','item_per_page','current_page','total_records','total_pages','from','to','formfactor'),array('title'=>'MIRAMIX | Home'));
		  
		}
    }
    
    public function homenext(){
	
	echo 'test';
    }

    
    public function userLogout() /* For All user logout */
    {
    	
 
	
        if(Session::has('member_userid'))
        {
            Session::forget('member_userid');
            Session::forget('member_user_email');
            Session::forget('member_username');
            
            /* Delete Cart Session */
            
			Session::forget('coupon_code');
			Session::forget('coupon_type');
			Session::forget('coupon_discount');
			Session::forget('coupon_amount');
			Session::forget('share_coupon_status');
            Session::forget('product_id');
            Session::forget('force_social_share');

			/* Delete Cart Session */

            /* Delete Checkout Session */
            Session::forget('select_address');                  
            Session::forget('selected_address_id');
            Session::forget('payment_method');
            Session::forget('step3');
            Session::forget('step1');
            Session::forget('guest_array');
            Session::forget('guest');
	    	Session::forget('social_login');
	    
            /* Delete Checkout Session */
            
			Cart::destroy(); // If any thing in cart session destroy the cart session  //
            Session::flash('success', 'You are successfully logged out.'); 
            return redirect('memberLogin');
        }
        else if(Session::has('brand_userid'))
        {
            Session::forget('brand_userid');
            Session::forget('brand_user_email');
            Session::forget('brand_username');
	    	Session::forget('social_login');
            Session::flash('success', 'You are successfully logged out.'); 
            return redirect('brandLogin');
        } 
        else{
        	 return redirect('home');
        }      
    }


    public function member_login()
    {
		if(substr($_SERVER['SERVER_NAME'],0,4) != "www." && $_SERVER['SERVER_NAME'] != '192.168.1.112' && $_SERVER['SERVER_NAME'] != 'localhost')
			header('Location: http://www.'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	/*if( ! Request::secure() )
	{
	    return Redirect::secure( Request::path() );
	}*/


        $obj = new helpers();
         if($obj->checkMemberLogin()){
            return redirect('member-dashboard');
        }
		if($obj->checkBrandLogin()){
            return redirect('brand-dashboard'); 
        }
    	Session::put('member_type', 0);
        if(Request::isMethod('post'))
        {
            $email = Request::input('email');
            $password = Request::input('password');
            $encrypt_pass = Hash::make($password);

            $login_arr = array('email' => $email, 'password' => $encrypt_pass);


            $users = DB::table('brandmembers')->where('email', $email)->where('role', 0)->first();            
           // print_r($_POST);exit;
            
            if($users!=""){

                $user_pass = $users->password;
                // check for password
                if(Hash::check($password, $user_pass)){

                    // Check for active                 
                    $user_cnt = DB::table('brandmembers')->where('email', $email)->where('status', 1)->where('admin_status', 1)->count();
                    //echo $user_cnt;exit;
                    //echo DB::enableQueryLog();exit;
                    if($user_cnt){
                        Session::put('member_userid', $users->id);
                        Session::put('member_user_email', $users->email);
                        Session::put('member_username', ucfirst($users->username));

                        // Check for remember me
                        if(Request::input('remember_me')==1){
                            Cookie::queue(Cookie::make('mem_email', Request::input('email'), 60 * 24 * 30));
                        }
						$this->update_cart($users->id);
						//dd("Pass - 1");
						//exit;
                        //return redirect('/');
                        return redirect('member-dashboard');
                    }
                    else{
			$site = DB::table('sitesettings')->where('name','email')->first();
                        Session::flash('error', 'Your Status is inactive. Contact Admin at '.$site->value.' to get your account activated!'); 
                        return redirect('memberLogin');
                    }
                }
                else{
                        Session::flash('error', 'Email and password does not match.'); 
                        return redirect('memberLogin');
                }
            }
            else{
                    Session::flash('error', 'Email and password does not match.'); 
                    return redirect('memberLogin');
            }
        }




        // check for remenber me cookie
        $mem_email = '';
        $mem_email = Cookie::get('mem_email');

        return view('frontend.home.member_login',compact('mem_email'),array('title'=>'MIRAMIX | Member Login'));
    } 

	private function update_cart($uid)
	{
		/*  All cart get from DB of logged user */
	
		//echo "<pre>"; print_r($db_cart); exit;					
		$cart_num = Cart::count(); //Count cart item from session
		
		if($cart_num>0)  // If there cart data in Session
		{
			//add to db for guest user's added cart
			$content = Cart::content();
			foreach($content as $eachcontentCart)
			{
				$cartRowId = $eachcontentCart->rowid;
			}
			//echo "<pre>";print_r($content);
			
			foreach($content as $each_content)
			{
				$product_id = $each_content->id;
				$product_name = $each_content->name;
				$product_quantity = $each_content->qty;
				$product_price = $each_content->price; // Price amount for each item
				$product_duration = $each_content->options->duration;
				$product_no_of_days = $each_content->options->no_of_days;
				$product_form_factor = $each_content->options->form_factor;
				$subtotal = $each_content->subtotal;
				$cartRowId = $each_content->rowid;
				
				 $cartContent = DB::table('carts')
                                ->where('user_id',Session::get('member_userid'))
                                ->where('product_id',$product_id)
                                ->where('no_of_days',$product_no_of_days)
                                ->where('form_factor',$product_form_factor)
                                ->first();
								
				if(count($cartContent)<1) // cart item not matches with database content so, insert as a new cart item
				{
					$insert_cart = DB::table('carts')->insert(['user_id' => Session::get('member_userid'), 'row_id' => $cartRowId, 'product_id' => $product_id , 'product_name' => $product_name, 'quantity' => $product_quantity, 'amount' => $product_price, 'duration' => $product_duration, 'no_of_days' => $product_no_of_days, 'form_factor' => $product_form_factor,'sub_total' => $subtotal, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
					//exit;
				}
				else  // cart item  matches with database content so, update quantity ofthat particular item
				{
					$new_quantity = ($cartContent->quantity)+$product_quantity;  //quantity from DB + cart item quantity
                    $new_sub_total = $new_quantity * $product_price;            // Sub Total 
					$update_cart = DB::table('carts')
										->where('cart_id', $cartContent->cart_id)
										->update(['quantity' => $new_quantity,'sub_total'=>$new_sub_total]);
				}
			} //Foreach End
			//now db contains all previous and current added cart items...so deleted all cart items and add all contents from db
			
			Cart::destroy(); // After inserting all cart data into database just
			
			
		} // Cart session If End
		 
		 
		 /*  All cart get from DB of logged user */
			 $dbCartContent = DB::table('carts')
                                ->where('user_id',Session::get('member_userid'))
                               ->get();
			
			foreach($dbCartContent as $eachCartContent)
			{
				Cart::add($eachCartContent->product_id, $eachCartContent->product_name, $eachCartContent->quantity, $eachCartContent->amount, array('duration' => $eachCartContent->duration,'no_of_days'=>$eachCartContent->no_of_days,'form_factor'=>$eachCartContent->form_factor));	

			}
			
			DB::table('carts')->where("user_id",Session::get('member_userid'))->delete();
			
			$content = Cart::content();
			foreach($content as $each_content)
			{
				$product_id = $each_content->id;
				$product_name = $each_content->name;
				$product_quantity = $each_content->qty;
				$product_price = $each_content->price; // Price amount for each item
				$product_duration = $each_content->options->duration;
				$product_no_of_days = $each_content->options->no_of_days;
				$product_form_factor = $each_content->options->form_factor;
				$subtotal = $each_content->subtotal;
				$cartRowId = $each_content->rowid;
				
				
				$insert_cart = DB::table('carts')->insert(['user_id' => Session::get('member_userid'), 'row_id' => $cartRowId, 'product_id' => $product_id , 'product_name' => $product_name, 'quantity' => $product_quantity, 'amount' => $product_price, 'duration' => $product_duration, 'no_of_days' => $product_no_of_days, 'form_factor' => $product_form_factor,'sub_total' => $subtotal, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
			}
	}

   
/**************************************  MEMBER FORGOT PASSWORD START ************************************/
/**************************  MEMBER RESET PASSWORD START  **************************/
    public function member_forgotPassword()
    {
        
       $obj = new helpers();
        if($obj->checkUserLogin()){
            return redirect('member-dashboard');
        }

        if(Request::isMethod('post'))
        {
            $email = Request::input('email');
            $brandmembers = DB::table('brandmembers')->where('email', '=', $email)->where('role', '=', 0)->first();
            $random_code = mt_rand();
            $updateWithCode = DB::table('brandmembers')->where('email', '=', $email)->update(array('code_number' => $random_code));
            $sitesettings = DB::table('sitesettings')->get();
            //exit;
            if(!empty($sitesettings))
            {
            foreach($sitesettings as $each_sitesetting)
            {
              if($each_sitesetting->name == 'email')
              {
                $admin_users_email = $each_sitesetting->value;
              }
            }
            }

            if(!(empty($brandmembers)))
            {
                if($brandmembers->fname !='')
                $user_name = $brandmembers->fname.' '.$brandmembers->lname;
                else
                $user_name = $brandmembers->username;

                $user_email = $brandmembers->email;
                $resetpassword_link = url().'/member-reset-password/'.base64_encode($user_email).'-'.base64_encode($random_code);
                //echo $resetpassword_link; exit;
 				$sent = Mail::send('frontend.home.reset_password_link', array('name'=>$user_name,'email'=>$user_email,'reset_password_link'=>$resetpassword_link,'admin_users_email'=>$admin_users_email), 
                function($message) use ($admin_users_email, $user_email,$user_name)
                {
                    $message->from($admin_users_email);
                    $message->to($user_email, $user_name)->subject('Forgot Password Email!');
                });

                if( ! $sent) 
                {
                  Session::flash('error', 'something went wrong!! Mail not sent.'); 
                  return redirect('member-forgot-password');
                }
                else
                {
                  Session::flash('success', 'Please check your email to reset your password.'); 
                  return redirect('memberLogin');
                }              
            }
            else
            {
              Session::flash('error', 'Email Id not matched.'); 
              return redirect('member-forgot-password');
            }

        }
        
        return view('frontend.home.memberforgotpassword');
    }
  
    public function member_resetpassword($link=false)
    {
        if($link !='')
        {
            $link_arr = explode('-',$link);
            if(!empty($link_arr))
            {
                $user_email_en = $link_arr[0];     // encrypted email
                $user_code_en = $link_arr[1];     // encrypted code
            }
        }
          $user_email = base64_decode($user_email_en);
          $user_code = base64_decode($user_code_en);

        $code_has = DB::table('brandmembers')->where('code_number',$user_code)->first();
        if(count($code_has)>0)
        {

            if(Request::isMethod('post'))
            {
                $password = Request::input('password');
                $conf_pass = Request::input('con_password');
               // echo $password." - ".$conf_pass. "email= ".$user_email; exit;
                $update = DB::table('brandmembers')->where('email', $user_email)->update(array('password' => Hash::make($password),'code_number'=>''));
                
                if($update)
                {
                    Session::flash('success', 'Password successfully changed.'); 
                    return redirect('memberLogin');
                }
                else
                {
                    Session::flash('error', 'Password not changed.Somthing wrong!'); 
                    return redirect('memberLogin');
                }
                
            }
            return view('frontend.home.memberresetpassword',array('title'=>'Reset Password','link'=>$link));
        }
        else
        {
            Session::flash('error', 'This link already been used.Please use valid link.'); 
            return redirect('member-forgot-password');
        }
        

    }

 /**************************  MEMBER RESET PASSWORD END  **************************/
/**************************************  MEMBER FORGOT PASSWORD END ************************************/
/*
    public function brand_change_pass()
    { 
        $obj = new helpers();
        if(!$obj->checkBrandLogin()){
            return redirect('brandLogin');
        }
        if(Request::isMethod('post'))
        {

            if(!Session::has('brand_userid')){
                return redirect('brandLogin');
            }

           // print_r($_POST);exit;
          $old_password = Request::input('old_password');
          

          $password = Request::input('password');
          $conf_pass = Request::input('conf_pass');

          // Get Admin's password

          $user=Brandmember::find(Session::get('brand_userid'));
          

          if(Hash::check($old_password, $user['password']))
          {
            if($password!=$conf_pass){
              Session::flash('error', 'Password and confirm password is not matched.'); 
              return redirect('brandChangePass');

            }
            else{
              DB::table('brandmembers')->where('id', Session::get('brand_userid'))->update(array('password' => Hash::make($password)));
              
              Session::flash('success', 'Password successfully changed.'); 
              return redirect('brandChangePass');
            }
          }
          else{
            Session::flash('error', 'Old Password does not match.'); 
            return redirect('brandChangePass');
          }
        }

        return view('frontend.home.brandchangepassword',array('title' => 'Brand Change Password'));
    }
*/
    public function brand_login()
    {
	
	/*if( ! Request::secure() )
	{
	    return Redirect::secure( Request::path() );
	}*/
	
        $obj = new helpers();
        if($obj->checkMemberLogin()){
            return redirect('member-dashboard');
        }
	if($obj->checkBrandLogin()){
            return redirect('brand-dashboard');
        }
	   Session::put('member_type', 1);
	
        if(Request::isMethod('post'))
        {        	
            $email = Request::input('email');
            $password = Request::input('password');

            $users = DB::table('brandmembers')
            		 ->where('email', $email)
            		 ->where('role', 1)
            		 ->first();
            		// dd($users->id);
            if($users!=""){
            	if($users->subscription_status=="active")
            	{
	                $user_pass = $users->password;
	                // check for password
	                if(Hash::check($password, $user_pass)){

	                    // Check for active                 
	                    $user_cnt = DB::table('brandmembers')->where('email', $email)->where('status', 1)->count();

	                    if($user_cnt){
			    
			    
			                  $this->check_subscription($users);
			    
			    
	                         // Check for remember me
	                        if(Request::input('remember_me')==1){
	                            Cookie::queue(Cookie::make('brand_email', Request::input('email'), 60 * 24 * 30));
	                        }
	                        Session::put('brand_userid', $users->id);
	                        Session::put('brand_user_email', $users->email);
	                        Session::put('brand_username', ucfirst($users->username));
	                        return redirect('brand-dashboard');
	                    }
	                    else{
	                        Session::flash('error', 'Your Status is inactive. Contact Admin to activated your account'); 
	                        return redirect('brandLogin');
	                    }
	                }
	                else{
	                        Session::flash('error', 'Email and password does not match.'); 
	                        return redirect('brandLogin');
	                }
               }
               else
               {
               		//Session::flash('error', 'Your subscription expired. Please contact with admin.'); 
	                //return redirect('brandLogin');
	               // return view('frontend.home.expired',array('title'=>'MIRAMIX | Brand Login'));
               		//Session::flash('error', 'Email and password does not match.'); 
               //	dd($users->id);
	               // return redirect('loginexpire')->with('user', $users->id);
               		$user_id=$users->id;
               		$brand_email=$users->email;
               		//dd($brand_email);
               		$discontinue_product=Product::where('brandmember_id',$user_id)
               				 ->where('discountinue',1)
               				 ->where('visiblity',0)
               				 ->count();
               		$continue_product=Product::where('brandmember_id',$user_id)
               				 ->where('discountinue',0)
               				 ->where('visiblity',0)
               				 ->count();
               		$product=$discontinue_product+$continue_product;
               		//dd($product);
               		$show_var=0;
               	return view('frontend.home.expired',compact('show_var','user_id','brand_email','discontinue_product','continue_product','product'),array('title'=>'MIRAMIX | Brand Login'));
               }
            }
            else{
                    Session::flash('error', 'Email and password does not match.'); 
                    return redirect('brandLogin');
            }
        }
        // check for remenber me cookie
        $brand_email = '';
        $brand_email = Cookie::get('brand_email');
	$subfee = DB::table('sitesettings')->where('name','brand_fee')->first();
	$subprofee = DB::table('sitesettings')->where('name','brand_perproduct_fee')->first();
	
        return view('frontend.home.brand_login',compact('brand_email','subfee','subprofee'),array('title'=>'MIRAMIX | Brand Login'));
    }   

    /**************************************  BRAND FORGOT PASSWORD START ************************************/
                /**************************  BRAND RESET PASSWORD START  **************************/
    public function brand_forgotPassword()
    {

        $obj = new helpers();
        if($obj->checkUserLogin()){
            return redirect('brand-dashboard');
        }

        if(Request::isMethod('post'))
        {
            $email = Request::input('email');
            $brandmembers = DB::table('brandmembers')->where('email', '=', $email)->where('role', '=', 1)->first();
            $random_code = mt_rand();
            $updateWithCode = DB::table('brandmembers')->where('email', '=', $email)->update(array('code_number' => $random_code));
            $sitesettings = DB::table('sitesettings')->get();
            //exit;
            if(!empty($sitesettings))
            {
            foreach($sitesettings as $each_sitesetting)
            {
              if($each_sitesetting->name == 'email')
              {
                $admin_users_email = $each_sitesetting->value;
              }
            }
            }

            if(!(empty($brandmembers)))
            {
                if($brandmembers->fname !='')
                $user_name = $brandmembers->fname.' '.$brandmembers->lname;
                else
                $user_name = $brandmembers->business_name;

                $user_email = $brandmembers->email;
                $resetpassword_link = url().'/brand-reset-password/'.base64_encode($user_email).'-'.base64_encode($random_code);
                //echo $resetpassword_link; exit;
                $sent = Mail::send('frontend.home.reset_password_link', array('name'=>$user_name,'email'=>$user_email,'reset_password_link'=>$resetpassword_link,'admin_users_email'=>$admin_users_email), 
                function($message) use ($admin_users_email, $user_email,$user_name)
                {
                    $message->from($admin_users_email);
                    $message->to($user_email, $user_name)->subject('Forgot Password Email!');
                });

                if( ! $sent) 
                {
                  Session::flash('error', 'something went wrong!! Mail not sent.'); 
                  return redirect('brand-forgot-password');
                }
                else
                {
                  Session::flash('success', 'Please check your email to reset your password.'); 
                  return redirect('brandLogin');
                }              
            }
            else
            {
              Session::flash('error', 'Email Id not matched.'); 
              return redirect('brand-forgot-password');
            }

        }
        
        return view('frontend.home.brandforgotpassword');
    }
  
    public function brand_resetpassword($link=false)
    {
        if($link !='')
        {
            $link_arr = explode('-',$link);
            if(!empty($link_arr))
            {
                $user_email_en = $link_arr[0];     // encrypted email
                $user_code_en = $link_arr[1];     // encrypted code
            }
        }
          $user_email = base64_decode($user_email_en);
          $user_code = base64_decode($user_code_en);

        $code_has = DB::table('brandmembers')->where('code_number',$user_code)->first();
        if(count($code_has)>0)
        {

            if(Request::isMethod('post'))
            {
                $password = Request::input('password');
                $conf_pass = Request::input('con_password');
               // echo $password." - ".$conf_pass. "email= ".$user_email; exit;
                $update = DB::table('brandmembers')->where('email', $user_email)->update(array('password' => Hash::make($password),'code_number'=>''));

                
                if($update)
                {
                    Session::flash('success', 'Password successfully changed.'); 
                    return redirect('brandLogin');
                }
                else
                {
                    Session::flash('error', 'Password not changed.Somthing wrong!'); 
                    return redirect('brandLogin');
                }
            }
            return view('frontend.home.brandresetpassword',array('title'=>'Reset Password','link'=>$link));
        }
        else
        {
            Session::flash('error', 'This link already been used.Please use valid link.'); 
            return redirect('member-forgot-password');
        }
       
        

    }

            /**************************  BRAND RESET PASSWORD END  **************************/
/**************************************  BRAND FORGOT PASSWORD END ************************************/

/**************************************  MEMBER DASH-BOARD AND MEMBER ACCOUNT START ************************************/


   
   public function searchtags(){
     $obj = new helpers();
    $stags=array();
    $terms=Request::input('term');
    
    $tagtable=DB::table('searchtags')->orderBy('popularity', 'DESC')->get();
      foreach($tagtable as $tag){
	
	$whereArray = array('id'=>$tag->product_id,'is_deleted'=>1);
	$count=DB::table('products')->where($whereArray)->count();
	
	if($count>0){
	   DB::table('searchtags')->where("product_id","=",$tag->product_id)->delete();
	}
	
    }
    
     $tags = DB::table('searchtags')->where('name', 'LIKE', '%'.Request::input('term').'%')->groupBy('type')->orderBy('popularity', 'DESC')->get();
  
     $product_id='';
     foreach($tags as $tag){
	
	
	
    $stags[]=array("id"=>$tag->product_id,"value"=>$tag->name.'||'.$tag->type,"tags"=>$tag->type);
    
    }
    

    
    echo json_encode($stags);
   }
   
   public function newsletterajax(){
    $email=Input::get('newsemail');
   
    
     if(Session::has('member_userid'))
        {
            $member = Brandmember::find(Session::get('member_userid'));
	   		$newsletter=array("email"=>$email,"fname"=>$member->fname,"lname"=>$member->lname,"created_on"=>date("Y-m-d H:s:i"),"status"=>1);
	   		$subscriber = (($member->fname) =='')?$member->username:$member->fname;
	   		$lname = $member->lname;
        }  
        else if(Session::has('brand_userid'))
        {
          	$member = Brandmember::find(Session::get('brand_userid'));
	   		$newsletter=array("email"=>$email,"fname"=>$member->fname,"lname"=>$member->lname,"created_on"=>date("Y-m-d H:s:i"),"status"=>1);
	   		$subscriber = $member->fname;
	   		$lname = $member->lname;	   
        }
        else
        {
	  		$newsletter=array("email"=>$email,"fname"=>'guest',"lname"=>'guest',"created_on"=>date("Y-m-d H:s:i"),"status"=>1); 
	  		$subscriber = 'subscriber';
            $lname = '';
		}

	$setting = DB::table('sitesettings')->where('name', 'email')->first();
	$admin_users_email=$setting->value;

    $countexists = DB::table('newsletter_subscription')->where('email', '=', $email)->count();
    if($countexists>0){
	    $result=array("message"=>"You have already subscribed.","status"=>"fail");
	    
	
	}
	else
	{
	   	$sitesettings = DB::table('sitesettings')->get();
        $all_sitesetting = array();
        foreach($sitesettings as $each_sitesetting)
        {
            $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
        }
		    //print_r($all_sitesetting); exit;
		    $merge_vars=array(
		    'OPTIN_IP'=>$_SERVER['REMOTE_ADDR'], // Use their IP (if avail)
		    'OPTIN-TIME'=>"now", // Must be something readable by strtotime...
		    'FNAME'=>ucwords(strtolower(trim($subscriber))),
		    'LNAME'=>ucwords(strtolower(trim($lname))),
		    'COMPANY'=>ucwords(strtolower(trim(""))),
		    'ORGTYPE'=>ucwords(strtolower(trim(""))),
		    'PLANNING'=>strtolower(trim("Unknown")),
		    );

		$send_data=array(
		    'email'=>array('email'=>$email),
		    'apikey'=>$all_sitesetting['mailchimp_api_key'],	//"aec02fd1bae4d9acb046576e1983a945-us8", // Your Key
		    'id'=>$all_sitesetting['mailchimp_list_id'],		//"5c949c1384", // Your proper List ID
		    'merge_vars'=>$merge_vars,
		    'double_optin'=>false,
		    //'update_existing'=>true,
		    //'replace_interests'=>false,
		    'send_welcome'=>false,
		    'email_type'=>"html",
		);

		$payload=json_encode($send_data);
		//$submit_url="https://api.mailchimp.com/2.0/lists/subscribe.json";
		$submit_url=$all_sitesetting['mailchimp_url']; //"https://us8.api.mailchimp.com/2.0/lists/subscribe.json";
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$submit_url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$payload);
		$result=curl_exec($ch);
		curl_close($ch);
		$mcdata=json_decode($result);

		if (empty($mcdata->error)) {
		     $newsletterres= Newsletter::create($newsletter);
		    $result=array("message"=>"You have successfully subscribed.","status"=>"success");
		}else{
			echo $mcdata->error;
			$result=array("message"=>"Newsletter subscription fails.","status"=>"fail");
		}
		
	    // $sent = Mail::send('frontend.home.newsletter', array('admin_users_email'=>$admin_users_email,'subscriber'=>$subscriber), 
     //        function($message) use ($admin_users_email,$email)
     //        {
     //            $message->from($admin_users_email);  //support mail
     //            $message->to($email)->subject('Miramix Subscription Mail');
     //        });
	}
    
     echo json_encode($result);
   }
   
   public function show404(){
    
    echo 'Page not found';
   }

   public function tagPopularity()
   {
        $tag_name = Input::get('tag');
        $tagpopularity = DB::table('searchtags')
                            ->where('name', 'like', '%'.$tag_name)->get();
           //echo "<pre>";print_r($tagpopularity); exit;                 
        if(!empty($tagpopularity))
        {
            foreach($tagpopularity as $eachtag)
            {
                //echo $eachtag->popularity; 
                $popularity =$eachtag->popularity;
                $popularity = $popularity+1;
                $update_tagpopularity = DB::table('searchtags')
                                            ->where('id', $eachtag->id)
                                            ->update(['popularity' => $popularity]);
            }
        }
        echo 1;
        exit;
   }
public function errorpage(){
    
  return view('frontend.home.error',array('title'=>'Something went wrong'));   
}

/*
public function facebook_redirect(){
   
    return Socialize::with('facebook')->redirect();
}*/
//social login in ajax
public function facebook(){
     $obj = new helpers();
    $name = Request::input('name');
  
    $email=(Request::input('email'));
    $checkout=(Request::input('checkout'));
    $id=(Request::input('id'));
    
    $name=explode(" ",$name);
    if(count($name)>0){
	$fname=$name[0];
	$lname=end($name);
    }else{
	$fname=$name; 
	$lname=$name;
    }
    $username=strtolower($fname);
    $count = DB::table('brandmembers')->where('email', $email)->count();
    
    if($count>0){
	$membertype=Session::get('member_type');
	 
		    
      
	$member=DB::table('brandmembers')->where('email', $email)->first();
	
	//for brand user
	if($member->role==1 && empty($checkout) && $membertype=='1'){
	    
	    //redirect if not activated
	    
	    $user_cnt = DB::table('brandmembers')->where('email', $email)->where('status', 1)->where('admin_status', 1)->count();
	 
	  if($user_cnt<=0){
			$site = DB::table('sitesettings')->where('name','email')->first();
                        Session::put('error', 'Your Status is inactive. Contact Admin at '.$site->value.' to get your account activated!'); 
                        echo url().'/brandLogin';
			exit;
                    }
      
	    $this->check_subscription($member);
	    
	    Session::put('brand_userid', $member->id);
	    Session::put('brand_user_email', $member->email);
	    Session::put('member_username', $member->username);
	    Session::put('social_login', 1);
        echo url()."/brand-dashboard";
	}
	elseif($member->role==1 && $membertype!='1'){
	    Session::put('error', 'You are already registered as brand!'); 
	    echo url().'/brandLogin';
	}
	elseif($member->role==1 && !empty($checkout)){
	    Session::put('error', 'You are unable to login as brand!'); 
	    echo url();
	}
	elseif($member->role==0 && $membertype=='1'){
	    Session::put('error', 'You are already registered as member!'); 
	     echo url().'/memberLogin';
			
	} 
	elseif($member->role==0 && ($membertype=='0' || !empty($checkout))){
	   //for member user 
	    
	    //redirect if admin deactivated account
	    $user_cnt = DB::table('brandmembers')->where('email', $email)->where('status', 1)->where('admin_status', 1)->count();
	 
	  if($user_cnt<=0){
			$site = DB::table('sitesettings')->where('name','email')->first();
                        Session::put('error', 'Your Status is inactive. Contact Admin at '.$site->value.' to get your account activated!'); 
                        echo url().'/memberLogin';
			exit;
                    }
		    
         
	    Session::put('member_userid', $member->id);
	    Session::put('member_user_email', $member->email);
	    Session::put('member_username', $member->username);
	    Session::put('social_login', 1);
	      
	     $this->update_cart($member->id);
	    
	    
	    if(!empty($checkout)){
		echo url()."/checkout";
		
	    }else{
		
      
	    echo url()."/member-dashboard";
	    }
	   
	    
	}
	
    }else{
	//create social users
	
	$hashpassword = Hash::make(uniqid());
	$slug=$obj->create_slug($fname."-".$lname,'brandmembers','slug');
	$checkout=(Request::input('checkout'));
	if(!empty($checkout)){
	    
	   Session::put('member_type', 0);
	}
	$membertype=Session::get('member_type');
	
	
	if($membertype=='1'){
	    
	     Session::put('error', 'Please register as brand for login!'); 
                        echo url(). '/brandregister';
			exit;
	}
	//echo $membertype;exit;
	
        $brandmember= Brandmember::create([
            'email'             => $email,
	    'fname'             => $fname,
	    'lname'             => $lname,
            'username'          => $username,
            'password'          => '',
            'role'              => Session::get('member_type'),                   // for member role is "0"
            'admin_status'      => 1,
	    'status'		=> 1,
	    'facebook_id'	=>$id,// Admin status
	    'slug'		=>$slug,
	    'business_name'	=>$fname." ".$lname,
            'updated_at'        => date('Y-m-d H:i:s'),
            'created_at'        => date('Y-m-d H:i:s')
        ]);
	
	$member=DB::table('brandmembers')->where('email', $email)->first();
	
			$reg_brand_id = $member->id; 
			$address = New Address;
			$address->mem_brand_id = $reg_brand_id;
			$address->first_name = $fname;
			$address->last_name = $lname;
			$address->address = '';
			$address->address2 = '';
			$address->country_id = '';
			$address->zone_id =  ''; // State id
			$address->city = '';
			$address->postcode =  '';
			$address->serialize_val =  '';
			
			if($address->save()) 
			{
				$addressId = $address->id;
				$dataUpdateAddress = DB::table('brandmembers')
					->where('id', $reg_brand_id)
					->update(['address' => $addressId]);
			}
			
	if($member->role==1){
	    $this->check_subscription($member);
	    
	    Session::put('brand_userid', $member->id);
	    Session::put('brand_user_email', $member->email);
	    Session::put('member_username', $member->username);
	    Session::put('social_login', 1);
	   
	    
        echo url()."/brand-dashboard";
	}else{
	    Session::put('member_userid', $member->id);
	    Session::put('member_user_email', $member->email);
	    Session::put('member_username', $member->username);
	    Session::put('social_login', 1);
	    $this->update_cart($member->id);
	    
	     if(!empty($checkout)){
		echo url()."/checkout";
		
	    }else{
	    
	   echo url()."/member-dashboard";
	    }
	    
	}
	
    }
    
}

/*
public function google_redirect(){
   
    return Socialize::with('google')->redirect();
}*/
//social login in ajax
public function google(){
     $obj = new helpers();
    $name = Request::input('name');;
    $checkout=(Request::input('checkout'));
    $email=( Request::input('email'));
    $membertype=Session::get('member_type');
    $name=explode(" ",$name);
    if(count($name)>0){
	$fname=$name[0];
	$lname=end($name);
    }else{
	$fname= $name; 
	$lname= $name;
    }
    $username=strtolower($fname);
    $count = DB::table('brandmembers')->where('email', $email)->count();
    
    if($count>0){
	$member=DB::table('brandmembers')->where('email', $email)->first();
	//brand member
	if($member->role==1  && empty($checkout) && $membertype=='1'){
	    
	       //redirect if admin deactivated account
	    $user_cnt = DB::table('brandmembers')->where('email', $email)->where('status', 1)->where('admin_status', 1)->count();
	 
	  if($user_cnt<=0){
			$site = DB::table('sitesettings')->where('name','email')->first();
                        Session::put('error', 'Your Status is inactive. Contact Admin at '.$site->value.' to get your account activated!'); 
                        echo url().'/brandLogin';
			exit;
                    }
		    
	    $this->check_subscription($member);
	    
	    Session::put('brand_userid', $member->id);
	    Session::put('brand_user_email', $member->email);
	    Session::put('member_username', $member->username);
	    Session::put('social_login', 1);
       echo url()."/brand-dashboard";
	}
	elseif($member->role==1 && $membertype!='1'){
	    Session::put('error', 'You are already registered as brand!'); 
	    echo url().'/brandLogin';
	}
	elseif($member->role==1 && !empty($checkout)){
	    Session::put('error', 'You are unable to login as brand!'); 
	    echo url();
	}
	elseif($member->role==0 && $membertype=='1'){
	    Session::put('error', 'You are already registered as member!'); 
	     echo url().'/memberLogin';
			exit;
	}
	
	elseif($member->role==0 && ($membertype=='0' || !empty($checkout))){
	    
	       //redirect if admin deactivated account
	    $user_cnt = DB::table('brandmembers')->where('email', $email)->where('status', 1)->where('admin_status', 1)->count();
	 
	  if($user_cnt<=0){
			$site = DB::table('sitesettings')->where('name','email')->first();
                        Session::put('error', 'Your Status is inactive. Contact Admin at '.$site->value.' to get your account activated!'); 
                        echo url().'/memberLogin';
			exit;
                    }
		    
	    Session::put('member_userid', $member->id);
	    Session::put('member_user_email', $member->email);
	    Session::put('member_username', $member->username);
	    Session::put('social_login', 1);
	    
	     $this->update_cart($member->id);
	     
	     if(!empty($checkout)){
		echo url()."/checkout";
		
	    }else{
		echo url()."/member-dashboard";
	    }
	    
	   
	    
	    
	}
	
    }else{
	
	//create social users
	$checkout=(Request::input('checkout'));
	if(!empty($checkout)){
	    
	   Session::put('member_type', 0);
	}
	
	$membertype=Session::get('member_type');
	
	if($membertype=='1'){
	    
	     Session::put('error', 'Please register as brand for login!'); 
                        echo url().'/brandregister';
			exit;
	}
	
	$hashpassword = Hash::make(uniqid());
	$slug=$obj->create_slug($fname."-".$lname,'brandmembers','slug');
        $brandmember= Brandmember::create([
            'email'             => $email,
	    'fname'             => $fname,
	    'lname'             => $lname,
            'username'          => $username,
            'password'          => '',
            'role'              => Session::get('member_type'),                   // for member role is "0"
            'admin_status'      => 1,                   // Admin status
	    'status'		=> 1,
	    'google_id'		=>Request::input('id'),
	    'slug'		=>$slug,
	    'business_name'	=>$fname." ".$lname,
            'updated_at'        => date('Y-m-d H:i:s'),
            'created_at'        => date('Y-m-d H:i:s')
        ]);
	
	$member=DB::table('brandmembers')->where('email', $email)->first();
	
	
	$reg_brand_id = $member->id; 
			$address = New Address;
			$address->mem_brand_id = $reg_brand_id;
			$address->first_name = $fname;
			$address->last_name = $lname;
			$address->address = '';
			$address->address2 = '';
			$address->country_id = '';
			$address->zone_id =  ''; // State id
			$address->city = '';
			$address->postcode =  '';
			$address->serialize_val =  '';
			
			if($address->save()) 
			{
				$addressId = $address->id;
				$dataUpdateAddress = DB::table('brandmembers')
					->where('id', $reg_brand_id)
					->update(['address' => $addressId]);
			}
	
	if($member->role==1){
	    $this->check_subscription($member);
	    
	    Session::put('brand_userid', $member->id);
	    Session::put('brand_user_email', $member->email);
	    Session::put('member_username', $member->username);
	    Session::put('social_login', 1);
	    echo url()."/brand-dashboard";
	}else{
	    Session::put('member_userid', $member->id);
	    Session::put('member_user_email', $member->email);
	    Session::put('member_username', $member->username);
	    Session::put('social_login', 1);
	     $this->update_cart($member->id);
	     if(!empty($checkout)){
		echo url()."/checkout";
		
	    }else{
		echo url()."/member-dashboard";
	    }
	   
	    
	    
	}
	
    }
   
    
}


private function check_subscription($users){
    
     /******************  check subscription **************************/
		    
		    
		    $subscription = DB::table('subscription_history')->where('member_id', $users->id)->orderBy('subscription_id','DESC')->first();
		    if(count($subscription)<=0){
			
			$end_date=date("Y-m-d",strtotime($users->created_at .' + 30 days'));
			$setting = DB::table('sitesettings')->where('name', 'brand_fee')->first();
			
			$subdata=array("member_id"=>$users->id,"start_date"=>$users->created_at,"end_date"=>$end_date,"subscription_fee"=>$setting->value);
			Subscription::create($subdata);
			
		    }else{
			
			$today=Date('Y-m-d');
			$enddate=date("Y-m-d",strtotime($subscription->end_date." + 1 day"));
			
			if($today>$enddate && $subscription->payment_status=='pending'){
			    
			    Session::put('error', 'Your subscription has expired. Contact Admin to activated your account'); 
			   // return redirect('brandLogin');
			     $updateWithCode = DB::table('brandmembers')->where('id', '=', $users->id)->update(array('subscription_status' => 'expired'));
			     echo url().'/brandLogin';exit;
			    
			}
			elseif( $users->subscription_status!='active'){
			     Session::put('error', 'Your subscription has expired. Contact Admin to activated your account');
			      echo url().'/brandLogin';exit;
			}
			else{
			   $updateWithCode = DB::table('brandmembers')->where('id', '=', $users->id)->update(array('subscription_status' => 'active'));  
			    
			}
			
			
		    }
		    
		    /******************  check subscription **************************/
}

public function show(){
    return redirect('home');
}

public function login_expire()
{
	$brand_email = '';
	$brand_email = Cookie::get('brand_email');
	$subfee = DB::table('sitesettings')->where('name','brand_fee')->first();
	$subprofee = DB::table('sitesettings')->where('name','brand_perproduct_fee')->first();
	$brandmembers = Brandmember::where('role',1)
					->where('status',1)
					->where('subscription_status','expired')
					->orderBy('fname','ASC')
					->get();
	//dd($user);

	return view('frontend.home.expired',compact('brand_email','subfee','subprofee','brandmembers'),array('title'=>'MIRAMIX | Brand Login'));

}

public function renew(){	  
    $total_price=Request::get('total_price');	   

	$sitesettings = DB::table('sitesettings')->get();
    $all_sitesetting = array();
    foreach($sitesettings as $each_sitesetting)
    {
        $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
    }
    
    $admin_users_email = $all_sitesetting['email']; // Admin Support Email
    $payment_method =  $all_sitesetting['payment_mode'];

    if($payment_method == 'test')
    {
        $authorize_url = $all_sitesetting['authorize.net_url_test'];    
        $authorize_login_key = $all_sitesetting['authorize.net_login_key_test'];
        $authorize_transaction_key = $all_sitesetting['authorize.net_transaction_key_test'];
    }
    elseif($payment_method == 'live')
    {
        $authorize_url = $all_sitesetting['authorize.net_url_live'];    
        $authorize_login_key = $all_sitesetting['authorize.net_login_key_live'];
        $authorize_transaction_key = $all_sitesetting['authorize.net_transaction_key_live'];
    }

    /* Order details for perticular order id */
    

        $post_url = $authorize_url; //"https://test.authorize.net/gateway/transact.dll";

        $post_values = array(
        
        // the API Login ID and Transaction Key must be replaced with valid values
        "x_login"           => $authorize_login_key,        //"2BPuf2X4wmn",
        "x_tran_key"        => $authorize_transaction_key, //"7kR5A9k8xa8F9ztz",
        "x_version"         => "3.1",
        "x_delim_data"      => "TRUE",
        "x_delim_char"      => "|",
        "x_relay_response"  => "FALSE",
        "x_type"            => "AUTH_CAPTURE",
        "x_method"          => "CC",
        "x_trans_id"        => uniqid(),
        "x_card_num"        => Request::get('card_number'), //"4042760173301988", $card_number
        "x_exp_date"        => Request::get('card_exp_month').Request::get('card_exp_year'),                //$card_exp_month.$card_exp_year 
        "x_amount"          => $total_price,
        "x_description"     => "Miramix Membership"
        
    );
    //echo "<pre>"; print_r($post_values); exit;
    // This section takes the input fields and converts them to the proper format
    // for an http post.  For example: "x_login=username&x_tran_key=a1B2c3D4"
    $post_string = "";
    foreach( $post_values as $key => $value )
        { $post_string .= "$key=" . urlencode( $value ) . "&"; }
    $post_string = rtrim( $post_string, "& " );

    $request = curl_init($post_url); // initiate curl object
        curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
        curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
        $post_response = curl_exec($request); // execute curl post and store results in $post_response
        
        curl_close ($request); // close curl object

    // This line takes the response and breaks it into an array using the specified delimiting character
    $response_array = explode($post_values["x_delim_char"],$post_response);

    $discontinue_product=Request::get('discontinue_product');
	$continue_product=Request::get('continue_product');
	$product=Request::get('product');
	$user_id=Request::get('member_id');
	$brand_email=Request::get('brandemail');
	//echo Request::get('discontinue_product');
	//dd(Request::all());
	//echo $discontinue_product."<br>";
	//echo $continue_product."<br>";
	//echo $product."<br>";
	//echo $user_id."<br>";
	//echo $brand_email."<br>";
	//exit;

   //echo "<pre>"; print_r($response_array); exit;
    if($response_array[0] == ''){
    	Session::flash('error', 'Something is wrong here! '.$response_array[3]);	       
    	$show_var=0;
    	//return redirect('/loginexpire');  
    	//return Redirect::to('/loginexpire')->with('discontinue_product', 'Login Failed');   	
    	return view('frontend.home.expired',compact('show_var','brand_email','discontinue_product','continue_product','product','user_id'));
         
    }
    else if($response_array[0] == 1){
    	$member_id = Request::input('member_id');
    	$total_month = Request::input('total_month');
    	$total_price = Request::input('total_price');
    	
    	$subscriptions = Subscription::where('member_id',$member_id)->first();
    	//dd($subscriptions->start_date);

    	$subscriptionhistory = new Subscriptionhistory();
    	$subscriptionhistory->member_id=$subscriptions->member_id;
    	$subscriptionhistory->start_date=$subscriptions->start_date;
    	$subscriptionhistory->end_date=$subscriptions->end_date;
    	$subscriptionhistory->subscription_fee=$subscriptions->subscription_fee;
    	$subscriptionhistory->other_fee=$subscriptions->other_fee;
    	$subscriptionhistory->payment_status=$subscriptions->payment_status;
    	$subscriptionhistory->transaction_id=$subscriptions->transaction_id;
    	if($subscriptionhistory->save())
    	{
    		$start_date=date("Y-m-d");
    		$time = strtotime($start_date);
			$end_date = date("Y-m-d", strtotime("+".$total_month." month", $time));
    	
    		Subscription::where('member_id', $member_id)		          
	          ->update(['start_date' => $start_date,
	          			'end_date' => $end_date,
	          			'end_date' => $end_date,
	          			'subscription_fee' => $total_price,		          			
	          			'payment_status' => 'paid',
	          			'transaction_id' => $response_array[6]]);

	        Brandmember::where('id', $member_id)		          
	          ->update(['subscription_status' => 'active']);
    	}

    	Session::flash('success', 'You have successfully renew membership.');        
		$show_var=1;
    	//return redirect('/loginexpire');     	
    	//return Redirect::to('/loginexpire')->with('discontinue_product', 'Login Failed');
    	return view('frontend.home.expired',compact('show_var','brand_email','discontinue_product','continue_product','product','user_id'));
    }
    else
    {
    	Session::flash('error', 'Something is wrong here! '.$response_array[3]);	       
    	$show_var=0;
    	//return redirect('/loginexpire');     	
    	//return Redirect::to('/loginexpire')->with('discontinue_product', 'Login Failed');
    	return view('frontend.home.expired',compact('show_var','brand_email','discontinue_product','continue_product','product','user_id'));
    }



    
}

	public function contactadmin($id)
	{
		$users=Brandmember::where('id',$id)->first();
		
		$sitesettings = DB::table('sitesettings')->get();
        //exit;
        if(!empty($sitesettings))
        {
        foreach($sitesettings as $each_sitesetting)
        {
          if($each_sitesetting->name == 'email')
          {
            $admin_users_email = $each_sitesetting->value;
          }
        }
        }
       // dd($admin_users_email);
		//$admin_users_email='uuu4@mailinator.com';
		$user_email=$users->email;
		$user_name=$users->username;
		/*$sent = Mail::send('frontend.home.email', array('to_email'=>$to_email,'from_email'=>$from_email,'user_name'=>$user_name), 
        function($message) use ($to_email,$from_email,$user_name)
        {
            $message->from($from_email);
            $message->to($to_email, 'Admin')->subject('A user wants to active account');
        });*/
		$sent = Mail::send('frontend.home.email', array('name'=>$user_name,'email'=>$user_email,'admin_users_email'=>$admin_users_email), 
        function($message) use ($admin_users_email, $user_email,$user_name)
        {
            $message->from($user_email);
            $message->to($admin_users_email, 'admin')->subject('A user wants to active account');
        });
        if($sent)
        {
        	echo "done";
        }
        else
        {
        	echo "not";
        }		
	}

	public function test($ord_id) {
		

		$order_list = DB::table('orders')
			        ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
			        ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name','order_items.brand_email', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name')
			        ->where('orders.id','=',$ord_id)
			        ->get();
		

		$this->sendCommunication($order_list);
	}

}