<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/

use App\Model\Brandmember;          /* Model name*/
use App\Model\Product;              /* Model name*/
use App\Model\ProductIngredientGroup;    /* Model name*/
use App\Model\ProductIngredient;      /* Model name*/
use App\Model\ProductFormfactor;      /* Model name*/
use App\Model\Ingredient;             /* Model name*/
use App\Model\FormFactor;             /* Model name*/
use App\Model\Order;             /* Model name*/
use App\Model\OrderItem;             /* Model name*/
use App\Model\OrderItems; 

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

class OrderController extends BaseController {

	var $obj;

    public function __construct() 
    {
    	parent::__construct(); 
    	$this->obj = new helpers();
        view()->share('obj',$this->obj);
    }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */


    public function index()
    {
        if(!$this->obj->checkMemberLogin())
        {
            return redirect('memberLogin');
        }

        $limit = 10;
        //$order_list = Order::with('AllOrderItems')->paginate($limit);
        $order_list = Order::with('AllOrderItems')->where('user_id','=',Session::get('member_userid'))->orderBy('id', 'desc')->paginate($limit);

        
        $order_list->setPath('order-history');

        
        //echo "<pre>";print_r($order_list);exit;
        return view('frontend.order.member_order_history',compact('order_list'),array('title'=>'MIRAMIX | My Past Order'));
    }

    public function order_detail($id)
  	{
  		if(!$this->obj->checkMemberLogin())
        {
            return redirect('memberLogin');
        }

        $order_list = Order::find($id);
        if($order_list->user_id == Session::get('member_userid'))
        {
			if($order_list=='')
			return redirect('order-history');
			$order_items_list = $order_list->AllOrderItems;

			//echo "<pre>";print_r($order_list);exit;
			return view('frontend.order.member_order_details',compact('order_list','order_items_list'),array('title'=>'MIRAMIX | My Past Order'));
        }
        else
        {
        	return redirect('memberLogin');
        }
        

  	}


 public function rateProduct($product_id=''){
	
	if(!$this->obj->checkMemberLogin())
        {
            return redirect('memberLogin');
        }
	
	if($product_id==''){
		Session::flash('error', 'Please select valid product.');
		return redirect('member-dashboard');	
	}
	
	$member2=Session::get('member_userid');
	if(!empty($member2)){
	   $memberdetail =DB::table('brandmembers')->where("id",$member2)->first();
	}
	
	
	$rating_details = DB::table('product_rating')->where('product_id',$product_id)->where("user_id",$memberdetail->id)->first();
         if(count($rating_details)>0){
		Session::flash('error', 'You have already rated for this product.');
		return redirect('order-history');
	 }
	 
	if(Request::isMethod('post'))
    {
		$ratedata=array(
		"product_id"=>Request::input('product_id'),
		"user_id"=>$member2,
		"username"=>$memberdetail->username,
		"rating_value"=>Request::input('rating_val'),
		"rating_title"=>Request::input('review_title'),
		"comment"=>Request::input('message'),
		"created_on"=> date('Y-m-d H:i:s'),
		
		);
		
		DB::table('product_rating')->insert($ratedata);
		
		
		Session::flash('success', 'You have rated successfully for this product.');
                        
		return redirect('order-history');	
	}
	 
    $product=DB::table('products')->where('id',$product_id)->first();
	
	return view('frontend.order.member_rating',compact('product','memberdetail'),array('title'=>'MIRAMIX | Rate Product'));
	
 }
 
 public function rateAjax(){
	
	echo "none";
 }


 public function getList()
 {    
 	$limit = 5;

 	if(!Session::has('brand_userid'))
    {
        return redirect('memberLogin');
    }
 	$user_id = Session::get('brand_userid');
       
    $orders=OrderItems::where('brand_id',$user_id)    					
    					->orderBy('id','DESC')
    					->groupBy('order_id')
                    	->paginate($limit); 

    return view('frontend.order.list',compact('orders'),array('title'=>'Customer Order List'));
 }

 public function getDetails($order_id)
 { 
 	if(!Session::has('brand_userid'))
    {
        return redirect('memberLogin');
    }
 	$user_id = Session::get('brand_userid');
       
    $orders=Order::where('id',$order_id)->orderBy('id','DESC')->get(); 
    $order_items=OrderItems::where('order_id',$order_id)
    						->where('brand_id',$user_id)
    						->get();     

    return view('frontend.order.details',compact('orders','order_items'),array('title'=>'Order Details'));
 }

