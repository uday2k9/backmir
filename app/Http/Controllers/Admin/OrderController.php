<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\Brandmember;          /* Model name*/
use App\Model\Product;              /* Model name*/
use App\Model\ProductIngredientGroup;    /* Model name*/
use App\Model\ProductIngredient;      /* Model name*/
use App\Model\ProductFormfactor;      /* Model name*/
use App\Model\Ingredient;             /* Model name*/
use App\Model\FormFactor;             /* Model name*/
use App\Model\Order;             /* Model name*/
use App\Model\OrderItem;             /* Model name*/
use App\Model\OrderItems;   /* Model name*/
use App\Model\AddProcessOrderLabel;             /* Model name*/

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
use Mail;
use App\Helper\helpers;
use App\libraries\Usps;
use App\libraries\Postmaster;
use Twilio;
use App;
//use Knp\Snappy\Pdf;


class OrderController extends BaseController {

    public function __construct() 
    {
    	parent::__construct();
        view()->share('order_class','active');
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

   	
	   	$limit = 20;
		Session::forget('orderstatus');
		Session::forget('filterdate');
		Session::forget('filtertodate');
		Session::forget('brandemail');
		$order_list = Order::with('getOrderMembers','AllOrderItems')->where('is_wholesale', '0')->orderBy('id','DESC')->paginate($limit);
		//print_r($order_list);exit;
		$order_list->setPath('orders');
		$orderstatus='';
		$filterdate='';
		$filtertodate='';
		$brandemail='';
		$filterkeyword='';
		
		// Get All selected check Usps mail
		//$all_process_labels = DB::table('add_process_order_labels')->select('order_id', 'label')->get();
		$all_process_labels = AddProcessOrderLabel::all();

		return view('admin.order.order_history',compact('order_list','orderstatus','filterdate','brandemail','all_process_labels','filtertodate','filterkeyword'),array('title'=>'MIRAMIX | All Order','module_head'=>'Orders'));

    }

    

    public function wholesale_orders()
    {
	   	$limit = 20;
		/*Session::forget('orderstatus');
		Session::forget('filterdate');
		Session::forget('filtertodate');
		Session::forget('brandemail');*/
		$order_list = Order::with('getOrderMembers','AllOrderItems')->where('is_wholesale', '1')->orderBy('id','DESC')->paginate($limit);
		//print_r($order_list);exit;
		$order_list->setPath('orders');
		
		/*$orderstatus='';
		$filterdate='';
		$filtertodate='';
		$brandemail='';
		$filterkeyword='';*/
		
		// Get All selected check Usps mail
		//$all_process_labels = DB::table('add_process_order_labels')->select('order_id', 'label')->get();
		//$all_process_labels = AddProcessOrderLabel::all();



		$is_wholesale = 1;
		$brandemail=NULL;
		$filtertodate=NULL;
		$filterkeyword=NULL;
		return view('admin.order.order_history',compact('order_list','brandemail','filtertodate','filterkeyword'),array('title'=>'MIRAMIX | All Wholesale Order','module_head'=>'Wholesale Orders', 'is_wholesale'));

    }


