<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/

use App\Model\Brandmember; /* Model name*/
use App\Model\Ingredient; /* Model name*/
use App\Model\Coupon; /* Model name*/
use Illuminate\Support\Collection;
use App\Http\Requests;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Request;

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
use Redirect;
use App\Helper\helpers;
//use Anam\Phpcart\Cart;
use Cart;


class CartController extends BaseController {

    public function __construct() 
    {
        parent::__construct();
        $obj = new helpers();
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
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        
    }

    public function cart2()
        {
            //Cart::remove("76c580d3655de904428445212b864688");
            //echo 1; //exit;
           
		    // Cart::destroy(); 

      //   echo Cart::count();
              $content = Cart::content();
              echo Cart::total();
             echo "<pre>";print_r($content);
    }

    public function cart()
    {
        $obj = new helpers();
        $product_id = Input::get('product_id');
        $quantity = Input::get('quantity');
        $product_name = Input::get('product_name');
        $amount = Input::get('amount');
        $duration = Input::get('duration');
        $no_of_days = Input::get('no_of_days');
        $form_factor = Input::get('form_factor');


        // First check if brand is purchasing the product
        // if so get the min price and if not available get the actual price

        /*if(Session::has('brand_userid'))
        {
            $prod_prices = DB::table('product_formfactors')
                        ->leftJoin('products', 'products.id', '=', 'product_formfactors.product_id')
                        ->leftJoin('product_formfactor_durations', 'product_formfactors.id', '=', 'product_formfactor_durations.product_formfactor_id')
                        ->where('product_id', $product_id)
                        ->where('formfactor_id', $form_factor)
                        ->select('products.brandmember_id', 'product_formfactor_durations.min_price', 'product_formfactor_durations.actual_price')
                        ->first();

            if(($prod_prices->brandmember_id == Session::get('brand_userid')) && floatval($prod_prices->min_price) > 0)
            {
                $amount = $prod_prices->min_price;
            }

    
        }*/
        


        
		$res=Cart::add($product_id, $product_name, $quantity, $amount, array('duration' => $duration,'no_of_days'=>$no_of_days,'form_factor'=>$form_factor));
		$content = Cart::content();
		//dd($content);
		//echo $content->rowid;
		
		foreach($content as $eachcontentCart)
		{
			$cartRowId = $eachcontentCart->rowid;
            $sub_total = $eachcontentCart->subtotal;
		}
		
        if($obj->checkMemberLogin())
        {
            $cartContent = DB::table('carts')
                                ->where('user_id',Session::get('member_userid'))
                                ->where('product_id',$product_id)
                                ->where('no_of_days',$no_of_days)
                                ->where('form_factor',$form_factor)
                                ->first();

            if(count($cartContent)<1)
            {
                $insert_cart = DB::table('carts')->insert(['user_id' => Session::get('member_userid'), 'row_id' => $cartRowId, 'product_id' => $product_id , 'product_name' => $product_name, 'quantity' => $quantity, 'amount' => $amount, 'duration' => $duration, 'no_of_days' => $no_of_days, 'form_factor' => $form_factor,'sub_total' => $sub_total, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
            }
            else
            {
                $new_quantity = ($cartContent->quantity)+$quantity;
                $new_sub_total = $new_quantity * $amount;
                $update_cart = DB::table('carts')
                                    ->where('cart_id', $cartContent->cart_id)
                                    ->update(['quantity' => $new_quantity,'sub_total'=>$new_sub_total]);
            }
            
        }
        else if($obj->checkBrandLogin())
        {
            $cartContent = DB::table('carts')
                                ->where('user_id',Session::get('brand_userid'))
                                ->where('product_id',$product_id)
                                ->where('no_of_days',$no_of_days)
                                ->where('form_factor',$form_factor)
                                ->first();

            if(count($cartContent)<1)
            {
                $insert_cart = DB::table('carts')->insert(['user_id' => Session::get('brand_userid'), 'row_id' => $cartRowId, 'product_id' => $product_id , 'product_name' => $product_name, 'quantity' => $quantity, 'amount' => $amount, 'duration' => $duration, 'no_of_days' => $no_of_days, 'form_factor' => $form_factor,'sub_total' => $sub_total, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
            }
            else
            {
                $new_quantity = ($cartContent->quantity)+$quantity;
                $new_sub_total = $new_quantity * $amount;
                $update_cart = DB::table('carts')
                                    ->where('cart_id', $cartContent->cart_id)
                                    ->update(['quantity' => $new_quantity,'sub_total'=>$new_sub_total]);
            }
            
        }

		//$str = Cart::count();
        $str = count($content);
		echo $str; 
    }

   function reorder()
   {
        $obj = new helpers();
        if((!$obj->checkMemberLogin()) && ($obj->checkBrandLogin()))
        {
            return redirect('home');
        }

        $order_id = Input::get('order_id');

        /* Order details for perticular order id */
        $order_list = DB::table('orders')
                    ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                    ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name','order_items.brand_email', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name', 'order_items.duration', 'order_items.no_of_days')
                    ->where('orders.id','=',$order_id)
                    ->get();

        //echo "<pre>";print_r($order_list); exit;
        foreach($order_list as $eachorder)
        {
            Cart::add($eachorder->product_id, $eachorder->product_name, $eachorder->quantity, $eachorder->price, array('duration' => $eachorder->duration,'no_of_days'=>$eachorder->no_of_days,'form_factor'=>$eachorder->form_factor_id)); 

        }

        DB::table('carts')->where("user_id",Session::get('member_userid'))->delete();   // delete All cart of logged in member.
        $content = Cart::content(); 
        //print_r($content);
        
        foreach($content as $eachcontentCart)
        {
            $cartContent = DB::table('carts')
                                ->where('user_id',Session::get('member_userid'))
                                ->where('product_id',$eachcontentCart->id)
                                ->where('no_of_days',$eachcontentCart->options->no_of_days)
                                ->where('form_factor',$eachcontentCart->options->form_factor)
                                ->first();
            

            if(count($cartContent)<1)
            {
                $insert_cart = DB::table('carts')->insert(['user_id' => Session::get('member_userid'), 'row_id' => $eachcontentCart->rowid, 'product_id' => $eachcontentCart->id , 'product_name' => $eachcontentCart->name, 'quantity' => $eachcontentCart->qty, 'amount' => $eachcontentCart->price, 'duration' => $eachcontentCart->options->duration, 'no_of_days' => $eachcontentCart->options->no_of_days, 'form_factor' => $eachcontentCart->options->form_factor,'sub_total' => $eachcontentCart->subtotal, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
            }
            else
            {
                $new_quantity = ($cartContent->quantity)+$eachcontentCart->qty;
                $new_sub_total = $new_quantity * $eachcontentCart->price;
                $update_cart = DB::table('carts')
                                    ->where('cart_id', $cartContent->cart_id)
                                    ->update(['quantity' => $new_quantity,'sub_total'=>$new_sub_total]);
            }
            
        }
        $str = Cart::count();
        echo $str; 
   } 

    public function showAllCart()
    {

        
        /*----- Only Member Will Access This Page ----*/
        $obj = new helpers();
      
        //$content = Cart::content();
        $content = $obj->content();
        //dd($content);
        /* Site Setting Start */
        $sitesettings = DB::table('sitesettings')->get();
        $all_sitesetting = array();
        foreach($sitesettings as $each_sitesetting)
        {
            $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
        }

        
        $brand_userid = "";
        if(Session::has('brand_userid'))
        {
            // Brand has logged in, check if eligible for 
            // wholesale_order_threshold

            $wholesale_order_threshold_quantity = $all_sitesetting['wholesale_order_threshold_quantity'];
            $wholesale_order_threshold_amount = $all_sitesetting['wholesale_order_threshold_amount'];
            
            // Logged in brand user id
            $brand_userid = Session::get('brand_userid');

            
        }



        $share_discount = '';

        // Wholesale Orders Starts
        $brand_tot_wholesale_qty = 0;
        $brand_tot_wholesale_amount = 0;
        $is_eligible_for_wholesale_discount = 0;
        $total_amount = 0;



        foreach($content as $each_content)
        {
            
            $product_res = DB::table('products')->where('id',$each_content->id)->first();
           // echo $each_content->brandmember_id; exi
            $brandmember = DB::table('products')
                                ->leftJoin('brandmembers', 'brandmembers.id', '=', 'products.brandmember_id')

                                ->select('products.*', 'brandmembers.business_name', 'brandmembers.slug', 'brandmembers.pro_image', 'brandmembers.brand_details', 'brandmembers.brand_sitelink', 'brandmembers.status', 'brandmembers.admin_status')

                                ->where('products.id','=',$each_content->id)
                                ->first();
                                //echo "<pre>";print_r($brandmember); 
                                //echo $brandmember->slug ; exit;
            //$brand_name = ($brandmember->fname)?($brandmember->fname.' '.$brandmember->lname):$brandmember->username;

            $brand_name = $brandmember->business_name;


            $brand_id = $brandmember->brandmember_id;
            $formfactor = DB::table('form_factors')->where('id','=',$each_content->options->form_factor)->first();
            $formfactor_name = $formfactor->name;
            $formfactor_id = $formfactor->id;

            //echo $brand_userid." => ".$brand_id."<br>";

            // Chekc if each product is for logged in brand
            if(isset($brand_userid) && $brand_userid == $brand_id)
            {
                $brand_tot_wholesale_qty += $each_content->qty;
            }

            if(isset($brand_userid) && $brand_userid == $brand_id)
            {
                $brand_tot_wholesale_amount += $each_content->subtotal;
            }

	
    
	        /* Discount Share Start */
	        if(Session::has('share_product_id'))
	        {
                $pid = array();
	        	$pid=Session::get('share_product_id');
				if(in_array($each_content->id,$pid) )
				{
					$share_discount = $all_sitesetting['discount_share'];
				}
		    }
		    if(Session::get('force_social_share')!='') // For cart page share
		    {
		    	$share_discount = $all_sitesetting['discount_share'];
		    } 



	        /* Discount Share End */

            $cart_result[] = array('rowid'=>$each_content->rowid,
            	'product_id'=>$each_content->id,
                'product_name'=>$each_content->name,
                'product_slug'=>$brandmember->product_slug,
                'product_image'=>$product_res->image1,
                'qty'=>$each_content->qty,
                'price'=>$each_content->price,
                'duration'=>$each_content->options->duration,
                'formfactor_name'=>$formfactor_name,
                'formfactor_id'=>$formfactor_id,
                'brand_name'=>$brand_name,
                'brand_id'=>$brand_id,
                'brand_slug'=>$brandmember->slug,
                'subtotal'=>$each_content->subtotal);


            $total_amount += $each_content->subtotal;
           
        } // end of loop for each product

        

        if($brand_tot_wholesale_amount > 0)
            Session::put('wholesale_total_amount', $brand_tot_wholesale_amount);
        
        Session::put('regular_total_amount', $total_amount);
        
	
    	$cartcontent = Cart::content();

        //dd($cartcontent);

    	$member=array();
    	$redemctrl=array("min"=>5,"max"=>100,"step"=>5);
    	if(Session::has('member_userid')){
        	$member =DB::table('brandmembers')->where("id",Session::get('member_userid'))->first();
        	$setting_point = DB::table('sitesettings')->where('name','points_for_price')->first();
        	$step=$member->user_points;
        	
        	$redemctrl=array("min"=>$setting_point->value,"max"=>$step,"step"=>$setting_point->value);
        	
    	}
        else if(Session::has('brand_userid')){
            $member =DB::table('brandmembers')->where("id",Session::get('brand_userid'))->first();
            $setting_point = DB::table('sitesettings')->where('name','points_for_price')->first();
            $step=$member->user_points;


            $redemctrl=array("min"=>$setting_point->value,"max"=>$step,"step"=>$setting_point->value);
            
        }

        // Wholesale Orders Continues
        // Check if the brand is eligible for discount
        // if the total purchased quantity of own product is more than equals to the threshold quantity
        // Or if total value of purchased products is more than equals to the threshold amount

        if(isset($cart_result))
        {
            if((isset($wholesale_order_threshold_quantity) && $brand_tot_wholesale_qty >= $wholesale_order_threshold_quantity) 
                || (isset($wholesale_order_threshold_amount) && $brand_tot_wholesale_amount >= $wholesale_order_threshold_amount))
            {
                $is_eligible_for_wholesale_discount = 1;

                // Flag the eligible products so they do not get added up in total price in cart
                
                for($p = 0; $p < count($cart_result); $p++) 
                {
                    //if(isset($brand_userid) && $brand_userid == $brand_id)
                    if($cart_result[$p]['brand_id'] == $brand_userid)
                        $cart_result[$p]['is_wholesale'] = 1;
                        
                    else
                        $cart_result[$p]['is_wholesale'] = 0;


                    // Update the cart field
                    $rowid = $cart_result[$p]['rowid'];
                    

                    $update_cart = DB::table('carts')
                                ->where('row_id', '=',$rowid)
                                ->update(['is_wholesale' => $cart_result[$p]['is_wholesale']]); 
                }

            }
            else
            {
                $is_eligible_for_wholesale_discount = 0;
                for($p = 0; $p < count($cart_result); $p++) 
                {
                    $rowid = $cart_result[$p]['rowid'];

                    $update_cart = DB::table('carts')
                                ->where('row_id', '=',$rowid)
                                ->update(['is_wholesale' => 0]);
                }

            }

        }

        //Session::put


        return view('frontend.product.showAllCart', compact('cart_result','cartcontent','member','redemctrl','share_discount', 'brand_tot_wholesale_qty', 'brand_tot_wholesale_amount', 'is_eligible_for_wholesale_discount'),array('title'=>'My shopping busket'));


    }



    public function updateCart()
    {
        $obj = new helpers();
        $rowid = Input::get('rowid');
        $quantity = Input::get('quantity');


        Cart::update($rowid, $quantity); 		// Update cart product from SESSION respect with cart rowid.
        $cartContent = Cart::get($rowid);       // Get All Cart Details By Row ID.
        
        $subtotal = $cartContent->subtotal;     //Sub Total For Updated Row Id.
        
        if($obj->checkMemberLogin())            // If logged in as a member then Update from DB too
        {
            $update_cart = DB::table('carts')
                            ->where('row_id', '=',$rowid)
                            ->where('user_id', '=',Session::get('member_userid'))
                            ->update(['quantity' => $quantity,'sub_total' => $subtotal]);  // Update cart product quatity in DB respect with cart rowid.
        }
        else if($obj->checkBrandLogin())            // If logged in as a member then Update from DB too
        {

            // Check if the product is applicable as a wholesale product
            if (Input::has('is_wholesale'))
                $is_wholesale = Input::get('is_wholesale');
            else
                $is_wholesale = 0;

            $update_cart = DB::table('carts')
                            ->where('row_id', '=',$rowid)
                            ->where('user_id', '=',Session::get('brand_userid'))
                            ->update(['is_wholesale' => $is_wholesale, 'quantity' => $quantity, 'sub_total' => $subtotal]);  // Update cart product quatity in DB respect with cart rowid.
        }
		
        echo 1;  // Update cart
    }

    public function deleteCart()
    {
        $obj = new helpers();
        $rowid = Input::get('rowid');
        $brandid = Input::get('brand_id');

        $brandcoupon = DB::table('brand_coupons')
                                ->where('brand_user_id','=',$brandid)
                                ->where('status','=',1)
                                ->count();

        Cart::remove($rowid);			// Delete cart product from SESSION respect with cart rowid.
		if($obj->checkMemberLogin())   // If logged in as a member then delete from DB too
        {
            DB::table('carts')
                ->where('row_id', '=', $rowid)
                ->where('user_id', '=',Session::get('member_userid'))
                ->delete();   // Delete cart product from DB respect with cart rowid.
        }

        else if($obj->checkBrandLogin())   // If logged in as a member then delete from DB too
        {
            DB::table('carts')
                ->where('row_id', '=', $rowid)
                ->where('user_id', '=',Session::get('brand_userid'))
                ->delete();   // Delete cart product from DB respect with cart rowid.
        }
    
	
        // Find Brand Id with respect to product id


    	//destroy cart
    	$cartcount=Cart::count();
    	if($cartcount<=0){
    	    Cart::destroy();
            Session::forget('coupon_code');
            Session::forget('coupon_type');
            Session::forget('coupon_discount');
            Session::forget('coupon_amount');
            Session::forget('share_coupon_status');
            Session::forget('share_product_id');
            Session::forget('force_social_share');
    	}else if($brandcoupon>0){

           Session::forget('coupon_code');
            Session::forget('coupon_type');
            Session::forget('coupon_discount');
            Session::forget('coupon_amount'); 
        }



        echo 1; // Remove from  cart
    }


    public function coupon_cart()
    {
        $obj = new helpers();


        $today_date = date('Y-m-d');
        $counpon_res = DB::table('coupons')
                        ->where('code',Request::input('coupon_code'))
                        //->where('date_start','<=',$today_date)
                       // ->where('date_end','>=',$today_date)
                        ->where('status',1)->get();
        
        // calculate product id for Brand Coupon// 
        $content = $obj->content();
        foreach($content as $each_content)
        {

             $brandmember = DB::table('products')
                                ->leftJoin('brandmembers', 'brandmembers.id', '=', 'products.brandmember_id')
                                ->select('products.*', 'brandmembers.fname', 'brandmembers.lname', 'brandmembers.username', 'brandmembers.slug', 'brandmembers.pro_image', 'brandmembers.brand_details', 'brandmembers.brand_sitelink', 'brandmembers.status', 'brandmembers.admin_status')
                                ->where('products.id','=',$each_content->id)
                                ->first();
                                //echo "<pre>";print_r($brandmember); 
                                //echo $brandmember->slug ; exit;
            $brand_id[] = $brandmember->brandmember_id;
        }

         $brandcounpon_query = DB::table('brand_coupons')
                        ->join('products', 'products.brandmember_id', '=', 'brand_coupons.brand_user_id')
                        ->where('code',Request::input('coupon_code'))
                        ->whereIn('brand_coupons.brand_user_id',$brand_id)
                        ->where('status',1)->get();



        // End calculation 

                     
        if(!empty($counpon_res[0]))
        {
            $total_amount = Cart::total();

            if($counpon_res[0]->type=='F'){

               if($total_amount<$counpon_res[0]->discount){

                Session::flash('error', 'Discount price is greater than Total Price. To get discount purchase more!!!'); 
                return redirect('show-cart');
               }
            }

            Session::put('coupon_code',Request::input('coupon_code'));
            Session::put('coupon_type',$counpon_res[0]->type);
            Session::put('coupon_discount',$counpon_res[0]->discount);
            Session::put('share_coupon_status',$counpon_res[0]->share_coupon); // if 1="discount coupon + share coupon rate", 0="Only discount coupon"//
            
            //$content = $obj->content();
            
            /* Discount Share Start */

                if(Session::has('share_product_id'))
                {
                    $share_discount = 1;
                }   
                else
                {
                    $share_discount = 0;
                }               
            /* Discount Share End */
           
            if($share_discount ==1 && $counpon_res[0]->share_coupon==1) //social discount and also apply valid coupon with social
            {
                Session::flash('success', 'Coupon has been successfully applied.'); 
            }
            elseif($share_discount ==1 && $counpon_res[0]->share_coupon==0) //social discount but coupon doesn't apply with social 
            {
                Session::forget('coupon_code');
                Session::forget('coupon_type');
                Session::forget('coupon_discount');
                Session::forget('share_coupon_status');
                Session::flash('error', 'You already have social discount.You can not apply this coupon with social discount.'); 
            }
            else
            {
                Session::flash('success', 'Your coupon code is active.'); // only coupon discount.
            }

            return redirect('show-cart');

        }
        else if(!empty($brandcounpon_query[0]))
        {
          $total_amount = Cart::total();

            if($brandcounpon_query[0]->type=='F'){

               if($total_amount<$brandcounpon_query[0]->discount){

                Session::flash('error', 'Discount price is greater than Total Price. To get discount purchase more!!!'); 
                return redirect('show-cart');
               }
            }

            Session::put('coupon_code',Request::input('coupon_code'));
            Session::put('coupon_type',$brandcounpon_query[0]->type);
            Session::put('coupon_discount',$brandcounpon_query[0]->discount);
            //Session::put('share_coupon_status',$brandcounpon_query[0]->share_coupon); // if 1="discount coupon + share coupon rate", 0="Only discount coupon"//

                if(Session::has('share_product_id'))
                {
                    $share_discount = 1;
                }   
                else
                {
                    $share_discount = 0;
                }               
            /* Discount Share End */
           
           Session::flash('success', 'Your coupon code is active.'); // only coupon discount.

            return redirect('show-cart');  
        }
        Session::forget('coupon_code');
        Session::forget('coupon_type');
        Session::forget('coupon_discount');
        Session::forget('share_coupon_status');
        Session::flash('error', 'Coupon is not valid.'); 
        return redirect('show-cart');

        //echo Session::get('coupon_type');
       
       // echo "<pre>";print_r(Session::all());
        //echo "<pre>";print_r($obj->content());exit;
       
    }


    public function socialShareContent()  // this page only share content from cart page
    {
    	/* Site Setting Start */
            $sitesettings = DB::table('sitesettings')->get();
            $all_sitesetting = array();
            foreach($sitesettings as $each_sitesetting)
            {
                $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
            }

        /* End */   
    	return view('frontend.product.social_share',compact('all_sitesetting'),array('title'=>'Share Content'));
    }

    public function redeem_cart(){
        $obj = new helpers();
        $points=Request::input('user_points');
        $member =DB::table('brandmembers')->where("id",Session::get('member_userid'))->first();
        if($points>$member->user_points){
    	Session::flash('error', "You don't have enough points to redeem.");
    	return redirect('show-cart');
        }
        
        $setting_point_price = DB::table('sitesettings')->where('name','price_for_point')->first();
        $setting_point = DB::table('sitesettings')->where('name','points_for_price')->first();
        $points_ammount=$setting_point_price->value/$setting_point->value;
        $amount = $points_ammount * $points;
        $total_amount = Cart::total();
        
        if($amount>$total_amount){
    	Session::flash('error', "Your redeem value is higher than total amount.");
    	return redirect('show-cart');
        }
        
        $obj->demandredeem($points,$amount);
        Session::flash('success', '$'.$amount.' Is applied as redeem amount in your cart.');
        
        return redirect('show-cart');

    }

    public function cleanupCart() {

        $now = date("Y-m-d H:i:s");
        //echo $now."<br>";
        $time = date("Y-m-d H:i:s", strtotime($now."- 4 hours"));
        //echo $time;
        //exit;


        DB::table('carts')->where("created_at", "<", $time)->delete(); 
    }
              
}