 /*public function getMyorder()
 {     
    $limit = 5;

    if(!Session::has('brand_userid'))
    {
        return redirect('memberLogin');
    }
    $user_id = Session::get('brand_userid');   
    $orders=Order::where('user_id',$user_id)->orderBy('id','DESC')->paginate($limit);      
    return view('frontend.order.myorder',compact('orders'),array('title'=>'Order List'));
 }*/

 public function getMyorder() 
 {     
    $limit = 5;

    if(!Session::has('brand_userid'))
    {
        return redirect('memberLogin');
    }

    $user_id = Session::get('brand_userid');   
    $orders=Order::where('user_id',$user_id)->orderBy('id','DESC')->paginate($limit);   


    return view('frontend.order.myorder',compact('orders'),array('title'=>'Order List'));

 }

 public function getMyorderdetails($order_id)
 { 
    
    if(!Session::has('brand_userid'))
    {
        return redirect('memberLogin');
    }
    $user_id = Session::get('brand_userid');
       
    $order=Order::where('id', $order_id)->first(); 

    /*if($order->is_wholesale == 1)
    {
        Session::put('is_wholesale_accept', 1);
    }
    else
    {
        Session::put('is_wholesale_accept', 0);
    }*/
    $order_items=OrderItems::where('order_id',$order_id)->get(); 
    //dd($order);
     

    return view('frontend.order.myorderdetails',compact('order','order_items'),array('title'=>'Order Details'));
 }

 public function getMyorderedit($order_id)
 {     
    if(!Session::has('brand_userid'))
    {
        return redirect('memberLogin');
    }
    $user_id = Session::get('brand_userid');
       
    $orders=Order::where('id',$order_id)                       
                        ->get();    
    
    return view('frontend.order.myorderedit',compact('orders'),array('title'=>'Update Status'));
 }

 public function postMyorderedit()
 { 
    $order_id=Request::input('order_id');  
    $order = Order::find($order_id);
    $order->order_status = Request::input('order_status');
    if(Request::input('order_status')=='shipped')
    {
        $order->tracking_number = Request::input('tracking_number');
        $order->shipping_carrier = Request::input('shipping_carrier');
    }
    else
    {
        $order->tracking_number = '';
        $order->shipping_carrier = ''; 
    }
    if($order->save())
    {
       Session::flash('success', 'Status updated successfully');      
       return redirect('orders/myorder');
    }
 }

 public function wholesale_status($id, $status)
 { 
    if(!isset($status) || !isset($id))
    {
        Session::flash('error', 'Oops! Error occured processing your request.');
        return redirect('orders/myorder');    
    }

    if($status == "reject")
    {
        $order = Order::where("id", $id)->first();
        $order->order_status = 'cancel';
        $order->wholesale_status = 'rejected';
        $order->save();

        Session::flash('success', 'Thanks for your confirmation.');      
        return redirect('orders/myorder');

    }

 }

 public function wholesaleCheckout()
 {
    //dd(Input::all());

    if(Input::get('order_id'))
    {
        $order_id = Input::get('order_id');

        Session::put('payment_method', Input::get('payment_type'));
        Session::put('preffered_communication',Input::get('preffered_communication'));

     
        $myorder = Order::find($order_id);

        $order_items=OrderItems::where('order_id',$order_id)->get();  

        //dd($order_items);

        $sub_total = 0;

        foreach($order_items as $eachitem)
        {
            //$eachitem['price']

            $order = OrderItems::where("id", $eachitem['id'])->first();
            $order->price = $eachitem['wholesale_offer_price'];
            $order->save();

            $sub_total += $eachitem['wholesale_offer_price'] * $eachitem['quantity'];


        }

        $myorder->sub_total = $sub_total;
        $myorder->order_total = $sub_total;
        
        $myorder->save();




        if(Request::isMethod('post'))
        {
            Session::put('name_card', Input::get('name_card'));//Input::get('name_card');
            Session::put('card_number', Input::get('card_number')); //Input::get('card_number'); //"4042760173301988";//
            Session::put('card_exp_month', Input::get('card_exp_month')); // "03"; //
            Session::put('card_exp_year', Input::get('card_exp_year'));  // "19"; //


            Session::put('wholesale_order_id', $order_id);
            Session::put('is_wholesale_order', 1);



            if(Input::get('payment_type') =='creditcard')     // if Payment With Credit Card 
            {
                return redirect('/checkout-authorize/'.$order_id);
            }
            elseif(Input::get('payment_type') =='paypal')    // if Payment With Paypal Account 
            {
                return redirect('/checkout-paypal/'.$order_id);    
            }


        }
        else
            return redirect('/checkout-cancel');   
            
    }

 }
 



              
}