    public function wholesale_offer($id)
    {
    	$msg = "";

    	$sitesettings = DB::table('sitesettings')->get();
        $all_sitesetting = array();
        foreach($sitesettings as $each_sitesetting)
        {
            $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
        }

        $admin_users_email = $all_sitesetting['email']; // Admin Support Email

	   	if(Input::has('orders'))
	   	{
	    
	   		$orders = Input::get('orders');
			$order_id = Input::get('order_id');	   		
	   		
	   		for($i=0; $i < count($orders); $i++)
	   		{
	   			$id = $orders[$i]['id'];
	   			$price = $orders[$i]['price'];
	   			
	   			OrderItems::where('id', $id)->update(['wholesale_offer_price' => $price]);

	   		}


	   		Order::where('id', $order_id)->update(['wholesale_status' => 'offered']);

	   		// Email the brand of the offer



            /* Order details for perticular order id */
            $order_list = DB::table('orders')
                        ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                        ->leftJoin('brandmembers', 'brandmembers.id', '=', 'orders.user_id')
                        ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name','order_items.brand_email', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name', 'brandmembers.fname', 'brandmembers.lname', 'brandmembers.email', 'brandmembers.username', 'brandmembers.phone_no')
                        ->where('orders.id','=',$order_id)
                        ->get();





            // Get Phone and Mobile Number from Order
            $serialize_add = unserialize($order_list[0]->shiping_address_serialize);
            $user_phone = $serialize_add['phone'];
            $user_check_email = $serialize_add['email'];
            $preffered_communication=$order_list[0]->preffered_communication;


	   		    
            $name = $order_list[0]->fname.' '.$order_list[0]->lname;
            $username = $order_list[0]->username;
            if($name!='')
                $mailing_name = $name;
            else
                $mailing_name = $username;

            $user_email = $order_list[0]->email; 

            $mobile =  $order_list[0]->phone_no; // logged user  phone number


        
        	// Get the Mobile Number

            if($mobile=='')
            {

                $mobile=$user_phone;
             
            }

            // Gte the messages from sitemessages table

       		$miramix_order = DB::table('sitemessages')->where('slug','wholesale_offer_notification')->first();

        	$sms_text = $miramix_order->sms_text;
        	$email_text = $miramix_order->email_text;
			$email_subject = $miramix_order->subject;


            if($preffered_communication==1)
            {

            	if($mobile !='')
                {

	            	$sms_text= str_replace("[order_id]",$order_list[0]->order_number,$sms_text);
	            	$order_message= str_replace("[order_amount]",$order_list[0]->order_total,$sms_text);
	            	$order_message= html_entity_decode($order_message);
	           
	            	Twilio::message('+1'.$mobile, $order_message);

                    
                }
        	}
        	else
        	{


	            $sent = Mail::send('frontend.checkout.wholesale_offer_mail', array('admin_users_email'=>$admin_users_email,'receiver_name'=>$mailing_name,'email'=>$user_check_email,'order_list'=>$order_list,'email_text'=>$email_text), 
	            function($message) use ($admin_users_email, $user_check_email,$mailing_name, $email_subject)
	            {
	                $message->from($admin_users_email);  //support mail
	                $message->to($user_check_email, $mailing_name)->subject($email_subject);
	            });

	            if( ! $sent) 
	            {
	              Session::flash('error', 'something went wrong!! Mail not sent.'); 
	              //return redirect('member-forgot-password');
	            }
	            else
	            {
	              Session::flash('success', 'Your order successfully placed.'); 
	              //return redirect('memberLogin');
	            }

        	}



	   		//$msg = "Offer successfully placed";
	   		return redirect('admin/wholesale-orders');



		
		}


		$order = Order::find($id);
		    
	    if($order=='')
	        return redirect('order-history');
	    
	    $order_items_list = $order->AllOrderItems;

