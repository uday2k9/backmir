<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/

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
use Twilio;
use Mail;
use App\Helper\helpers;
use App\Model\Sitesetting;
use App\Model\Sitemessage;


class BaseController extends Controller {

	public function __construct() 
    {
    	if(Session::has('member_userid'))
    	{
             // Logged as a member
            $cart_value = DB::table('carts')
			                    ->where('user_id','=',Session::get('member_userid'))
			                    ->sum('quantity');
        }
        else if(Session::has('brand_userid'))
        {
             // Logged as a member
            $cart_value = DB::table('carts')
                                ->where('user_id','=',Session::get('brand_userid'))
                                ->sum('quantity');
        }
        else
        {
        	$cart_value ='';
        }

        $obj = new helpers();

        $content = $obj->content();

        //$cartcontent = Cart::content();

        $cart_value = count($content);



        view()->share('cart_value',$cart_value);
	
	
        //define("AUTHORIZENET_API_LOGIN_ID", "32px8XM76GZg");
        //define("AUTHORIZENET_TRANSACTION_KEY", "9PLV89n5LPD9dx55");
	    define("AUTHORIZENET_API_LOGIN_ID", "6Z7S5dmfD");
        define("AUTHORIZENET_TRANSACTION_KEY", "2uKS73by9W9Rw3mN");
        define("AUTHORIZENET_SANDBOX", false);
        
        $getHelper = new helpers();
        view()->share('getHelper',$getHelper);

        /* All Site Settings List */
        $sitesettings = DB::table('sitesettings')->get();
        $all_sitesetting = array();
        foreach($sitesettings as $each_sitesetting)
        {
            $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
        }
        
	    view()->share('all_sitesetting',$all_sitesetting);

       
        
    }

    public function index(){
    	
    }

    
    public function sendCommunication($order_list, $template_name = "", $message_id = "") {
        
        if($template_name == "")
            $template_name = "frontend.checkout.order_details_mail";

        if($message_id == "")
            $message_id = "customer_order_placed_notification";
        
        /// Get Order List from function parameter
        if(isset($order_list[0]) && count($order_list[0]) > 0)
        {

            $mobile = ""; $admin_users_email = ""; $payment_method = ""; $ord_num = ""; $ord_ammount = "0"; $sms_text = ""; $email_subject = ""; 
            $email_text = ""; $mailing_name = ""; $user_check_email = ""; $mailing_name = "";
            
            //$sitesettings = DB::table('sitesettings')->get();
            // Get site settings for specific attributes
            $sitesettings = Sitesetting::where('name', '=', 'email')->orWhere('name', '=', 'payment_mode')->get();
            
            $all_sitesetting = array();
            foreach($sitesettings as $each_sitesetting)
            {
                $all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value; 
            }

            // Admin Support Email
            if(isset($all_sitesetting['email']))
                $admin_users_email = $all_sitesetting['email']; 
            
            if(isset($all_sitesetting['payment_mode']))
                $payment_method = $all_sitesetting['payment_mode']; 
            
            // Get order information if available
            if(isset($order_list[0]->order_number))
                $ord_num = $order_list[0]->order_number;            
            
            if(isset($order_list[0]->order_total))
                $ord_ammount = $order_list[0]->order_total;
            

            // Get sitemessage, required for SMS and Email
            $sitemessage = Sitemessage::where('slug', '=', $message_id)->first();


            if(isset($order_list[0]->shiping_address_serialize))
            {
                $serialize_add = unserialize($order_list[0]->shiping_address_serialize);
                
                if(isset($serialize_add['phone']))
                    $mobile = $serialize_add['phone'];
                
                if(isset($serialize_add['email']))              
                    $user_check_email = $serialize_add['email'];
                
                if(isset($serialize_add['first_name']) && isset($serialize_add['last_name']))   
                    $mailing_name = $serialize_add['first_name'].' '.$serialize_add['last_name'];
                
                //if(isset($serialize_add['email']))
                //    $user_email = $serialize_add['email'];; 
                
                if(isset($serialize_add['phone']))
                    $mobile =  $serialize_add['phone'];

            }


            // Overwrite the information in case the found from brandmember table

            if(isset($order_list[0]->user_id) && $order_list[0]->user_id != "")
            {
                $user_details = DB::table('brandmembers')->where('id', $order_list[0]->user_id)->first();
            
            
                $name = $user_details->fname.' '.$user_details->lname;
                $username = $user_details->username;
                if($name!='')
                    $mailing_name = $name;
                else
                    $mailing_name = $username;

                $user_check_email = $user_details->email; 

                $mobile =  $user_details->phone_no; 

            }




            /*****************END Message For Email and SMS *********/



           
            if(isset($order_list[0]->preffered_communication) && $order_list[0]->preffered_communication == 1) 
            {
                
                /* TWILIO SMS SENDING AFTER SUCCESSFUL ORDER START */
                if($mobile !='')
                {
                    if(isset($sitemessage->sms_text))
                    {
                        $sms_text = $sitemessage->sms_text;
            
                        $sms_text = str_replace("[order_id]", $ord_num, $sms_text);
                        $order_message = str_replace("[order_amount]", $ord_ammount, $sms_text);
                        $order_message = html_entity_decode($order_message);

                        Twilio::message('+1'.$mobile, $order_message);
                    
                    }

                    /*$order_message = "Thanks for your order at The Miramix"."\r\n";
                    $order_message .="Order Number :".$order_list[0]->order_number." Order Amount: $".$order_list[0]->order_total;
                    Twilio::message('+1'.$mobile, $order_message);*/
                } 

                /* TWILIO SMS SENDING AFTER SUCCESSFUL ORDER START */

            } else {
                
                // Get the email details from Sitemessage model
                if(isset($sitemessage->subject))
                    $email_subject = trim($sitemessage->subject);
                
                if(isset($sitemessage->email_text))
                    $email_text = html_entity_decode($sitemessage->email_text);

                if(strpos($email_text,"[order_id]")==true)
                {
                    $email_text = str_replace("[order_id]", $ord_num, $email_text);
                }

                if(strpos($email_text,"[fullname]")==true)
                {
                    $email_text = str_replace("[fullname]", $mailing_name, $email_text);
                }

                if(strpos($email_text,"[order_amount]")==true)
                {
                    $email_text = str_replace("[order_amount]", $ord_ammount, $email_text);   
                }

                
                
                $sent = Mail::send($template_name, array('admin_users_email' => $admin_users_email, 'receiver_name' => $mailing_name, 'email' => $user_check_email, 'order_list' => $order_list, 'email_text' => $email_text), 
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
                
                

            }

            // Do not send email or SMS to Admin in case of Non-Order
            if($template_name != "frontend.checkout.order_details_mail")
            {

                /* Mail For Admin */
                $admin_user = DB::table('users')->first();

                $admin_email = $admin_user->email;

                if(($admin_user->name)!='')
                    $admin_name = $admin_user->name;
                else
                    $admin_name = 'Admin';


                
                $sent_admin = Mail::send('frontend.checkout.admin_order_details_mail', array('admin_users_email'=>$admin_users_email,'receiver_name'=>$admin_name,'admin_email'=>$admin_email,'order_list'=>$order_list), 
                function($message) use ($admin_users_email, $admin_email,$admin_name)
                {
                    $message->from($admin_users_email);  //support mail
                    $message->to($admin_email, $admin_name)->subject('Miramix Order Details For Admin');
                });
            }

        }


    }


}
