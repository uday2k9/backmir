<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/

use App\Model\Brandmember;  /* Model name*/
use App\Model\Order;        /* Model name*/
use App\Model\OrderItems;   /* Model name*/
use Illuminate\Support\Collection;
use App\Http\Requests;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Request;
use App\Model\Address; 
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
use Cart;
use Mail;
use Twilio;
use App\libraries\Usps;

class CheckoutController extends BaseController {

    public function __construct() 
    {
        parent::__construct();

        $obj = new helpers();
        view()->share('obj',$obj);
        //echo "<pre>"; print_r($obj->content()); exit;
    }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        
    }
    
    public function checkoutMemberLogin()
    {
        $obj = new helpers();
        if($obj->checkMemberLogin())
        {
            return redirect('member-dashboard');
        }
        if($obj->checkBrandLogin())
        {
            return redirect('brand-dashboard');
        }

        if(Request::isMethod('post'))
        {
            $email = Request::input('email');
            $password = Request::input('password');
            $encrypt_pass = Hash::make($password);

            $login_arr = array('email' => $email, 'password' => $encrypt_pass);

            // 16-Feb-2016: Removing the role check
            // Either members or brands can login.

            $users = DB::table('brandmembers')->where('email', $email)->first();    // Only member Can Login here        
           // print_r($_POST);exit;
            
            if($users!="")
            {
                $user_pass = $users->password;
                // check for password
                if(Hash::check($password, $user_pass))
                {
                    // Check for active 
                    // 16-Feb-2016: Removing the role check
                    // Either members or brands can login.
                
                    //$user_cnt = DB::table('brandmembers')->where('email', $email)->where('status', 1)->where('admin_status', 1)->count();
                    $userdetails = DB::table('brandmembers')->where('email', $email)->where('status', 1)->where('admin_status', 1)->first();
                    
                    if(isset($userdetails->role) && $userdetails->role == 0)
                    {
                        Session::put('member_userid', $userdetails->id);
                        Session::put('member_user_email', $userdetails->email);
                        Session::put('member_username', ucfirst($userdetails->username));

                        Session::forget('step1');
                        Session::forget('guest_array');
                        Session::forget('guest');
                        Session::forget('step3');


                        //Set the user cart 
                        $this->update_cart($userdetails->id);
                        
                        return redirect('/checkout');
                    }
                    else if(isset($userdetails->role) && $userdetails->role == 1)
                    {
                        Session::put('brand_userid', $userdetails->id);
                        Session::put('brand_user_email', $userdetails->email);
                        Session::put('brand_username', ucfirst($userdetails->username));

                        Session::forget('step1');
                        Session::forget('guest_array');
                        Session::forget('guest');
                        Session::forget('step3');


                        //Set the user cart 
                        $this->update_cart($userdetails->id);
                        
                        return redirect('/checkout');
                    }
                    else
                    {
                        $site = DB::table('sitesettings')->where('name','email')->first();
                        Session::flash('error', 'Your Status is inactive. Contact Admin at '.$site->value.' to get your account activated!'); 
                        return redirect('/checkout');
                    }
                }
                else
                {
                        Session::flash('error', 'Email and password does not match.'); 
                        return redirect('/checkout');
                }
            }
            else
            {
                    Session::flash('error', 'This email-id is not register as a member.'); 
                    return redirect('/checkout');
            }
        }

        return view('frontend.checkout.checkout_setp1',compact('body_class'),array('title'=>'MIRAMIX | checkout'));
    } 
    public function checkoutStep1()
    {
        //dd(Session::all());
      /*  $array = Session::get('guest_array');
        //==========================================  
        $secret = "6Lf7viATAAAAABDeCPFKhXbYUYuZXklYm9g9Mw--";
        $captcha = $array['guest_captcha'];
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha&remoteip=$remoteip");
        $confirm = json_decode($response);
        // dd($confirm);
        if ($confirm->success) {
           dd('success');
        }
        else
        {
            dd('bad input');
        }*/
        
        //==============================
        $obj = new helpers();
 

        /* ==================== For Step 3 ========================================= */ 
        $allcountry = DB::table('countries')->orderBy('name','ASC')->get();

        $states = DB::table('zones')->where('country_id',  223)->orderBy('name','ASC')->get();
        
        $allstates = array();
        foreach($states as $key=>$value)
        {
            $allstates[$value->zone_id] = $value->name;
        }

        $shipAddress = array();


        if(Session::has('member_userid'))
            $shipAddress = DB::table('addresses')
            ->leftJoin('brandmembers', 'brandmembers.id', '=', 'addresses.mem_brand_id')
            ->leftJoin('countries', 'countries.country_id', '=', 'addresses.country_id')
            ->leftJoin('zones', 'zones.zone_id', '=', 'addresses.zone_id')
            ->select('addresses.*', 'brandmembers.fname', 'brandmembers.lname', 'brandmembers.username','brandmembers.address as default_address','countries.name as country_name', 'zones.name as zone_name', 'brandmembers.status', 'brandmembers.admin_status')
            ->where('addresses.mem_brand_id','=',Session::get('member_userid'))
            ->whereRaw('addresses.email<>"" and addresses.phone<>"" and addresses.address<>"" and addresses.country_id<>"" and addresses.city<>"" and addresses.zone_id<>"" and addresses.postcode<>""')
            ->get();
        
        else if(Session::has('brand_userid'))
            $shipAddress = DB::table('addresses')
            ->leftJoin('brandmembers', 'brandmembers.id', '=', 'addresses.mem_brand_id')
            ->leftJoin('countries', 'countries.country_id', '=', 'addresses.country_id')
            ->leftJoin('zones', 'zones.zone_id', '=', 'addresses.zone_id')
            ->select('addresses.*', 'brandmembers.fname', 'brandmembers.lname', 'brandmembers.username','brandmembers.address as default_address','countries.name as country_name', 'zones.name as zone_name', 'brandmembers.status', 'brandmembers.admin_status')
            ->where('addresses.mem_brand_id','=',Session::get('brand_userid'))
            ->whereRaw('addresses.email<>"" and addresses.phone<>"" and addresses.address<>"" and addresses.country_id<>"" and addresses.city<>"" and addresses.zone_id<>"" and addresses.postcode<>""')
            ->get();
        


    /* ==================== For Step 4 ========================================= */ 
     $sitesettings = DB::table('sitesettings')->get();
     $all_sitesetting = array();
        if(!empty($sitesettings))
        {
            foreach($sitesettings as $each_sitesetting)
            {
              if($each_sitesetting->name == 'shipping_rate')
              {
                $shipping_rate = ((float)$each_sitesetting->value);
              }
              if($each_sitesetting->name == 'free_discount_rate')
              {
                $free_discount_rate = ((float)$each_sitesetting->value);
              }

              $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value;
            }
        }
        // All Cart Contain  In Session Will Display Here //
        if(Session::has('member_userid'))
        {

            $content = DB::table('carts')->where('user_id',Session::get('member_userid'))->get();
        }
        else if(Session::has('brand_userid'))
        {
            $content = DB::table('carts')->where('user_id',Session::get('brand_userid'))->get();
            //dd($content);
        }
        
        else
        {

            $content = $obj->content();
            foreach($content as $each_content)
            {
                $each_content->product_id=$each_content->id;
                $each_content->form_factor=$each_content->options->form_factor;
                $each_content->row_id=$each_content->rowid;
                $each_content->product_name=$each_content->name;
                $each_content->quantity=$each_content->qty;
                $each_content->amount=$each_content->price;
                $each_content->duration=$each_content->options->duration;
                $each_content->sub_total=$each_content->subtotal;

            }

            

        }

        //dd($content);

        // redirect to cart page if cart is empty
        if(Cart::count()==0){
            return redirect('show-cart');
        }

        $share_discount = '';

        //dd($content);

        foreach($content as $each_content)
        {
            
            $product_res = DB::table('products')->where('id',$each_content->product_id)->first();
            // echo $each_content->brandmember_id; exit;
            $brandmember = DB::table('products')
                            ->leftJoin('brandmembers', 'brandmembers.id', '=', 'products.brandmember_id')
                            ->select('products.*', 'brandmembers.fname', 'brandmembers.lname', 'brandmembers.username', 'brandmembers.slug', 'brandmembers.pro_image', 'brandmembers.brand_details', 'brandmembers.brand_sitelink', 'brandmembers.status', 'brandmembers.admin_status')
                            ->where('products.id','=',$each_content->product_id)
                            ->first();
            //echo "<pre>";print_r($brandmember); 
            //echo $brandmember->slug ; exit;
            $brand_name = ($brandmember->fname)?($brandmember->fname.' '.$brandmember->lname):$brandmember->username;

            $formfactor = DB::table('form_factors')->where('id','=',$each_content->form_factor)->first();
            $formfactor_name = $formfactor->name;
            $formfactor_id = $formfactor->id;

            /* Discount Share Start */
            if(Session::has('product_id'))
            {
                $pid = array();
                $pid=Session::get('share_product_id');
                if(in_array($each_content->product_id,$pid) )
                {
                    $share_discount = $all_sitesetting['discount_share'];
                }
                
            }
            if(Session::get('force_social_share')!='') // For cart page share
            {
                $share_discount = $all_sitesetting['discount_share'];
            }           
            /* Discount Share End */

            $cart_result[] = array('rowid'=>$each_content->row_id,
                'product_id'=>$each_content->product_id,
                'product_name'=>$each_content->product_name,
                'product_slug'=>$brandmember->product_slug,
                'product_image'=>$product_res->image1,
                'qty'=>$each_content->quantity,
                'price'=>$each_content->amount,
                'duration'=>$each_content->duration,
                'formfactor_name'=>$formfactor_name,
                'formfactor_id'=>$formfactor_id,
                'brand_name'=>$brand_name,
                'brand_slug'=>$brandmember->slug,
                'subtotal'=>$each_content->sub_total,
                'is_wholesale'=>$each_content->is_wholesale);

        }
        $cartcontent = Cart::content();



        if(Session::get('member_userid')!='')
        {
            // Get Details from Brand Member

            //dd(Session::get('brand_userid'));

            $brandusers = DB::table('brandmembers')->where('id', Session::get('member_userid'))->where('role', 0)->first();
            
            Session::put('preffered_communication',$brandusers->preffered_communication);
            $brandusers_result[] = array('id'=>$brandusers->id,
                    'fname'=>$brandusers->fname,
                    'email'=>$brandusers->email,
                    'lname'=>$brandusers->lname,
                    'phone_no'=>$brandusers->phone_no,
                    'preffered_communication'=>Session::get('preffered_communication'));
        } 
        else if(Session::get('brand_userid')!='')
        {
            // Get Details from Brand Member

            //dd(Session::get('brand_userid'));

            $brandusers = DB::table('brandmembers')->where('id', Session::get('brand_userid'))->where('role', 1)->first();

            //dd($brandusers);
            
            Session::put('preffered_communication',$brandusers->preffered_communication);
            $brandusers_result[] = array('id'=>$brandusers->id,
                    'fname'=>$brandusers->fname,
                    'email'=>$brandusers->email,
                    'lname'=>$brandusers->lname,
                    'phone_no'=>$brandusers->phone_no,
                    'preffered_communication'=>Session::get('preffered_communication'));
        } 

        else
        {
           $brandusers_result[] = array('id'=>'',
                    'fname'=>'',
                    'email'=>'',
                    'lname'=>'',
                    'phone_no'=>'',
                    'preffered_communication'=>''); 
        }

        //dd($shipAddress);
        
        return view('frontend.checkout.checkout_allstep',compact('body_class','shipAddress','allcountry','allstates','cart_result','shipping_rate','cartcontent','share_discount','brandusers_result'),array('title'=>'MIRAMIX | Checkout-Step1'));

    }

    //
    public function checkoutSubStep3()
    {

        
        $user_id = "";

        if(Session::has('brand_userid'))
        {
            $user_id = Session::get('brand_userid');
            /*if(Session::has('wholesale_total_amount') && Session::has('regular_total_amount'))
            {
                $wholesale_total_amount = Session::has('wholesale_total_amount');
                $regular_total_amount = Session::has('regular_total_amount');
                

                if(floatval($wholesale_total_amount) == floatval($regular_total_amount) && $wholesale_total_amount > 0)
                {
                    //dd("Checkout no shipping");
                    return redirect('/checkout-wholesale');
                }

            }*/
        }
        else if(Session::has('member_userid'))
           $user_id = Session::get('member_userid');



           
        $userShipAddress = DB::table('addresses')->where('addresses.mem_brand_id','=',$user_id)->count();
        
        if($userShipAddress >= 1)
        {
            
            $addrs = DB::table('addresses')->where('addresses.mem_brand_id','=',$user_id)->first();
            
            DB::table('brandmembers')
                                ->where('id', $user_id)
                                ->update(['address' =>$addrs->id]);

        }
        


        if($user_id != "")
        {

            $sitesettings = DB::table('sitesettings')->get();
            $all_sitesetting = array();
            foreach($sitesettings as $each_sitesetting)
            {
                $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
            }


            $admin_users_email = $all_sitesetting['email']; // Admin Support Email
            $payment_method =  $all_sitesetting['payment_mode']; 


            if(Request::has('preffered_communication'))
                Session::put('preffered_communication', Input::get('preffered_communication'));

          
            if(Request::input('select_address') == 'existing')
            {
                Session::put('select_address','existing');
                Session::put('selected_address_id',Request::input('existing_address'));
            }

            else if(Request::input('select_address') == 'newaddress')
            {
                Session::put('select_address','newaddress');
                
                $insert_address = DB::table('addresses')
                                        ->insert([
                                            'address_title' => 'default address',
                                            'mem_brand_id' => $user_id, 
                                            'first_name' => Request::input('fname'), 
                                            'last_name' => Request::input('lname'), 
                                            'email' => Request::input('email_custom_address'),
                                            'phone' => Request::input('phone'), 
                                            'address' => Request::input('address'),
                                            'address2' => Request::input('address2'),
                                            'city' => Request::input('city'),
                                            'country_id' => Request::input('country_id'),
                                            'zone_id' => Request::input('state'),
                                            'postcode' => Request::input('zip_code')
                                            ]);
                
                $lastInsertedId =DB::getPdo()->lastInsertId(); 
                  
                if(isset($userShipAddress) && $userShipAddress <= 0){

                    $dataUpdateAddress = DB::table('brandmembers')
                    ->where('id', $user_id)
                    ->update(['address' => $lastInsertedId]);
                
                }

                // Getting last inserted id

                Session::put('selected_address_id', $lastInsertedId);        // shipping address option value store in session.
                

            }

        }



        exit;
        
    }

    public function checkoutSubStep2()
    {
        // dd("checkoutSubStep2");
        Session::put('payment_method',Input::get('payment_type'));
        Session::put('preffered_communication', Input::get('preffered_communication'));
        //return redirect('/checkout');
    }
    
    public function checkoutguestlogin()
    {
        // dd('checkoutguestlogin');
        Session::put('step1','completed');
        return redirect('/checkout');
    }

    public function checkoutguestsubmit()
    {
        if (Request::input('g-recaptcha-response')) {
            $secret = "6Lf7viATAAAAABDeCPFKhXbYUYuZXklYm9g9Mw--";
            $captcha = Request::input('g-recaptcha-response');
            $remoteip = $_SERVER['REMOTE_ADDR'];
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha&remoteip=$remoteip");
            $confirm = json_decode($response);
            // dd($confirm);
            if ($confirm->success) {
                echo "checkoutguestsubmit";
                $guest_array = array('guest_fname'=>Request::input('guest_fname'),'guest_lname'=>Request::input('guest_lname'),'guest_email'=>Request::input('guest_email'),'guest_phone'=>Request::input('guest_phone'),'guest_address'=>Request::input('guest_address'),'guest_address2'=>Request::input('guest_address2'),'guest_country_id'=>Request::input('guest_country_id'),'guest_state'=>Request::input('guest_state'),'guest_city'=>Request::input('guest_city'),'guest_zip_code'=>Request::input('guest_zip_code'));
                Session::put('guest_array',$guest_array);
                Session::put('guest',1);
                Session::put('step3','completed');
                return redirect('/checkout');
            }
            else
            {
                Session::put('error_captcha','Not a verified user');
                return redirect('/checkout');
            }
        }
        else
        {
                Session::put('error_captcha','Not a verified user');
                return redirect('/checkout');
        }
       

    }


    public function checkoutStep4()
    {
        $obj = new helpers();
        $shp_address=array();

        $user_id2 = "";



        if(Session::has('brand_userid')) 
        {       
            $user_id2 = Session::get('brand_userid');
        }
        else if(Session::has('member_userid'))
        {
           $user_id2 = Session::get('member_userid');
        }
        else
        {
           $user_id2 = NULL;   
        }
        //echo $user_id2;

        Session::put('checkout_userid', $user_id2);

    
        $sitesettings = DB::table('sitesettings')->get();
        if(!empty($sitesettings))
        {
            foreach($sitesettings as $each_sitesetting)
            {
              if($each_sitesetting->name == 'shipping_rate')
              {
                $shipping_rate = ((float)$each_sitesetting->value);
              }
              if($each_sitesetting->name == 'free_discount_rate')
              {
                $free_discount_rate = ((float)$each_sitesetting->value);
              }
            }
        }

        $preffered_communication = 0;

        if(Session::has('preffered_communication'))
            $preffered_communication = Session::get('preffered_communication');


        $sitesettings = DB::table('sitesettings')->get();
        $all_sitesetting = array();
        foreach($sitesettings as $each_sitesetting)
        {
            $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
        }


        $admin_users_email = $all_sitesetting['email']; // Admin Support Email
        $payment_method =  $all_sitesetting['payment_mode']; 




        if(Request::isMethod('post'))
        {
            Session::put('name_card',Input::get('name_card'));//Input::get('name_card');
            Session::put('card_number',Input::get('card_number')); //Input::get('card_number'); //"4042760173301988";//
            Session::put('card_exp_month',Input::get('card_exp_month')); // "03"; //
            Session::put('card_exp_year',Input::get('card_exp_year'));  // "19"; //

            //checkout as guest

           
            //if(!Session::has('member_userid')){
            if(!Session::has('checkout_userid')){

                $guestdata=Session::get('guest_array');
                $shiping_address = array('address_title'        => 'default address',
                                    'first_name'                => $guestdata["guest_fname"],
                                    'last_name'                 => $guestdata["guest_lname"],
                                    'email'                     => $guestdata["guest_email"],
                                    'phone'                     => $guestdata["guest_phone"],
                                    'address'                   => $guestdata["guest_address"],
                                    'address2'                  => $guestdata["guest_address2"],
                                    'city'                      => $guestdata["guest_city"],
                                    'zone_id'                   => $guestdata["guest_state"],
                                    'country_id'                => $guestdata["guest_country_id"],
                                    'postcode'                  => $guestdata["guest_zip_code"]
                                    );


                $want_reg=Request::input('register_user');
                if($want_reg=='register'){

                    //register the member
                    Session::put('guest_username_sess',Request::input('guest_username'));
                    
                    $brandmember= Brandmember::create([
                        'fname'             => $guestdata['guest_fname'],
                        'lname'             => $guestdata['guest_lname'],
                        'email'             => $guestdata['guest_email'],
                        'username'          => Request::input('guest_username'),
                        'password'          => Hash::make(Request::input('guest_password')),
                        'role'              => 0,                   // for member role is "0"
                        'admin_status'      => 1, 
                        'status'            =>1,                  // Admin status
                        'updated_at'        => date('Y-m-d H:i:s'),
                        'created_at'        => date('Y-m-d H:i:s')
                    ]);

                    $lastInsertedId = $brandmember->id;

                    $shiping_address['mem_brand_id']=$brandmember->id;
                    
                    $shp_address=Address::create($shiping_address);
                    $lastAddressId =DB::getPdo()->lastInsertId();  
                    $user_id=$brandmember->id;

                    // Update Address id in brandmember table
                    $addressId = $shp_address->id;
                    $dataUpdateAddress = DB::table('brandmembers')
                        ->where('id', $brandmember->id)
                        ->update(['address' => $addressId]);
                            
                } 
                else 
                {
                    //set userid for not loggedin users to pass the order
                    $user_id=NULL;
                    $shp_address['id']=NULL;
                    $shp_address=(object)$shp_address;
                    //print_r($shp_address); exit;
                }
                // End of registration ==================================================

                /* To get the country code And Zone code */
                
                $shp_country = DB::table('countries')
                                ->where('country_id',$guestdata["guest_country_id"])
                                ->first();
                $shp_zone = DB::table('zones')
                                ->where('zone_id',$guestdata["guest_state"])
                                ->first();

            
                $shiping_address = array('address_title'        => 'default address',
                                    'first_name'                => $guestdata["guest_fname"],
                                    'last_name'                 => $guestdata["guest_lname"],
                                    'email'                     => $guestdata["guest_email"],
                                    'phone'                     => $guestdata["guest_phone"],
                                    'address'                   => $guestdata["guest_address"],
                                    'address2'                  => $guestdata["guest_address2"],
                                    'city'                      => $guestdata["guest_city"],
                                    'zone_id'                   => $shp_zone->code,
                                    'country_id'                => $shp_country->iso_code_3,
                                    'postcode'                  => $guestdata["guest_zip_code"]
                                    );
                //print_r($shiping_address); exit;
                $shiping_address_serial = serialize($shiping_address);

            }
            else
            {
                //for logged-in users
                // 16-Feb-2016: Added following if..else to get the shipping address
                // in case  Session::get('selected_address_id') is not available
                if(Session::has('selected_address_id'))
                {
                    $shp_address = DB::table('addresses')
                            ->leftjoin('countries', 'countries.country_id', '=', 'addresses.country_id')
                            ->leftjoin('zones', 'zones.zone_id', '=', 'addresses.zone_id')
                            ->select('addresses.*', 'countries.name as country_name','countries.iso_code_3 as country_code', 'zones.name as zone_name', 'zones.code as zone_code')
                            ->where('mem_brand_id',Session::get('member_userid'))
                            ->where('id', Session::get('selected_address_id'))
                            ->first();
                }
                else
                {
                    $shp_address = DB::table('addresses')
                            ->leftjoin('countries', 'countries.country_id', '=', 'addresses.country_id')
                            ->leftjoin('zones', 'zones.zone_id', '=', 'addresses.zone_id')
                            ->select('addresses.*', 'countries.name as country_name','countries.iso_code_3 as country_code', 'zones.name as zone_name', 'zones.code as zone_code')
                            ->where('mem_brand_id',Session::get('checkout_userid'))
                            ->orderBy('id','DESC')
                            ->first();

                }
                
                // Serialize the Shipping Address because If user delete there address from "addresses" table,After that the address also store in the "order" table for  getting order history//
                $shiping_address = array('address_title'            => $shp_address->address_title,
                                        'mem_brand_id'              => $shp_address->mem_brand_id,
                                        'first_name'                => $shp_address->first_name,
                                        'last_name'                 => $shp_address->last_name,
                                        'email'                     => $shp_address->email,
                                        'phone'                     => $shp_address->phone,
                                        'address'                   => $shp_address->address,
                                        'address2'                  => $shp_address->address2,
                                        'city'                      => $shp_address->city,
                                        'zone_id'                   => $shp_address->zone_code, // two digit code //
                                        'country_id'                => $shp_address->country_code,  // three digit code iso_code_3//
                                        'postcode'                  => $shp_address->postcode
                                        );

                $shiping_address_serial = serialize($shiping_address);
                //echo "pm= ".Session::get('payment_method'); exit;
                
            }

            // Check if any wholesale orders. If so divide the order into 2 orders
            ///we are not storing new registered cart in cart table as it will be destroyed soon

            $regular_orders = array();
            $wholesale_orders = array();

            if(Session::has('member_userid'))
            {
                $user_id=Session::get('member_userid');
                $allCart = DB::table('carts')->where('user_id',Session::get('member_userid'))->get();
            }
            else if(Session::has('brand_userid'))
            {
                $user_id=Session::get('brand_userid');
                $allCart = DB::table('carts')->where('user_id',Session::get('brand_userid'))->get();
            }
            else
            {
                $allCart = $obj->content();

                foreach($allCart as $each_content)
                {
                    

                    $each_content->product_id=$each_content->id;
                    $each_content->form_factor=$each_content->options->form_factor;
                    $each_content->row_id=$each_content->rowid;
                    $each_content->product_name=$each_content->name;
                    $each_content->quantity=$each_content->qty;
                    $each_content->amount=$each_content->price;
                    $each_content->duration=$each_content->options->duration;
                    $each_content->sub_total=$each_content->subtotal;
                    $each_content->no_of_days=$each_content->options->no_of_days;

                }
            }


            ///$wholesale_sub_total = 0;
            $is_wholesale = 0; //Flag to check if there is at least one wholesale order
            $is_regular = 0; //Flag to check if there is at least one regular order



            $wholesale_order_total = 0;

            if(Input::has('wholesale_order_total'))
                $wholesale_order_total = Input::get('wholesale_order_total');

            //dd($wholesale_order_total);
           
            if(Request::input('sub_total') > 0 && Request::input('grand_total') > 0)
                $is_regular = 1;


            if($is_regular = 1)
            {
                $order= Order::create([
                                    'order_total'               => Request::input('grand_total'),
                                    'sub_total'                 => Request::input('sub_total'),
                                    'discount'                  => Request::input('discount'),
                                    'share_discount'            => Request::input('social_discount'),
                                    'total_discount'            => Request::input('total_discount'),
                                    'redeem_amount'             => Request::input('redeem_amount'),
                                    'order_status'              => 'pending',
                                    'shipping_address_id'       => $shp_address->id,
                                    'shipping_cost'             => Request::input('shipping_rate'),
                                    'shipping_type'             => 'flat',
                                    'user_id'                   => $user_id,
                                    'ip_address'                => $_SERVER['REMOTE_ADDR'],
                                    'payment_method'            => Session::get('payment_method'),
                                    'preffered_communication'   => Session::get('preffered_communication'),
                                    'transaction_id'            => '',
                                    'transaction_status'        => '',                                       
                                    'is_wholesale'              => 0,
                                    'shiping_address_serialize' => $shiping_address_serial,
                                    'created_at' => date('Y-m-d H:s:i'),
                                    'updated_at' => date('Y-m-d H:s:i')]);
            
                $last_order_id = $order->id;
                

                $obj = new helpers();
                $order_number = 'ORD-'.$obj->random_string(5).'-'.$last_order_id;   // Generate random String for order number
                $update_order_number = DB::table('orders')
                                            ->where('id', $last_order_id)
                                            ->update(['order_number' => $order_number]);

                Session::put('order_number',$order_number);
                Session::put('order_id',$last_order_id);


                

                foreach($allCart as $eachCart)
                {
                    $product_details = DB::table('products')->where('id',$eachCart->product_id)->first();
                   // echo $each_content->brandmember_id; exit;
                    $brandmember_deatils = DB::table('products')
                                        ->leftJoin('brandmembers', 'brandmembers.id', '=', 'products.brandmember_id')
                                        ->select('products.*', 'brandmembers.fname', 'brandmembers.lname', 'brandmembers.username','brandmembers.email', 'brandmembers.slug', 'brandmembers.pro_image', 'brandmembers.brand_details', 'brandmembers.brand_sitelink', 'brandmembers.status', 'brandmembers.admin_status')
                                        ->where('products.id','=',$eachCart->product_id)
                                        ->first();
                                        //echo "<pre>";print_r($brandmember_deatils); exit;
                                        //echo $brandmember->slug ; exit;
                    $brand_member_name = ($brandmember_deatils->fname)?($brandmember_deatils->fname.' '.$brandmember_deatils->lname):$brandmember_deatils->username;

                    $formfactor = DB::table('form_factors')->where('id','=',$eachCart->form_factor)->first();
                    
                    // Enter the data if not a wholesale order
                    // The coulmn of 
                    if($eachCart->is_wholesale == 0)
                    {
                        $order_item = OrderItems::create([
                            'order_id'          => $last_order_id,
                            'brand_id'          => $brandmember_deatils->brandmember_id,
                            'brand_name'        => $brand_member_name,
                            'brand_email'       => $brandmember_deatils->email,
                            'product_id'        => $eachCart->product_id,
                            'product_name'      => $eachCart->product_name,
                            'product_image'     => $product_details->image1,
                            'quantity'          => $eachCart->quantity,
                            'price'             => $eachCart->amount,                                
                            'form_factor_id'    => $formfactor->id,
                            'form_factor_name'  => $formfactor->name,
                            'duration'          => $eachCart->duration,
                            'no_of_days'        => $eachCart->no_of_days
                        ]);
                    }



                    
                }


            }

            //******************** WHOLESALE ORDERS STARTS ********************//
            // Now insert all wholesale orders
            if(floatval($wholesale_order_total) > 0)
            {



                $order= Order::create([
                                    'order_total'               => Request::input('wholesale_order_total'),
                                    'sub_total'                 => Request::input('wholesale_order_total'),
                                    'discount'                  => 0,
                                    'share_discount'            => 0,
                                    'total_discount'            => 0,
                                    'redeem_amount'             => 0,
                                    'order_status'              => 'pending',
                                    'shipping_address_id'       => $shp_address->id,
                                    'shipping_cost'             => 0,
                                    'shipping_type'             => 'flat',
                                    'user_id'                   => $user_id,
                                    'ip_address'                => $_SERVER['REMOTE_ADDR'],
                                    'payment_method'            => Session::get('payment_method'),
                                    'preffered_communication'   => Session::get('preffered_communication'),
                                    'transaction_id'            => '',
                                    'transaction_status'        => '',
                                    'is_wholesale'              => 1,
                                    'shiping_address_serialize' => $shiping_address_serial,
                                    'created_at' => date('Y-m-d H:s:i'),
                                    'updated_at' => date('Y-m-d H:s:i')
                                ]);

                $wholesale_last_order_id = $order->id;
                

                $obj = new helpers();
                $wholesale_order_number = 'WORD-'.$obj->random_string(5).'-'.$wholesale_last_order_id;   // Generate random String for order number
                $update_order_number = DB::table('orders')
                                            ->where('id', $wholesale_last_order_id)
                                            ->update(['order_number' => $wholesale_order_number]);

                Session::put('wholesale_order_number',$wholesale_order_number);
                Session::put('wholesale_order_id',$wholesale_last_order_id);

                foreach($allCart as $eachCart)
                {
                    $product_details = DB::table('products')->where('id',$eachCart->product_id)->first();
                   // echo $each_content->brandmember_id; exit;
                    $brandmember_deatils = DB::table('products')
                                        ->leftJoin('brandmembers', 'brandmembers.id', '=', 'products.brandmember_id')
                                        ->select('products.*', 'brandmembers.fname', 'brandmembers.lname', 'brandmembers.username','brandmembers.email', 'brandmembers.slug', 'brandmembers.pro_image', 'brandmembers.brand_details', 'brandmembers.brand_sitelink', 'brandmembers.status', 'brandmembers.admin_status')
                                        ->where('products.id','=',$eachCart->product_id)
                                        ->first();
                                        //echo "<pre>";print_r($brandmember_deatils); exit;
                                        //echo $brandmember->slug ; exit;
                    $brand_member_name = ($brandmember_deatils->fname)?($brandmember_deatils->fname.' '.$brandmember_deatils->lname):$brandmember_deatils->username;

                    $formfactor = DB::table('form_factors')->where('id','=',$eachCart->form_factor)->first();
                    

                    if($eachCart->is_wholesale == 1)
                    {
                        $order_item = OrderItems::create([
                            'order_id'              => $wholesale_last_order_id,
                            'brand_id'              => $brandmember_deatils->brandmember_id,
                            'brand_name'            => $brand_member_name,
                            'brand_email'           => $brandmember_deatils->email,
                            'product_id'            => $eachCart->product_id,
                            'product_name'          => $eachCart->product_name,
                            'product_image'         => $product_details->image1,
                            'quantity'              => $eachCart->quantity,
                            'price'                 => $eachCart->amount,
                            'form_factor_id'        => $formfactor->id,
                            'form_factor_name'      => $formfactor->name,
                            'duration'              => $eachCart->duration,
                            'no_of_days'            => $eachCart->no_of_days
                        ]);
                    }



                    
                }

                /*

                $wholesale_order_list = DB::table('orders')
                                    ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                                    ->leftJoin('brandmembers', 'brandmembers.id', '=', 'orders.user_id')
                                    ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name','order_items.brand_email', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name', 'brandmembers.fname', 'brandmembers.lname', 'brandmembers.email', 'brandmembers.username', 'brandmembers.phone_no')
                                    ->where('orders.id','=',$wholesale_last_order_id)
                                    ->get();

                //print_r($wholesale_order_list);

              
                    
                $ord_num=$wholesale_order_number;
                
                $ord_ammount=$wholesale_order_total;

                $miramix_order = DB::table('sitemessages')->where('slug','customer_order_placed_notification')->first();

                $sms_text=$miramix_order->sms_text;
                
                $email_subject=trim($miramix_order->subject);
                
                $email_text=html_entity_decode($miramix_order->email_text);

                if(strpos($email_text,"[order_id]")==true)
                {
                    $email_text=str_replace("[order_id]", $ord_num, $email_text);
                }

                if(strpos($email_text,"[fullname]")==true)
                {
                    $email_text=str_replace("[fullname]", $name, $email_text);
                }

                if(strpos($email_text,"[order_amount]")==true)
                {
                    $email_text=str_replace("[order_amount]", $ord_ammount, $email_text);   
                }

                if(strpos($email_text,"[fullname]")==true)
                {
                    $email_text=str_replace("[fullname]", $name, $email_text);
                }

                $serialize_add = unserialize($wholesale_order_list[0]->shiping_address_serialize);
                $user_phone = $serialize_add['phone'];
                $user_check_email = $serialize_add['email'];
                $preffered_communication=$wholesale_order_list[0]->preffered_communication;



                $name = $wholesale_order_list[0]->fname.' '.$wholesale_order_list[0]->lname;
                $username = $wholesale_order_list[0]->username;
                if($name!='')
                    $mailing_name = $name;
                else
                    $mailing_name = $username;

                $user_email = $wholesale_order_list[0]->email; 

                $mobile =  $wholesale_order_list[0]->phone_no; // logged user  phone number


            
                // Get the Mobile Number

                if($mobile=='')
                {

                    $mobile=$user_phone;
                 
                }
                

               
                if($preffered_communication==0) 
                {

                    
                    $sent = Mail::send('frontend.checkout.order_details_mail', array('admin_users_email'=>$admin_users_email,'receiver_name'=>$mailing_name,'email'=>$user_check_email,'order_list'=>$wholesale_order_list,'email_text'=>$email_text), 
                    function($message) use ($admin_users_email, $user_check_email, $mailing_name, $email_subject)
                    {
                        $message->from($admin_users_email);  //support mail
                        $message->to($user_check_email, $mailing_name)->subject($email_subject);
                    });


                    if(!$sent) 
                    {
                      Session::flash('error', 'something went wrong!! Mail not sent.'); 
                      //return redirect('member-forgot-password');
                    }
                    else
                    {
                      Session::flash('success', 'Your order successfully placed.'); 
                      //return redirect('memberLogin');
                    }

                } else {

                 
                    if($mobile !='')
                    {

                        $sms_text= str_replace("[order_id]", $ord_num,$sms_text);
                        $order_message= str_replace("[order_amount]", $ord_ammount,$sms_text);
                        $order_message= html_entity_decode($order_message);

                        Twilio::message('+1'.$mobile, $order_message);

                    } 
                }

                $admin_user = DB::table('users')->first();

                $admin_email = $admin_user->email;

                if(($admin_user->name)!='')
                    $admin_name = $admin_user->name;
                else
                    $admin_name = 'Admin';


                //echo "Admin Name: ".$admin_name;
                //echo "Admin Email: ".$admin_email;

                $sent_admin = Mail::send('frontend.checkout.admin_order_details_mail', array('admin_users_email'=>$admin_users_email,'receiver_name'=>$admin_name,'admin_email'=>$admin_email,'order_list'=>$wholesale_order_list), 
                function($message) use ($admin_users_email, $admin_email, $admin_name)
                {
                    $message->from($admin_users_email);  //support mail
                    $message->to($admin_email, $admin_name)->subject('Miramix Order Details For Admin');
                });*/


            }  // END OF WHOLESALE ORDERS

            //exit;



            //All Cart deleted from cart table after inserting all data to order and order_item table.
            if(isset($user_id))
                $deleteCart =  DB::table('carts')->where('user_id', '=', $user_id)->delete();
                
            Cart::destroy(); // After inserting all cart data into Order and Order_item Table database                 
            //set points for users on purchase


         
            if(Session::get('payment_method') =='creditcard')     // if Payment With Credit Card 
            {
                return redirect('/checkout-authorize/'.$last_order_id);
            }
            elseif(Session::get('payment_method') =='paypal')    // if Payment With Paypal Account 
            {
                return redirect('/checkout-paypal/'.$last_order_id);    
            }
                
            } //end of post       
            
            
    }



    public function checkoutWholesale()
    {

        //dd(Input:all());

        $wholesale_orders = array(); 

        ///$wholesale_sub_total = 0;
        $is_wholesale = 0; //Flag to check if there is at least one wholesale order
       
        $wholesale_order_total = 0;

        $shp_address = array();

        if(Input::has("is_wholesale_accept"))        
        {
            Session::put('is_wholesale_accept', 1);
        }
        else
        {
            Session::put('is_wholesale_accept', 0);
        }


        if(Session::has('selected_address_id'))
        {
            $shp_address = DB::table('addresses')
                    ->leftjoin('countries', 'countries.country_id', '=', 'addresses.country_id')
                    ->leftjoin('zones', 'zones.zone_id', '=', 'addresses.zone_id')
                    ->select('addresses.*', 'countries.name as country_name','countries.iso_code_3 as country_code', 'zones.name as zone_name', 'zones.code as zone_code')
                    ->where('mem_brand_id',Session::get('brand_userid'))
                    ->where('id', Session::get('selected_address_id'))
                    ->first();
        }
        else
        {
            $shp_address = DB::table('addresses')
                    ->leftjoin('countries', 'countries.country_id', '=', 'addresses.country_id')
                    ->leftjoin('zones', 'zones.zone_id', '=', 'addresses.zone_id')
                    ->select('addresses.*', 'countries.name as country_name','countries.iso_code_3 as country_code', 'zones.name as zone_name', 'zones.code as zone_code')
                    ->where('mem_brand_id', Session::get('brand_userid'))
                    ->orderBy('id','DESC')
                    ->first();

        }

        //dd($shp_address);



        if(Session::has('brand_userid'))
        {
            $user_id=Session::get('brand_userid');
            $allCart = DB::table('carts')->where('user_id',Session::get('brand_userid'))->get();

            //dd($allCart);

            foreach($allCart as $eachCart)
            {
                // Enter the data if not a wholesale order
                // The coulmn of 
                if($eachCart->is_wholesale == 1)
                {
                    
                    Session::put('order_is_wholesale', 1);

                    $amount = $eachCart->quantity * $eachCart->amount;

                    $wholesale_order_total += $amount;
                }
                $is_wholesale = 1;
                
            }

            //dd($wholesale_order_total);
        
        

            //******************** WHOLESALE ORDERS STARTS ********************//
            // Now insert all wholesale orders
            if($is_wholesale == 1 && floatval($wholesale_order_total) > 0)
            {

                // Serialize the Shipping Address because If user delete there address from "addresses" table,After that the address also store in the "order" table for  getting order history//
                $shiping_address = array('address_title'            => $shp_address->address_title,
                                        'mem_brand_id'              => $shp_address->mem_brand_id,
                                        'first_name'                => $shp_address->first_name,
                                        'last_name'                 => $shp_address->last_name,
                                        'email'                     => $shp_address->email,
                                        'phone'                     => $shp_address->phone,
                                        'address'                   => $shp_address->address,
                                        'address2'                  => $shp_address->address2,
                                        'city'                      => $shp_address->city,
                                        'zone_id'                   => $shp_address->zone_code, // two digit code //
                                        'country_id'                => $shp_address->country_code,  // three digit code iso_code_3//
                                        'postcode'                  => $shp_address->postcode
                                        );

                $shiping_address_serial = serialize($shiping_address);


                $order= Order::create([
                                    'order_total'               => $wholesale_order_total,
                                    'sub_total'                 => $wholesale_order_total,
                                    'discount'                  => 0,
                                    'share_discount'            => 0,
                                    'total_discount'            => 0,
                                    'redeem_amount'             => 0,
                                    'order_status'              => 'pending',
                                    'shipping_address_id'       => $shp_address->id,
                                    'shipping_cost'             => 0,
                                    'shipping_type'             => 'flat',
                                    'user_id'                   => $user_id,
                                    'ip_address'                => $_SERVER['REMOTE_ADDR'],
                                    'payment_method'            => 'creditcard',
                                    'preffered_communication'   => Session::get('preffered_communication'),
                                    'transaction_id'            => '',
                                    'transaction_status'        => '',
                                    'is_wholesale'              => 1,
                                    'shiping_address_serialize' => $shiping_address_serial,
                                    'created_at' => date('Y-m-d H:s:i'),
                                    'updated_at' => date('Y-m-d H:s:i')
                                ]);

                $wholesale_last_order_id = $order->id;
                

                $obj = new helpers();
                $wholesale_order_number = 'WORD-'.$obj->random_string(5).'-'.$wholesale_last_order_id;   // Generate random String for order number
                $update_order_number = DB::table('orders')
                                            ->where('id', $wholesale_last_order_id)
                                            ->update(['order_number' => $wholesale_order_number]);

                Session::put('order_number',$wholesale_order_number);
                Session::put('order_id',$wholesale_last_order_id);

                foreach($allCart as $eachCart)
                {
                    $product_details = DB::table('products')->where('id',$eachCart->product_id)->first();
                   // echo $each_content->brandmember_id; exit;
                    $brandmember_deatils = DB::table('products')
                                        ->leftJoin('brandmembers', 'brandmembers.id', '=', 'products.brandmember_id')
                                        ->select('products.*', 'brandmembers.fname', 'brandmembers.lname', 'brandmembers.username','brandmembers.email', 'brandmembers.slug', 'brandmembers.pro_image', 'brandmembers.brand_details', 'brandmembers.brand_sitelink', 'brandmembers.status', 'brandmembers.admin_status')
                                        ->where('products.id','=',$eachCart->product_id)
                                        ->first();
                                        //echo "<pre>";print_r($brandmember_deatils); exit;
                                        //echo $brandmember->slug ; exit;
                    $brand_member_name = ($brandmember_deatils->fname)?($brandmember_deatils->fname.' '.$brandmember_deatils->lname):$brandmember_deatils->username;

                    $formfactor = DB::table('form_factors')->where('id','=',$eachCart->form_factor)->first();
                    

                    if($eachCart->is_wholesale == 1)
                    {
                        $order_item = OrderItems::create([
                            'order_id'              => $wholesale_last_order_id,
                            'brand_id'              => $brandmember_deatils->brandmember_id,
                            'brand_name'            => $brand_member_name,
                            'brand_email'           => $brandmember_deatils->email,
                            'product_id'            => $eachCart->product_id,
                            'product_name'          => $eachCart->product_name,
                            'product_image'         => $product_details->image1,
                            'quantity'              => $eachCart->quantity,
                            'price'                 => $eachCart->amount,
                            'form_factor_id'        => $formfactor->id,
                            'form_factor_name'      => $formfactor->name,
                            'duration'              => $eachCart->duration,
                            'no_of_days'            => $eachCart->no_of_days
                        ]);
                    }



                    
                }

            }


            

            /******** Custom Message For Email and SMS ***********/
            $sitesettings = DB::table('sitesettings')->get();
            $all_sitesetting = array();
            foreach($sitesettings as $each_sitesetting)
            {
                $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
            }

            if(isset($wholesale_last_order_id) && $wholesale_last_order_id != "")
            {


                

                $order_list = DB::table('orders')
                                    ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                                    ->leftJoin('brandmembers', 'brandmembers.id', '=', 'orders.user_id')
                                    ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name','order_items.brand_email', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name', 'brandmembers.fname', 'brandmembers.lname', 'brandmembers.email', 'brandmembers.username', 'brandmembers.phone_no')
                                    ->where('orders.id','=',$wholesale_last_order_id)
                                    ->get();


                /////////////////
                // In case of successful payment email or SMS as per user choice
                $this->sendCommunication($order_list);

            }


            //All Cart deleted from cart table after inserting all data to order and order_item table.
            if(isset($user_id))
                $deleteCart =  DB::table('carts')->where('user_id', '=', $user_id)->delete();
                
            Cart::destroy(); // After inserting all cart data into Order and Order_item Table database                 
            //set points for users on purchase

            return redirect('checkout-success');

        }


    }


    public function checkoutPaypal($id)
    {
        /* Site Setting All details */
        $sitesettings = DB::table('sitesettings')->get();
        $all_sitesetting = array();
        foreach($sitesettings as $each_sitesetting)
        {
            $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
        }

        $order_id = $id;
        $order_list = DB::table('orders')
                    ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                    ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name')
                    ->where('orders.id','=',$order_id)
                    ->get();

        Session::put('miramix_order_id',$order_id); // use for paypal cancel
        // echo "<pre>";
        // print_r($order_list);
        // exit;
        return view('frontend.checkout.checkout_paypalpage',compact('body_class','order_list'),array('title'=>'MIRAMIX | Checkout-Paypal'));
        //echo "boom";
    }
        
    public function paypalNotify()
    {

        $notify_email = "";

        $sitesettings = DB::table('sitesettings')->get();
        $all_sitesetting = array();
        foreach($sitesettings as $each_sitesetting)
        {
            $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
        }
        

        if(isset($all_sitesetting['notify_email']) && $all_sitesetting['notify_email'] <> "")
            $notify_email = $all_sitesetting['notify_email'];
    
        if($notify_email <> "")
            @mail($notify_email, "enter notify", "Paypal Response<pre>".print_r($_POST,true));    
        // Call ipn
        $req = 'cmd=_notify-validate';
        foreach ($_POST as $key => $value) 
        {
            $value = urlencode(stripslashes($value));
            $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
            $req .= "&$key=$value";
        }
            // assign posted variables to local variables
            $data['payment_status']     = $_POST['payment_status'];
            $data['payment_amount']     = $_POST['mc_gross'];
            $data['mc_shipping']        = $_POST['mc_shipping'];
            $data['mc_handling']        = $_POST['mc_handling'];
            $data['payment_currency']   = $_POST['mc_currency'];
            $data['txn_id']             = $_POST['txn_id'];
            $data['receiver_email']     = $_POST['receiver_email'];
            $data['payer_email']        = $_POST['payer_email'];
            $cnt                        = explode(",",$_POST['custom']);
            
            if($notify_email <> "")
                @mail($notify_email, "paypalnotify", "Paypal Response<br />data = <pre>".print_r($data, true)."</pre>");

            $user_id =  $cnt[0];    //User_id
            $order_id = $cnt[1];    //Order_id
        // For Paypal Payment Only //
        
        $admin_users_email = $all_sitesetting['email']; // Admin Support Email
        $payment_method =  $all_sitesetting['payment_mode']; 

        if($all_sitesetting['payment_mode'] == 'test')
        {
            $paypal_host = $all_sitesetting['paypal_host_test'];
        }
        else if($all_sitesetting['payment_mode'] == 'live')
        {
            $paypal_host = $all_sitesetting['paypal_host_live'];
        }

        $pay_date=date('l jS \of F Y h:i:s A');

        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Host: ".$paypal_host."\r\n";
        //$header .= "Host: www.paypal.com:443\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        //$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);   
        $fp = fsockopen ('ssl://'.$paypal_host, 443, $errno, $errstr, 30);  
        //$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
        if (!$fp) 
        {
            // HTTP ERROR
            if($notify_email <> "")
                @mail($notify_email, "fshok error", "Invalid Response<br />data = <pre>".print_r($_POST, true)."</pre>");
        } 
        else 
        {
            if($notify_email <> "")
                @mail($notify_email, "Me PAYPAL DEBUGGING1", "Invalid Response<br />data = <pre>".print_r($_POST, true)."</pre>");
            
            fputs ($fp, $header . $req);
            while (!feof($fp)) 
            {
                $res = fgets ($fp, 1024);
                $order=DB::table('orders')->where('id', $order_id)->first();
                $status=$order->order_status;
                
                if (strcmp($res, "VERIFIED") == 0 && $status=='pending') 
                {
                    @mail($notify_email, "notify1-completed", "valid Response<br />data = <pre>".print_r($data, true)."</pre>");
                    
                    

                    $transaction_status =$_POST['payment_status'];
                    $update_order = DB::table('orders')
                                    ->where('id', $order_id)
                                    ->update(['order_status'=>'processing','transaction_id' => $_POST['txn_id'],'transaction_status'=>$transaction_status]);

                    // Order details for perticular order id 
                    $order_list = DB::table('orders')
                                ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                                ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name','order_items.brand_email', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name')
                                ->where('orders.id','=',$order_id)
                                ->get();


                    if($user_id!=''){

                        $user_details = DB::table('brandmembers')->where('id', $user_id)->first();
                        
                        
                        $order_total=$order->sub_total;
                        $price_for_point = DB::table('sitesettings')->where('name','price_for_point')->first();
                        $points=round($order_total/$price_for_point->value)+$user_details->user_points;
                        if($order->redeem_amount>0){
                        $points=$points-($order->redeem_amount/$price_for_point->value);
                        }
                        DB::table('brandmembers')
                                ->where('id', $user_id)
                                ->update(['user_points' => $points]);
                    

                    } 

                    // Paypal notify email or SMS as per user choice
                    $this->sendCommunication($order_list);

                    // In case of wholesale orders
                    if(Session::has("wholesale_order_id"))
                    {
                        $wholesale_order_id = Session::get("wholesale_order_id");


                        $wholesale_order_list = DB::table('orders')
                            ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                            ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name','order_items.brand_email', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name')
                            ->where('orders.id','=',$wholesale_order_id)
                            ->get();


                        // Paypal notify email or SMS as per user choice
                        $this->sendCommunication($wholesale_order_list);

                        

                    }



                    
                }
                if (strcmp ($res, "INVALID") == 0) 
                {
                    // Used for debugging
                    if($notify_email <> "")
                        @mail($notify_email, "Me PAYPAL DEBUGGING2", "Invalid Response<br />data = <pre>".print_r($_POST, true)."</pre>");
                    $order_id = $order_id;      //Order_id
                    //echo $order_id;
                    $transaction_status =$_POST['payment_status'];
                    $update_order = DB::table('orders')
                                    ->where('id', $order_id)
                                    ->update(['order_status'=>'cancel','transaction_status'=>$transaction_status]);

                    // Order details for perticular order id 
                    $order_list = DB::table('orders')
                                ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                                ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name','order_items.brand_email', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name')
                                ->where('orders.id','=',$order_id)
                                ->get();
                    
                    
                    // Send Cancel Email
                    $this->sendCommunication($order_list, "frontend.checkout.order_cancel_mail", "customer_order_cancelled_notification");
    




                }
            }   //end of while
            fclose ($fp);
        }//end of if
        
    }

    public function success()
    {

        if (Session::has('coupon_code'))
        {
            Session::forget('coupon_code');
        }
        elseif(Session::has('coupon_type'))
        {
            Session::forget('coupon_type');
        }
        elseif(Session::has('coupon_discount'))
        {
            Session::forget('coupon_discount');
        }
        elseif(Session::has('coupon_amount'))
        {
            Session::forget('coupon_amount');
        }

        //SuccessFull payment View
        $xsrfToken = app('Illuminate\Encryption\Encrypter')->encrypt(csrf_token());  

        // For Paypal Payment Only //
        $sitesettings = DB::table('sitesettings')->get();
        $all_sitesetting = array();
        foreach($sitesettings as $each_sitesetting)
        {
            $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
        }
        
        $admin_users_email = $all_sitesetting['email']; // Admin Support Email
        $payment_method =  $all_sitesetting['payment_mode'];

        if(Session::get('payment_method')=='paypal')
        {


            if(Request::isMethod('post'))
            { 
                $custom = explode(',',$_POST['custom']); //Return From Paypal
                $user_id =  $custom[0];                 //User_id
                $order_id = $custom[1];                 //Order_id

                $order = DB::table('orders')->where('id', $order_id)->first();

                //dd($order);

                $status=$order->order_status;

                if($status=='pending'){ 
                    $transaction_status =$_POST['payment_status'];
                    $update_order = DB::table('orders')
                    ->where('id', $order_id)
                    ->update(['order_status'=>'processing','transaction_id' => $_POST['txn_id'],'transaction_status'=>$transaction_status]);

                    /* Order details for perticular order id */
                    $order_list = DB::table('orders')
                    ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                    ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name','order_items.brand_email', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name')
                    ->where('orders.id','=',$order_id)
                    ->get();

                    

                    // Get Phone and Mobile Number from Order
                    $serialize_add = unserialize($order_list[0]->shiping_address_serialize);
                    $user_phone = $serialize_add['phone'];
                    $user_check_email = $serialize_add['email'];
                    $preffered_communication=$order_list[0]->preffered_communication;

                    Session::put('order_number',$order_list[0]->order_number);
                    Session::put('order_id',$order_id);

                // Checkout User Id changed
                if(Session::has('checkout_userid')){
                    
                    
                    $user_details = DB::table('brandmembers')->where('id', Session::get('checkout_userid'))->get();

                    //update user's points
                    $order_total=$order->sub_total;
                    $price_for_point = DB::table('sitesettings')->where('name','price_for_point')->first();
                    $points = round($order_total/$price_for_point->value)+$user_details->user_points;
                
                    if($order->redeem_amount>0){
                        $points=$points-($order->redeem_amount/$price_for_point->value);
                    }
                
                    DB::table('brandmembers')
                            ->where('id', Session::get('member_userid'))
                            ->update(['user_points' => $points]);

                } else {

                    // For guest checkout
                    /*$guestdata=Session::get('guest_array');
                    $name = $guestdata["guest_fname"].' '.$guestdata["guest_lname"];
                    $username = Session::get('guest_username_sess');
                    $mailing_name = $name;
                    $user_check_email = $guestdata["guest_email"]; 
                    $mobile =  $guestdata["guest_phone"]; // logged user  phone number*/
                }

                /******** Custom Message For Email and SMS ***********/


                
                $this->sendCommunication($order_list);


                //dd(Session::get('is_wholesale_order'));


                if(Session::has("wholesale_order_id"))
                {


                    $wholesale_order_id = Session::get("wholesale_order_id");

                   
                    $update_order = DB::table('orders')
                            ->where('id', $wholesale_order_id)
                            ->update(['wholesale_status'=>'offered','is_wholesale'=>0]);
                    
                    


                    $wholesale_order_list = DB::table('orders')
                        ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                        ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name','order_items.brand_email', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name')
                        ->where('orders.id','=',$wholesale_order_id)
                        ->get();



                    $this->sendCommunication($wholesale_order_list);

                    
                    

                }


               
               }
            }
        }
    
    

        /* ========================= Remove session ==================================== */ 
            Session::forget('payment_method');
            Session::forget('step3');
            Session::forget('step1');
            Session::forget('guest_array');
            Session::forget('guest');
            Session::forget('coupon_code');
            Session::forget('coupon_type');
            Session::forget('coupon_amount');
            Session::forget('coupon_discount');
            Session::forget('share_coupon_status');
            Session::forget('share_product_id');
            Session::forget('force_social_share');
            //Session::forget('wholesale_order_id');
            //Session::forget('wholesale_order_number');

            if(Session::has('order_id'))
            {
                $order_id = Session::get('order_id');
            }

            if(Session::get('wholesale_order_id'))
            {
                $wholesale_order_id = Session::has('wholesale_order_id');
            }



        /* ========================= End Remove session ==================================== */ 
        return view('frontend.checkout.pyament_success',array('title'=>'MIRAMIX | Checkout-Success'))->with('srf_token', $xsrfToken);
    }
   

    public function cancel()
    {
    
        // For Paypal Payment Only //
        $sitesettings = DB::table('sitesettings')->get();
        $all_sitesetting = array();
        foreach($sitesettings as $each_sitesetting)
        {
            $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
        }
        
        $admin_users_email = $all_sitesetting['email']; // Admin Support Email
        $payment_method =  $all_sitesetting['payment_mode'];
        
        if(Session::get('payment_method')=='paypal')
        {
                                                    
            $order_id = Session::get('miramix_order_id');               //Order_id
            //echo $order_id;
            $transaction_status ='Canceled';
            $update_order = DB::table('orders')
                            ->where('id', $order_id)
                            ->update(['order_status'=>'cancel','transaction_status'=>$transaction_status]);

            /* Order details for perticular order id */
            $order_list = DB::table('orders')
                        ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
                        ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name','order_items.brand_email', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name')
                        ->where('orders.id','=',$order_id)
                        ->get();
            //print_r($order_list); exit;

            // Get Phone and Mobile Number and preffered communication from Order

            $serialize_add = unserialize($order_list[0]->shiping_address_serialize);
            
            
            $user_phone = $serialize_add['phone'];
            $user_check_email = $serialize_add['email'];
            $preffered_communication=$order_list[0]->preffered_communication;

            // End Get Details //

            Session::put('order_number',$order_list[0]->order_number);
            Session::put('order_id',$order_id);

            if(Session::has('member_userid')){
                $user_details = DB::table('brandmembers')->where('id', Session::get('member_userid'))->first();
                
                $name = $user_details->fname.' '.$user_details->lname;
                $username = $user_details->username;
                if($name!='')
                    $mailing_name = $name;
                else
                    $mailing_name = $username;

                $user_email = $user_details->email; 

                 $mobile =  $user_details->phone_no; // logged user  phone number
            
            // Get the Mobile Number

                if($mobile=='')
                {

                    $mobile=$user_phone;
                 
                }

            }else{
                //for guest checkout
                $guestdata=Session::get('guest_array');
                $name = $guestdata["guest_fname"].' '.$guestdata["guest_lname"];
                $username = Session::get('guest_username_sess');
                $mailing_name = $name;
                $user_check_email = $guestdata["guest_email"]; 
                $mobile =  $guestdata["guest_phone"]; // logged user  phone number
            }

        /* ========================= Remove session ==================================== */ 
            Session::forget('payment_method');
            Session::forget('step3');
            Session::forget('step1');
            Session::forget('guest_array');
            Session::forget('guest');
            Session::forget('coupon_code');
            Session::forget('coupon_type');
            Session::forget('coupon_discount');
            Session::forget('coupon_amount');
            Session::forget('share_coupon_status');
            Session::forget('share_product_id');
            Session::forget('force_social_share');
            Session::forget('wholesale_order_id');
            Session::forget('wholesale_order_number');

        /* ========================= End Remove session ==================================== */ 


            // Send Cancel Email
            $this->sendCommunication($order_list, "frontend.checkout.order_cancel_mail", "customer_order_cancelled_notification");

        }
        return view('frontend.checkout.payment_cancel',array('title'=>'MIRAMIX | Checkout-Cancel'));
    }

    public function checkoutAuthorize($id)
    {


    	//dd(Session::all());

        if(Session::has('brand_userid')) 
        {       
            $user_id2 = Session::get('brand_userid');
        }
        else if(Session::has('member_userid'))
        {
           $user_id2 = Session::get('member_userid');
        }
        else
        {
           $user_id2 = NULL;   
        }
        //echo $user_id2;

        Session::put('checkout_userid', $user_id2);

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

        //echo 'cn='.Session::get('card_number').' card_exp_month= '.Session::get('card_exp_month').'  pm= '.Session::get('payment_method'); exit;
        $order_id = $id;
        $order_details = DB::table('orders')->where('id',$order_id)->first();

        /* Order details for perticular order id */

        $order_list = DB::table('orders')
        ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
        ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name','order_items.brand_email', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name')
        ->where('orders.id','=',$order_id)
        ->get();

        // Get Phone and Mobile Number and preffered communication from Order

        $serialize_add = unserialize($order_list[0]->shiping_address_serialize);
        $user_phone = $serialize_add['phone'];
        $user_check_email = $serialize_add['email'];

        //dd($user_check_email);

        $preffered_communication=$order_list[0]->preffered_communication;
    

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
            "x_card_num"        => Session::get('card_number'), //"4042760173301988", $card_number
            "x_exp_date"        => Session::get('card_exp_month').Session::get('card_exp_year'),                //$card_exp_month.$card_exp_year 
            "x_amount"          => $order_details->order_total,
            "x_description"     => "Miramix Transaction"
        
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

        //echo "<pre>"; print_r($response_array); exit;



        if($response_array[0] == ''){
            Session::flash('error', 'Something is wrong here!!!');
            Session::put('order_number',$order_list[0]->order_number);
            Session::put('order_id',$order_id);

        /* ========================= Remove session ==================================== */ 
            Session::forget('payment_method');
            Session::forget('step3');
            Session::forget('step1');
            Session::forget('guest_array');
            Session::forget('guest');
            Session::forget('coupon_code');
            Session::forget('coupon_type');
            Session::forget('coupon_discount');
            Session::forget('coupon_amount');
            Session::forget('share_coupon_status');
            Session::forget('share_product_id');
            Session::forget('force_social_share');
            
            Session::forget('wholesale_order_id');
            Session::forget('wholesale_order_number');

        /* ========================= End Remove session ==================================== */ 

        //Session::get('brand_userid');

            return redirect('/checkout-cancel'); 
        }

        if($response_array[0] == 1)
        { 


            $transaction_status ="Completed";
            $update_order = DB::table('orders')
                            ->where('id', $order_id)
                            ->update(['order_status'=>'processing','card_type'=>$response_array[51],'card_number' => $response_array[50],'transaction_id' => $response_array[6],'transaction_status'=>$transaction_status,'response_code'=>$response_array[0]]);

            $mobile = '';
            
            if(Session::has('checkout_userid')) {

                $user_id = Session::get('checkout_userid');

                //dd($user_id);

                $user_details = DB::table('brandmembers')->where('id', $user_id)->first();

                //dd($user_details);
                
                

            
                // Update user's points
                $order_total=$order_details->sub_total;
                $price_for_point = DB::table('sitesettings')->where('name','price_for_point')->first();
                $points=round($order_total/$price_for_point->value)+$user_details->user_points;
                
                if($order_details->redeem_amount>0){
                    $points=$points-($order_details->redeem_amount/$price_for_point->value);
                }
                
                DB::table('brandmembers')
                            ->where('id', Session::get('checkout_userid'))
                            ->update(['user_points' => $points]);

            }
            
            /*else
            {
                //for guest checkout
                $guestdata=Session::get('guest_array');
                $name = $guestdata["guest_fname"].' '.$guestdata["guest_lname"];
                $username = Session::get('guest_username_sess');
                $mailing_name = $name;
                $user_check_email = $guestdata["guest_email"]; 
                $mobile =  $guestdata["guest_phone"]; // logged user  phone number
            }*/




            /******** Custom Message For Email and SMS ***********/
            $this->sendCommunication($order_list);

            if(Session::has("wholesale_order_id"))
            {
            	$wholesale_order_id = Session::get("wholesale_order_id");

                if(Session::has("is_wholesale_accept") && Session::get("is_wholesale_accept") == 1)
                {
                $update_order = DB::table('orders')
                        ->where('id', $wholesale_order_id)
                        ->update(['wholesale_status'=>'accepted','is_wholesale'=>0]);
                }

            	$wholesale_order_list = DB::table('orders')
			        ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id')
			        ->select('orders.*', 'order_items.brand_id', 'order_items.brand_name','order_items.brand_email', 'order_items.product_id', 'order_items.product_name', 'order_items.product_image', 'order_items.quantity', 'order_items.price', 'order_items.form_factor_id', 'order_items.form_factor_name')
			        ->where('orders.id','=',$wholesale_order_id)
			        ->get();

                //dd($wholesale_order_list);

            	$this->sendCommunication($wholesale_order_list);

                //Session::forget('wholesale_order_id');
                //Session::forget('wholesale_order_number');
                    

            }
            

           

            //Session::put('wholesale_order_id',$order_id);

            Session::put('order_number',$order_list[0]->order_number);
            Session::put('order_id',$order_id);
            /* ========================= Remove session ==================================== */
            //if(Session::has('wholesale_order_id')) 
            //    Session::forget('wholesale_order_id');
            
            if(Session::has('is_wholesale_order'))
                Session::forget('is_wholesale_order');
            
            Session::forget('payment_method');
            Session::forget('step3');
            Session::forget('step1');
            Session::forget('guest_array');
            Session::forget('guest');
            Session::forget('coupon_code');
            Session::forget('coupon_type');
            Session::forget('coupon_discount');
            Session::forget('coupon_amount');
            Session::forget('share_coupon_status');
            Session::forget('share_product_id');
            Session::forget('force_social_share');
            /* ========================= End Remove session ==================================== */ 

            return redirect('/checkout-success'); 
        }
        else
        {
            $transaction_status = "cancel";
            $update_order = DB::table('orders')
                            ->where('id', $order_id)
                            ->update(['order_status'=>'cancel','card_type'=>$response_array[51],'card_number' => $response_array[50],'transaction_id' => $response_array[6],'transaction_status'=>$transaction_status,'response_code'=>$response_array[0]]);
            $msg = $response_array[3];
            Session::flash('error', $msg);
            Session::put('order_number',$order_list[0]->order_number);
            Session::put('order_id',$order_id);
            /* ========================= Remove session ==================================== */ 
            Session::forget('payment_method');
            Session::forget('step3');
            Session::forget('step1');
            Session::forget('guest_array');
            Session::forget('guest');
            Session::forget('coupon_code');
            Session::forget('coupon_type');
            Session::forget('coupon_discount');
            Session::forget('coupon_amount');
            Session::forget('share_coupon_status');
            Session::forget('share_product_id');
            Session::forget('force_social_share');
            /* ========================= End Remove session ==================================== */ 

            return redirect('/checkout-cancel'); 
        }   
    }

    /* update Cart Start */

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
                    if(Session::has('member_userid'))
                        $insert_cart = DB::table('carts')->insert(['user_id' => Session::get('member_userid'), 'row_id' => $cartRowId, 'product_id' => $product_id , 'product_name' => $product_name, 'quantity' => $product_quantity, 'amount' => $product_price, 'duration' => $product_duration, 'no_of_days' => $product_no_of_days, 'form_factor' => $product_form_factor,'sub_total' => $subtotal, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                    else if(Session::has('brand_userid'))
                        $insert_cart = DB::table('carts')->insert(['user_id' => Session::get('brand_userid'), 'row_id' => $cartRowId, 'product_id' => $product_id , 'product_name' => $product_name, 'quantity' => $product_quantity, 'amount' => $product_price, 'duration' => $product_duration, 'no_of_days' => $product_no_of_days, 'form_factor' => $product_form_factor,'sub_total' => $subtotal, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
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

    /* update Cart End */
    
    public function uspsAddressValidate(){
    
     $street = Request::input('street');
     $city = Request::input('city');
     $state = Request::input('state');
     $zip = Request::input('zip');
     
     $status=Usps::varifyaddress(array("Address1"=>$street,"Address2"=>$street,"City"=>$city,"State"=>$state,"Zip4"=>$zip));
     echo $status;
    
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
    
    public function socialShareCheckout()
    {
        // If Social Share from Cart Page and Checkout Page.
        if(Input::get('product_id') == 'social_share')
        {
          Session::put('force_social_share','social_share'); 
          echo 1; exit;
        }
    }


}