	    return view('admin.order.wholesale_offer',compact('order', 'order_items_list'),
	    	array('title'=>'Wholesale Orders','module_head'=>'Wholesale discount offer', 'msg'));

		
    }


    

    public function show()
    {
    	// No action needed.
    }
    
	public function filters() {


	DB::enableQueryLog();

    $limit = 10;

    $orderstatus=Request::input('orderstatus');
	$filterdate=Request::input('filterdate');
	$filtertodate=Request::input('filtertodate');
	$filterkeyword=Request::input('filterkeyword');
	//dd("Test: ".$filterkeyword);

	$brandemail=Request::input('brandemail');
	if($orderstatus == '0') // If choose nothing in select box.
	{
		$orderstatus = '';
		if(Request::isMethod('post'))
        {
		Session::forget('orderstatus');
		}
	}
	if($filterdate == '') 
	{
		$filterdate = '';
		if(Request::isMethod('post'))
		{
		Session::forget('filterdate');
		}
	}
	
	if($filtertodate == '') 
	{
		$filtertodate = '';
		if(Request::isMethod('post'))
		{
		Session::forget('filtertodate');
		}
	}
	
	if($filterkeyword == '') 
	{
		$filterkeyword = '';
		if(Request::isMethod('post'))
		{
		Session::forget('filterkeyword');
		}
	}
	
	if($brandemail == '') // If choose nothing in select box.
	{
		$brandemail = '';
		if(Request::isMethod('post'))
		{
		Session::forget('brandemail');
		}
	}
	//echo $filterdate; exit;
	if(Request::isMethod('post'))
    {
		if($orderstatus !='0')
			Session::put('orderstatus',$orderstatus);
		
		if($filterdate !='')
			Session::put('filterdate',$filterdate);
		
		if($filtertodate !='')
			Session::put('filtertodate',$filtertodate);
		
		if($filterkeyword !='')
			Session::put('filterkeyword',$filterkeyword);
		
		if($brandemail !='')
			Session::put('brandemail',$brandemail);
	}

   // $order_list = Order::with('getOrderMembers','AllOrderItems')->orderBy('id','DESC');
	$order_list = DB::table('orders')
	->select(DB::raw('orders.*'))	
	->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
	->leftJoin('brandmembers', 'order_items.brand_id', '=', 'brandmembers.id')
	->leftJoin('addresses', 'addresses.mem_brand_id', '=', 'brandmembers.id')
	->orderBy('id','DESC');
	
	$orderstatus = Session::get('orderstatus');
	$filterdate = Session::get('filterdate');
	$filtertodate = Session::get('filtertodate');
	$brandemail = Session::get('brandemail');
	$filterkeyword = Session::get('filterkeyword');
	
	if(($orderstatus =='' || $orderstatus =='0') && $filterdate =='' && $filtertodate =='' && $brandemail=='' && $filterkeyword=='')
		{
			Session::forget('orderstatus');
			return redirect('/admin/orders');
		}

	if($orderstatus!='0' && $orderstatus!=''){
	   	$order_list->whereRaw("orders.order_status='".$orderstatus."'"); 
		//$order_list->where('orders.order_status', '=', $orderstatus);

	}
	
	/*if($filterdate!='' && $filtertodate==''){ 
	   $order_list->whereRaw("DATE(orders.created_at)>='".$filterdate."'"); 
	}elseif($filterdate!='' && $filtertodate!=''){
	    $order_list->whereRaw("DATE(orders.created_at) between '".$filterdate."' and '".$filtertodate."'"); 
	}elseif($filterdate=='' && $filtertodate!=''){
	    $order_list->whereRaw("DATE(orders.created_at)<='".$filtertodate."'"); 
	    
	}*/

	if($filterdate!='' && $filtertodate==''){ 
	   	$order_list->whereRaw("DATE(orders.created_at)>='".$filterdate."'");
	   	//$order_list->where('DATE(orders.created_at)', '=', $filterdate); 

	} elseif($filterdate!='' && $filtertodate!=''){
	   	$order_list->whereRaw("DATE(orders.created_at) between '".$filterdate."' and '".$filtertodate."'"); 
		//$order_list->whereBetween('DATE(orders.created_at)', [$filterdate, $filtertodate]);

	} elseif($filterdate=='' && $filtertodate!=''){
	    $order_list->whereRaw("DATE(orders.created_at)<='".$filtertodate."'"); 
	    //$order_list->where('DATE(orders.created_at)', '<=', $filtertodate);
	    
	}
	
	if($brandemail!=''){
	   $order_list->whereRaw("(brandmembers.business_name like '%".$brandemail."%')"); 
	}

	//dd($filterkeyword);

	if($filterkeyword!=''){
	   //$order_list->whereRaw("(brandmembers.email LIKE '%".$filterkeyword."%' OR addresses.email LIKE '%".$filterkeyword."%' OR orders.order_number = '".$filterkeyword."')"); 
		//$order_list->whereRaw("(brandmembers.email LIKE '%".$filterkeyword."%' OR addresses.email LIKE '%".$filterkeyword."%' OR orders.order_number = '".$filterkeyword."')"); 
		$order_list->whereRaw("(brandmembers.fname LIKE '%".$filterkeyword."%' 
			OR brandmembers.lname LIKE '%".$filterkeyword."%' 
			OR brandmembers.email LIKE '%".$filterkeyword."%' 
			OR order_items.product_name LIKE '%".$filterkeyword."%'
			OR orders.shiping_address_serialize LIKE '%".$filterkeyword."%'
			OR orders.order_number = '".$filterkeyword."')"); 
		
		
		
	}

	
	$order_list=$order_list->paginate($limit);

	

	//print_r($order_list);exit;
    $order_list->setPath('');
  	
  	if($orderstatus == '0') // If choose nothing in select box.
	{
		Session::forget('orderstatus');
		Session::forget('filterdate');
		Session::forget('filtertodate');
		Session::forget('brandemail');
		return redirect('/admin/orders');
	}
        
    return view('admin.order.order_history',compact('order_list','orderstatus','filterdate','filtertodate','brandemail','filterkeyword'),array('title'=>'MIRAMIX | All Order','module_head'=>'Orders'));
}
 public function edit($id)
 {
	
        
    $orders = Order::find($id);

    if($orders=='')
        return redirect('order-history');
    

    return view('admin.order.edit',compact('orders'),
    	array('title'=>'Edit Order','module_head'=>'Edit Order'));
 }
    
