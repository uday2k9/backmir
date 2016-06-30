<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/

use App\Model\Brandmember;  /* Model name*/
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
use App\Model\Subscription;


class RegisterController extends BaseController {

    public function __construct() 
    {
	   parent::__construct();
       
    }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        $obj = new helpers();
        if($obj->checkUserLogin()){
            return redirect('home');
        }
        $country = DB::table('countries')->orderBy('name','ASC')->get();
        // echo "<pre>";print_r($country); exit;
        $alldata = array();
        foreach($country as $key=>$value)
        {
            $alldata[$value->country_id] = $value->name;
        }
        //echo "<pre>";print_r($alldata); exit;
        return view('frontend.register.create',compact('alldata'));
    }

    public function brandRegister()
    {
        $obj = new helpers();
        if($obj->checkUserLogin()){
            return redirect('home');
        }

        $country = DB::table('countries')->orderBy('name','ASC')->get();
        
        $alldata = array();
        foreach($country as $key=>$value)
        {
            $alldata[$value->country_id] = $value->name;
        }
		//echo "<pre>";print_r($alldata); exit;
		$reg_brand_id =''; // No register brand id for first time.
	 $states = DB::table('zones')->where('country_id',  223)->orderBy('name','ASC')->get();
        
        $allstates = array();
        foreach($states as $key=>$value)
        {
            $allstates[$value->zone_id] = $value->name;
        }
		
    if(Request::isMethod('post'))
        {
	    $email_count = DB::table('brandmembers')
                    ->where('email', '=', Request::input('email'))->count();
	    if($email_count>0){
		Session::flash('error', 'Email already exists.'); 
		return redirect('brandregister');
	    }


	        /*if(Request::input('email')=='' || Request::input('fname')=='' || Request::input('lname') || Request::input('password')==''){
		Session::flash('error', 'Please fill form again.'); 
		return redirect('brandregister');
	    }*/
		    
	    
	     if(Request::input('expiry_month') !='' && Request::input('expiry_year')!='' && Request::input('card_number')!=''){
	    $country = DB::table('countries') ->where('country_id', '=',Request::input('card_country_id'))->first();
	    
	    
	    $shipping_card_addr = array('card_holder_fname' => Request::input('card_holder_fname'),
					'card_holder_lname' => Request::input('card_holder_lname'),
					'company_name' => Request::input('company_name'),
					'expiry_month' => Request::input('expiry_month'),
					'expiry_year' => Request::input('expiry_year'),
					'cvv' => Request::input('cvv'),
					'card_shiping_name' => Request::input('card_shiping_name'),
					'card_shiping_address' => Request::input('card_shiping_address'),
					'card_country_id' => Request::input('card_country_id'),
					'card_shiping_city' => Request::input('card_shiping_city'),
					'card_shipping_phone_no' => Request::input('card_shipping_phone_no'),
					'card_shipping_fax' => Request::input('card_shipping_fax'),
					'card_state' => Request::input('card_state'),
					'card_shipping_postcode' => Request::input('card_shipping_postcode'),
					'email'=> Request::input('email'),
					'card_number'=>Request::input('card_number'),
					'country'=>$country->name
					);
	   
		$res=Authorizenet::createprofile($shipping_card_addr);
	       
		if($res['status']=='fail'){
		$msg = $res['message'];
		Session::flash('error', 'something went wrong with creditcard details!!'.$msg.' Please try again.'); 
		return redirect('brandregister');
		}
	    }
	    
			
			if(isset($_FILES['government_issue']['name']) && $_FILES['government_issue']['name']!="")
			{
				$destinationPath = 'uploads/brand_government_issue_id/'; // upload path
				$extension = Input::file('government_issue')->getClientOriginalExtension(); 
				$government_issue = rand(111111111,999999999).'.'.$extension; 
				Input::file('government_issue')->move($destinationPath, $government_issue); // uploading file to given path
				
			}
			else
			{
				$government_issue = '';
			}
			
			
			if(isset($_FILES['business_doc']['name']) && $_FILES['business_doc']['name']!="")
			{
				$destinationPath = 'uploads/brandmember/business_doc/'; // upload path
				$extension = Input::file('business_doc')->getClientOriginalExtension(); 
				$business_doc = rand(111111111,999999999).'.'.$extension; 
				Input::file('business_doc')->move($destinationPath, $business_doc); 
				
			}
			else
			{
				$business_doc = '';
			}
			
			
			
			
			//if(Input::hasFile('image'))
			if(isset($_FILES['image']['name']) && $_FILES['image']['name']!="")
			{
				$destinationPath = 'uploads/brandmember/'; // upload path
                $thumb_path = 'uploads/brandmember/thumb/';
                $medium = 'uploads/brandmember/thumb/';
				$extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
				$fileName = rand(111111111,999999999).'.'.$extension; // renameing image
				Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
				
                $obj->createThumbnail($fileName,661,440,$destinationPath,$thumb_path);
                $obj->createThumbnail($fileName,116,116,$destinationPath,$medium);
			}
			else
			{
				$fileName = '';
			}
			
			$slug=$obj->create_slug(Request::input('business_name'),'brandmembers','slug');
			

			$hashpassword = Hash::make(Request::input('password'));
			
			$time=Request::input('calltime');
			$date=Request::input('calldate');
			$given_date=strtotime($date." ".$time);
			$given_date= date("Y-m-d H:s:i",$given_date);
			$setting = DB::table('sitesettings')->where('name', 'brand_fee')->first();
			$fee=$setting->value;
			$brandmember= Brandmember::create([
				'business_name'     => Request::input('business_name'),
				'brand_type'        => Request::input('brand_type'),
				'fname'             => Request::input('fname'),
				'lname'             => Request::input('lname'),
				'email'             => Request::input('email'),
				'username'          => strtolower(Request::input('fname')),
				'password'          => $hashpassword,
				'status'	    => 1,
				'government_issue'  => $government_issue,
				'business_doc'      => $business_doc,
				'phone_no'          => Request::input('phone_no'),
				'routing_number'    => Request::input('routing_number'),
				'account_number'    => Request::input('account_number'),
				'mailing_name'      => Request::input('mailing_name'),
				'mailing_address'   => Request::input('mailing_address'),
				'mailing_country_id'=> Request::input('mailing_country_id'),
				'mailing_city'=> Request::input('mailing_city'),
				'mailing_lastname'=> Request::input('mailing_lastname'),
				'mailing_address2'=> Request::input('mailing_address2'),
				'mailing_state'=> Request::input('mailing_state'),
				'mailing_postcode'=> Request::input('mailing_postcode'),
				'call_datetime'	  =>$given_date,
				'paypal_email'      => Request::input('paypal_email'),
				'mailing_address'   => Request::input('mailing_address'),
				'default_band_preference'   => Request::input('default_band_preference'),
				'pro_image'         => $fileName,
				'role'              => 1,                   // for member role is "0"
				'admin_status'      => 0,                   // Admin status
				'auth_profile_id'  =>isset($res['customer']['profile_id'])?($res['customer']['profile_id']):'',
				'auth_payment_profile_id'  =>isset($res['customer']['payment_profile_id'])?($res['customer']['payment_profile_id']):'',
				'auth_address_id'  =>isset($res['customer']['address_id'])?($res['customer']['address_id']):'',
				'slug'		=>strtolower($slug),
				'member_fee'=>$fee,
				'subscription_status' => 'active'
				
			]);
			
			
			$shipping_card_addr = array('card_holder_name' => Request::input('card_holder_name'),'card_number'=>Request::input('card_number'),'card_name' => Request::input('card_name'),'expiry_month' => Request::input('expiry_month'),'expiry_year' => Request::input('expiry_year'),'card_shiping_name' => Request::input('card_shiping_name'),'card_shiping_address' => Request::input('card_shiping_address'),'card_country_id' => Request::input('card_country_id'),'card_shiping_city' => Request::input('card_shiping_city'),'card_shipping_phone_no' => Request::input('card_shipping_phone_no'),'card_shipping_address2' => Request::input('card_shipping_address2'),'card_state' => Request::input('card_state'),'card_shipping_postcode' => Request::input('card_shipping_postcode'));
			$shipping_card_addr_serial = serialize($shipping_card_addr);
	
			$lastInsertedId = $brandmember->id;
			$start_date=date("Y-m-d");
			$end_date=date("Y-m-d",strtotime($start_date .' + 30 days'));
			$subdata=array("member_id"=>$lastInsertedId,"start_date"=>$start_date,"end_date"=>$end_date,"subscription_fee"=>$fee,"payment_status"=>'paid',"transaction_id"=>'free');
			Subscription::create($subdata);
			
			
			$reg_brand_id = $lastInsertedId; //base64_encode ($lastInsertedId); // encrypted last register brand member id
			$address = New Address;
			$address->mem_brand_id = $lastInsertedId;
			$address->first_name = Request::input('shiping_fname');
			$address->last_name = Request::input('shiping_lname');
			$address->address = Request::input('shiping_address');
			$address->address2 = Request::input('shipping_address2');
			$address->country_id = Request::input('country');
			$address->zone_id =  Request::input('state'); // State id
			$address->city =  Request::input('city');
			$address->postcode =  Request::input('shipping_postcode');
			$address->serialize_val =  '';
			
			if($address->save()) 
			{
				$addressId = $address->id;
				$dataUpdateAddress = DB::table('brandmembers')
					->where('id', $lastInsertedId)
					->update(['address' => $addressId]);
	
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
				
				//Session::flash('success', 'Registration completed successfully.Please check your email to activate your account.'); 
				//return redirect('brandregister');
	
				$user_name = Request::input('fname').' '.Request::input('lname');
				$user_email = Request::input('email');
				
				//$activateLink = url().'/activateLink/'.base64_encode(Request::input('email')).'/brand';
				$activateLink =url().'/brandLogin';
				$sent = Mail::send('frontend.register.activateLink', array('name'=>$user_name,'email'=>$user_email,'activate_link'=>$activateLink ,'admin_users_email'=>$admin_users_email), 
				function($message) use ($admin_users_email, $user_email,$user_name)
				{
					$message->from($admin_users_email);
					$message->to($user_email, $user_name)->subject('Welcome to Miramix');
				});
	
				if( ! $sent) 
				{
					Session::flash('error', 'something went wrong!! Mail not sent.'); 
					return redirect('brandregister');
				}
				else
				{
				    Session::flash('success', 'Registration completed successfully.Please wait for admin approval.'); 
							Session::flash('flush_reg_brand_id','open_modal'); 
				    Session::put('reg_brand_id',$reg_brand_id);
				    return redirect('brandLogin');
				}
			}
		}
				
        return view('frontend.register.registerbrand',compact('alldata','allstates'),array('reg_brand_id'=>$reg_brand_id));
    }
   
	public function updateDate()
	{
		$selectdate = date('Y-m-d',strtotime(Input::get('selectdate')));
		$brand_member_id = Input::get('brand_member_id');
         
        $dataUpdateDate = DB::table('brandmembers')
                ->where('id', $brand_member_id)
				->where('role', 1)
                ->update(['calender_date' => $selectdate]);
		Session::flash('success', 'Registration completed successfully.Please check your email to activate your account.'); 

		echo  1;
	}
	
    public function create()
    {

        return view('frontend.register.create');
    }

    public function store(Request $request)
    {
        $register=Request::all();
        //print_r($register);
        
        $hashpassword = Hash::make($register['password']);

        $brandmember= Brandmember::create([
            'email'             => $register['email'],
            'username'          => $register['user_name'],
            'password'          => $hashpassword,
            'role'              => 0,                   // for member role is "0"
            'admin_status'      => 1,                   // Admin status
	    'status'	    => 1,
            'updated_at'        => date('Y-m-d H:i:s'),
            'created_at'        => date('Y-m-d H:i:s')
        ]);

        $lastInsertedId = $brandmember->id;

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

        $user_name = $register['user_name'];
        $user_email = $register['email'];
        //$activateLink = url().'/activateLink/'.base64_encode($register['email']).'/member';
	$activateLink =url().'/memberLogin';
        $sent = Mail::send('frontend.register.activateLink', array('name'=>$user_name,'email'=>$user_email,'activate_link'=>$activateLink, 'admin_users_email'=>$admin_users_email), 
        function($message) use ($admin_users_email, $user_email,$user_name)
        {
            $message->from($admin_users_email);
            $message->to($user_email, $user_name)->subject('Welcome to Miramix');
        });

        if( ! $sent) 
        {
            Session::flash('error', 'something went wrong!! Mail not sent.'); 
            return redirect('register');
        }
        else
        {
            Session::flash('success', 'Registration completed successfully.Please login with your details to your account.'); 
            return redirect('memberLogin');
        }
       
        
    }

    public function getState()
    {
        $country_id = Input::get('countryId');
        
        $state = DB::table('zones')
                    ->where('country_id', '=', $country_id)
                    ->get();
        //echo "<pre>";print_r($state); exit;
        $alldata = array();
        $str='';
        foreach($state as $key=>$value)
        {
            //$alldata[$value->zone_id] = $value->name;
            $str .= '<option value='.$value->zone_id.'>'.$value->name.'</option>';
        }

        echo $str;
        exit;
    }

    public function emailChecking()
    {
        $email_id = Input::get('email');
        
        $email_count = DB::table('brandmembers')
                    ->where('email', '=', $email_id)
                    ->count();
        if($email_count >0)
        {
            echo  1; // Email already exist.
        }
        else
        {
            echo 0 ; // New email for registration.
        }
    }
    
    public function emailChecking2()
    {
        $email_id = Input::get('email');
        
        $email_count = DB::table('brandmembers')
                    ->where('email', '=', $email_id)
		    ->where('id', '<>', Session::get('brand_userid'))
                    ->count();
	$email_count2 = DB::table('brandmembers')
                    ->where('email', '=', $email_id)
		    ->where('id', '=', Session::get('brand_userid'))
                    ->count();
		    
        if($email_count >0)
        {
            echo  1; // Email already exist.
        }
        else
        {
            echo 0 ; // New email for registration.
        }
    }
    
    public function usernameChecking()
    {
        $user_name = Input::get('user_name');
        
        $user_name_count = DB::table('brandmembers')
                    ->where('username', '=', $user_name)
                    ->count();
        if($user_name_count >0)
        {
            echo  1; // user_name already exist.
        }
        else
        {
            echo 0 ; // New user_name for registration.
        }
    }
    
    public function usernameEmailChecking()
    {
        $user_name = Input::get('user_name');
        $email = Input::get('email');
        
        $user_name_count = DB::table('brandmembers')
                    ->where('username', '=', $user_name)
                    ->count();
        $email_count = DB::table('brandmembers')
                    ->where('email', '=', $email)
                    ->count();

		if($user_name_count>0)
			echo 1;
		else if($email_count>0)
			echo 2;
		else
			echo 0;
        
    }

    /* for activate Register user */
    public function activateLink($email=false,$role=false)
    {
        $useremail = base64_decode($email);
        //$useremail = 'sumitra1.unified@gmail.com';

        $active_count = DB::table('brandmembers')
                            ->where('email', '=', $useremail)
                            ->where('status', '=', 1)->first();

        if(!empty($active_count))
        {
            Session::flash('error', 'Your account has been already activated.Please login with your valid credentials.'); 
            if($active_count->role == 1)
            {
                return redirect('brandLogin');
            }
            else
            {
                return redirect('memberLogin');
            }
        }
        else
        {
            $success = DB::table('brandmembers')
                ->where('email', $useremail)
                ->update(['status' => 1]);

            if($success)
            {
                Session::flash('success', 'Your account has been activated.Please login with your valid credentials.'); 
                
                if($role == 'brand')
                {
                    return redirect('brandLogin');
                }
                else if($role == 'member')
                {
                    return redirect('memberLogin');
                }
            }
            else
            {
                Session::flash('error', 'Your account has not been activated.Please try again.'); 
                return redirect('register');
            }
        }
    }
    
}