public function update(Request $request, $id)
{ 

	//dd(Input::all());
   	$orderUpdate=Request::all();
   	
   	$order=Order::with('getOrderMembers','AllOrderItems')->where("id",$id)->first();
	$order->update($orderUpdate);


	$shipping_detail = unserialize($order->shiping_address_serialize);
	
	$user_email = $shipping_detail['email'];
	$user_name = $shipping_detail['first_name']." ".$shipping_detail['last_name'];
	
	if($order->user_id!=''){
		$user_email = $order->getOrderMembers->email;	
		$user_name = !empty($order->getOrderMembers->fname)?$order->getOrderMembers->fname." ".$order->getOrderMembers->lname:$order->getOrderMembers->username;
	}


	
	$subject = 'Order status change of : #'.$order->order_number;
	$cmessage = 'Your order status is changed to '.$order->order_status.'. Please visit your account for details.';
	$tracking = '';
	$shipping = '';

	if($order->order_status=='shipped'){
		$tracking = 'Tracking Number is : '.$order->tracking_number;
		$shipping='Shipping Method is : '.$order->shipping_carrier .'<br />Please visit your account for details';
	}
	
	$setting = DB::table('sitesettings')->where('name', 'email')->first();
	$admin_users_email=$setting->value;
	
	$sent = Mail::send('admin.order.statusemail', array('name'=>$user_name,'email'=>$user_email,'messages'=>$cmessage,'admin_users_email'=>$admin_users_email,'tracking'=>$tracking,'shipping'=>$shipping), 
	
	function($message) use ($admin_users_email, $user_email,$user_name,$subject)
	{
		$message->from($admin_users_email);
		$message->to($user_email, $user_name)->cc($admin_users_email)->subject($subject);
		
	});

	if( ! $sent) 
	{
		Session::flash('error', 'something went wrong!! Mail not sent.'); 
		return redirect('admin/orders');
	}
	else
	{
	    Session::flash('success', 'Message is sent to user and order status is updated successfully.'); 
	    return redirect('admin/orders');
	}

       
}

    
    public function destroy($id)
    { 
        Order::find($id)->delete();
        return redirect('admin/orders');

        Session::flash('success', 'Order deleted successfully'); 
        return redirect('admin/orders');
    }
   
   	public function orderDetails($id)
    { 
        $order_list = Order::find($id);

        if($order_list=='')
            return redirect('order-history');
        $order_items_list = $order_list->AllOrderItems;
		$order_members = $order_list->getOrderMembers;

		$order_list->is_wholesale;
		
        return view('admin.order.order_details',compact('order_list','order_items_list','order_members'),array('title'=>'MIRAMIX | All Order','module_head'=>'Orders'));
        
    }
   	public function brand_search(){

   		if(isset($_REQUEST['term']) && $_REQUEST['term'] != "")
			$brands = DB::table('brandmembers')->whereRaw("role='1'")->whereRaw('(business_name LIKE "%' . $_REQUEST['term'] . '%" OR email LIKE "' . $_REQUEST['term'] . '%")' )->orderBy('id','DESC')->get();
		else
			$brands = DB::table('brandmembers')->get();

      	$arr = array();
	
	      	foreach ($brands as $value) {
	          if(!empty($value->business_name) && $value->role=="1"){
	          $arr[] = $value->business_name;
		  	}
      	}
      	echo json_encode($arr);
    }


    public function add_process_queue()
    { 

    	$postmaster_api_key = env("POSTMASTER_API_KEY");


        
        $param = Input::get('param');
        $order_id = Input::get('order_id');


        if($param=='add'){

        		$order_list = Order::find($order_id);
        		
        		$serialize_address = unserialize($order_list->shiping_address_serialize);
        		
        		if($serialize_address['country_id']=="US" ||$serialize_address['country_id']=="USA" 
        			&& $postmaster_api_key != "")
        		{
					//Postmaster::setApiKey("tt_MTUzOTEwMDE6RFp4V3ZybTB3bHRabm9ocENqaVlpUlZqcVRv");
					//Postmaster::setApiKey("tt_MTUzOTEwMDE6RFp4V3ZybTB3bHRabm9ocENqaVlpUlZqcVRv");

					Postmaster::setApiKey($postmaster_api_key);

					/* at first validate recipient address */
					$result = Postmaster::validate(array(
					"company" => $serialize_address['first_name'].' '.$serialize_address['last_name'],
					"contact" => $serialize_address['first_name'].' '.$serialize_address['last_name'],
					"line1" => $serialize_address['address'],
					"city" => $serialize_address['city'],
					"state" => $serialize_address['zone_id'],
					"zip_code" => $serialize_address['postcode'],
					"country" => $serialize_address['country_id'],
					));

					if(isset($result['status']) && $result['status']=="OK"){
					$arr = array('order_id'=>$order_id);
					AddProcessOrderLabel::create($arr);
					echo 1;
					}
					else{
					echo "Sorry ".$result['message']." !!";
					}
        		}else{
        			echo "Sorry US Address Only,For Other International Address You Have To Make Label Manually  !!";
        		}
				
				//var_dump($result);
        		
        			
        }
        else{
        	echo 1;
        	AddProcessOrderLabel::where('order_id', '=',$order_id)->delete();
        }

        
        exit;
        
    }

    public function push_order_process($param = false)
    { 
    	//echo $param;exit;
    	$usps_obj = new Usps();
    	$obj = new helpers();
        $all_process_orders = DB::table('add_process_order_labels')->get();
       
        $all_filename = array();
        $flag = 0;
        if(!empty($all_process_orders)){
        	foreach ($all_process_orders as $key => $value) {

        		// Get details for each order
        		$ord_dtls = Order::find($value->order_id);
        		$serialize_add = unserialize($ord_dtls['shiping_address_serialize']);
        		
        		$user_email = $serialize_add['email'];
				$user_name = $serialize_add['first_name']." ".$serialize_add['last_name'];
        		$phone = $serialize_add['phone'];
        		$address = $serialize_add['address'];
        		$address2 = $serialize_add['address2'];
        		$city = $serialize_add['city'];
        		$zone_id = $serialize_add['zone_id'];
        		$country_id = $serialize_add['country_id'];
        		$postcode = $serialize_add['postcode']; 


				$ToState = '';
				if(is_numeric($zone_id))
				{
					$ToState = $obj->get_statecode($zone_id);
				}
				else
				{
					$ToState = $obj->get_statecode_by_name($zone_id);
				}


        		// Call USPS API
        		$parameters_array = array('ToName'=>$user_name,'ToFirm'=>'','ToAddress1'=>$address2,'ToAddress2'=>$address,'ToCity'=>$city,'ToState'=>$ToState,'ToZip5'=>$postcode,'order_id'=>$value->order_id);
				$ret_array = $usps_obj->USPSLabel($parameters_array);
				//echo "<pre>";print_r($ret_array);exit;

				if($ret_array['filename']!=""){
					$flag = 1;
				}

        		$all_filename[] = $filename = $ret_array['filename'];
        		$tracking_number = $ret_array['tracking_no'];

        		// Update label name in DB
        		Order::where('id', $value->order_id)->update(['tracking_number' => $tracking_number,'shipping_carrier'=>'USPS','usps_label'=>$filename,'order_status'=>'shipped']);


        		// change order status and send mail
        		$order = Order::find($value->order_id);        		
				
				$subject = 'Order status change of : #'.$order->order_number;
				$cmessage = 'Your order status is changed to '.$order->order_status.'. Please visit your account for details.';
				$tracking = '';
				$shipping = '';

				if($order->order_status=='shipped'){
					$tracking = 'Tracking Number is : '.$tracking_number;
					$shipping='Shipping Method is : USPS<br />Please visit your account for details';
				}
				
				$setting = DB::table('sitesettings')->where('name', 'email')->first();
				$admin_users_email=$setting->value;
				
				/*$sent = Mail::send('admin.order.statusemail', array('name'=>$user_name,'email'=>$user_email,'messages'=>$cmessage,'admin_users_email'=>$admin_users_email,'tracking'=>$tracking,'shipping'=>$shipping), 
				
				function($message) use ($admin_users_email, $user_email,$user_name,$subject)
				{
					$message->from($admin_users_email);
					$message->to($user_email, $user_name)->cc($admin_users_email)->subject($subject);
					//$message->to('amit.unified@gmail.com', $user_name)->cc($admin_users_email)->subject($subject);
					
				});*/

        	}
        }
        // Delete from add_process_order_labels
		DB::table('add_process_order_labels')->delete();

		if($param==1){

		    $full_path = array();
			if(!empty($all_filename)){
				foreach ($all_filename as $file) {
					
					if($file!=""){
						$full_path[]= './uploads/pdf/'.$file;
					}

				}
			}
			if(!empty($full_path))
			{//$usps_obj->new_printPdf($full_path);
			    
				    // $full_path=array("./uploads/pdf/label22.pdf","./uploads/pdf/slabel.pdf");
				    $ch = curl_init(url()."/print.php");
				    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				    curl_setopt($ch, CURLOPT_POSTFIELDS, $full_path);
				    
				    $response = curl_exec($ch);
				    curl_close($ch);
				    Session::put("merge","done");
				    Session::put("pdffile",$response);
			}

		}
		//echo $flag;print_r($all_filename);exit;

		if($flag==1)
	    	Session::flash('success', 'Message is sent to user and order status is updated successfully.'); 
		else
	    	Session::flash('error', 'No label is created.'); 
	   
	    return redirect('admin/orders');
        
    }

    // Function to generate packing slip from order details page and be able to view it in PDF
    /*function generatePackingSlip() {


    	

		$snappy = App::make('snappy.pdf');
		//To file
		$snappy->generateFromHtml('<h1>Bill</h1><p>You owe me money, dude.</p>', '/var/www/html/miramix-prod/uploads/pdf/bill-123.pdf');
		//$snappy->generate('http://www.github.com', '/tmp/github.pdf');
		//Or output:
		return new Response(
		    $snappy->getOutputFromHtml($html),
		    200,
		    array(
		        'Content-Type'          => 'application/pdf',
		        'Content-Disposition'   => 'attachment; filename="file.pdf"'
		    )
		);

		//$snappy = new Pdf($myProjectDirectory . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');

		$myProjectDirectory = "/var/www/html/miramix-prod";

		$snappy = new Pdf("/usr/bin/wkhtmltopdf");

		$snappy->generateFromHtml('<h1>Bill</h1><p>You owe me money, dude.</p>', $myProjectDirectory.'/uploads/pdf/bill-123.pdf');



	}*/

	/*

	function generatePackingSlip() {

		


		$html = "<h1>Test</h1>";

		$pdf = App::make('dompdf.wrapper');
		$pdf->loadHTML($html);
		return $pdf->stream();

		//$output = $dompdf->output();
		//file_put_contents($path."uploads/pdf/file.pdf", $output);

	}*/


	public function generatePackingSlip($id) {
		
		
		$setting = DB::table('sitesettings')->where('name', 'address')->first();
		$address=$setting->value;
		
		$order = Order::find($id);
		
		/*$order_items_list = $order->AllOrderItems;
		$order_members=$order->getOrderMembers;
		
		print_r($order_items_list);
		exit;*/
		
		
		if(isset($order))
		{

			//dd($order->AllOrderItems);
			//dd($order->getOrderMembers);

			
			if(count($order->AllOrderItems) > 0 && count($order->getOrderMembers) > 0)
			{
				$order_items  = $order->AllOrderItems;
				
				$order_member = $order->getOrderMembers;
				
				
				
				//dd($order);
				
				$path = base_path('resources/views/admin/order/');
				
				$file = $path."packing_slip.blade.php";

				//echo $file;
				//exit;
				
				//$html = return \View::make('tickets.bus.index');
				
				$html = file_get_contents($file);
				//echo $html;
				
				
				$serialize_add = unserialize($order->shiping_address_serialize);
				
				//dd($serialize_add);
				
				
				$recipient_name = ""; $recipient_address = ""; $recipient_city = ""; $recipient_state = ""; $recipient_country = ""; $recipient_postcode = "";
				
				if(isset($serialize_add['first_name']) && isset($serialize_add['last_name']))
					$recipient_name = $serialize_add['first_name']." ".$serialize_add['last_name'];
				
				if(isset($serialize_add['address']))
					$recipient_address = $serialize_add['address'];
				
				
				if(isset($serialize_add['address2']))
					$recipient_address .= " ".$serialize_add['address2'];
				
				if(isset($serialize_add['city']))
					$recipient_city = $serialize_add['city'];
				
				if(isset($serialize_add['zone_id']))
					$recipient_state = $serialize_add['zone_id'];
				
				if(isset($serialize_add['country']))
					$recipient_country = $serialize_add['country'];
				
				if(isset($serialize_add['postcode']))
					$recipient_postcode = $serialize_add['postcode'];
				
				$order_date = substr($order->created_at, 0, 10);

				//$preffered_communication=$order_list[0]->preffered_communication;

				if(isset($serialize_add['email']))
					$email = $serialize_add['email'];
				
				//dd($order_items);
				
				$order_items_str = '';
				
				foreach($order_items as $order_item) {
					$amt = $order_item->price * $order_item->quantity;
					
					$order_items_str .= '<tr>
											<td>'.$order_item->product_name.'</td>
											<td class="right">$'.number_format($order_item->price, 2).'</td>
											<td>'.$order_item->quantity.'</td>
											<td class="right">$'.number_format($amt, 2).'</td>
										</tr>';
										
				}
				
				
				
				$src = array("{{PATH}}", "{{COMPANY_ADDRESS}}", "{{RECIPIENT_NAME}}", "{{RECIPIENT_ADDRESS}}", "{{RECIPIENT_CITY}}", "{{RECIPIENT_STATE}}", "{{RECIPIENT_ZIP}}", "{{RECIPIENT_COUNTRY}}", "{{ORDER_NUMBER}}", "{{ORDER_DATE}}", "{{USERNAME}}", "{{SHIP_DATE}}", "{{ODER_ITEMS}}", "{{SUB_TOTAL}}", "{{DISCOUNTS}}", "{{SHIPPINGS}}", "{{TOTAL}}");
				
				$dest =  array(url(), $address, $recipient_name, $recipient_address, $recipient_city, $recipient_state, $recipient_postcode, $recipient_country, $order->order_number, date("m/d/Y", strtotime($order_date)), $email, date("m/d/Y"), $order_items_str, number_format($order->sub_total, 2), number_format($order->total_discount, 2), number_format($order->shipping_cost, 2), number_format($order->order_total, 2));
				
				$html = str_replace($src, $dest, $html);
				
				
				
				//echo $html;
				//exit;
				
				$pdf = App::make('dompdf.wrapper');
				$pdf->loadHTML($html);
				return $pdf->stream();
				exit;
				
			
			}
			//else
				//echo "Order details not found";
		}
		else
			echo "Order not found";

	}
	
	
	
	public function packingSlip() {
		
		  // return view('admin.order.packing_slip.blade', compact('order_list','orderstatus','filterdate','filtertodate','brandemail'),array('title'=>'MIRAMIX | All Order','module_head'=>'Orders'));
		  
		  
		
		  return view('admin.order.packing_slip', array(), array('title'=>'MIRAMIX | All Order','module_head'=>'Orders'));

	}
	
    

}