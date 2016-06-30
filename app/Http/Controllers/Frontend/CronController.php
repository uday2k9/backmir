<?php
namespace App\Http\Controllers\Frontend;

use App\Model\Brandmember;/* Model name*/
use App\Model\MemberProfile;
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
use Authorizenet;
use App\Helper\helpers;
use App\libraries\auth\AuthorizeNetCIM;
use App\libraries\auth\shared\AuthorizeNetTransaction;
use App\libraries\auth\shared\AuthorizeNetLineItem;



class CronController extends BaseController {
    
    public function __construct() 
    {
	   parent::__construct();
	   
	 
    
    }
    
     public function index()
    {
         $all_brand_member = DB::table('brandmembers')->where('role', 1)->where('status', 1)->where('admin_status', 1)->get();
        DB::connection()->enableQueryLog();

	foreach($all_brand_member as $brand){
	    $product = DB::table('products')
                 ->select(DB::raw('products.id,products.brandmember_id,products.product_name,products.product_slug,products.image1, MIN(`actual_price`) as `min_price`,MAX(`actual_price`) as `max_price`'))
                 ->leftJoin('product_formfactors', 'products.id', '=', 'product_formfactors.product_id')
                 ->where('products.brandmember_id', '=', $brand->id)
                 ->where('products.active', 1)
                 ->where('products.is_deleted', 0)
                 ->where('product_formfactors.servings', '!=',0)
                 ->where('products.discountinue', 0)
		
                 ->groupBy('product_formfactors.product_id');
	    $profile=MemberProfile::find($brand->id);
	  if(isset($profile) && $profile->count){
	    $count=(int)$profile->count;
	  }else{
	    $count=0;
	  }
	    $setting = DB::table('sitesettings')->where('name', 'brand_fee')->first();
	    $setting2 = DB::table('sitesettings')->where('name', 'brand_perproduct_fee')->first();
	    
	    if($count==1){
		$fee=$setting->value;
	    }elseif($count>1){
		$fee=$setting->value;
		$perproduct_fee=$setting2->value;
		
		$fee=$fee+($perproduct_fee*$count);
	    }else{
		$fee=0;
	    }
	   //echo $brand->id;
	   //echo '<br />';
	   $br=array('member_fee'=> $fee);
	   //print_r($br);
	   
	   
	    DB::table('brandmembers')
            ->where('id', $brand->id)
            ->update(array('member_fee' => $fee));
	}
	
        
         foreach($all_brand_member as $brand){
            //echo $brand->fname;
	   
	
            $subscription = DB::table('subscription_history')->where('payment_status', 'pending')->where('member_id', $brand->id)->first();
           if(count($subscription)<=0){
	    $paidsub = DB::table('subscription_history')->where('payment_status', 'paid')->where('member_id', $brand->id)->orderBy('end_date','DESC')->first();
	    
	    if(count($paidsub)>0){
	    $start_date=$paidsub->end_date;
	    $end_date=date("Y-m-d",strtotime($start_date .' + 30 days'));	
	    }else{
	    $start_date=$brand->created_at;
            $end_date=date("Y-m-d",strtotime($start_date .' + 30 days'));
	    
	    }
            $setting = DB::table('sitesettings')->where('name', 'brand_fee')->first();
            
            $subdata=array("member_id"=>$brand->id,"start_date"=>$start_date,"end_date"=>$end_date,"subscription_fee"=>$brand->member_fee);
            Subscription::create($subdata);
           }else{
	    
	    $paidsubscription = DB::table('subscription_history')->where('member_id', $brand->id)->orderBy('end_date','DESC')->first();
	    
	    if(is_object($paidsubscription) && $paidsubscription->end_date<=date("Y-m-d") && $paidsubscription->payment_status=='paid'){
	    
            echo 'subid-'.$paidsubscription->subscription_id.'- paid in prev month <br />';
	    
	    }else{
		
	    $today=Date('Y-m-d');
            $enddate=date("Y-m-d",strtotime($subscription->end_date." + 1 day"));
             if($enddate<=$today){
                //charge here
		 if(empty($brand->auth_profile_id))
		continue;
		
		echo "will be charged ".$subscription->member_id .'<br />';
		
		// Create Auth & Capture Transaction
		$request = new AuthorizeNetCIM;
		
		$transaction = new AuthorizeNetTransaction;
		$transaction->amount =$brand->member_fee;
		$transaction->customerProfileId = $brand->auth_profile_id;
		$transaction->customerPaymentProfileId = $brand->auth_payment_profile_id;
		$transaction->customerShippingAddressId = $brand->auth_address_id;
	    
		$lineItem              = new AuthorizeNetLineItem;
		$lineItem->itemId      = $subscription->subscription_id;
		$lineItem->name        = $brand->fname;
		$lineItem->description = $brand->fname. " charged for subscription of " .$subscription->start_date;
		$lineItem->quantity    = "1";
		$lineItem->unitPrice   = $brand->member_fee;
		$lineItem->taxable     = "false";
		
		$transaction->lineItems[] = $lineItem;
		
		$response = $request->createCustomerProfileTransaction("AuthCapture", $transaction);
		$updateWithCode = DB::table('brandmembers')->where('id', '=', $subscription->member_id)->update(array('subscription_status' => 'expired'));
		if($response->isOk()){
		    $transactionResponse = $response->getTransactionResponse();
		   
		    $transactionId = $transactionResponse->transaction_id;
    
		$subdata=array("transaction_id"=>$transactionResponse->transaction_id,"payment_status"=>'paid');
		
		$sub = DB::table('subscription_history')
                                    ->where('subscription_id', $subscription->subscription_id)
                                    ->update($subdata);
		$updateWithCode = DB::table('brandmembers')->where('id', '=', $subscription->member_id)->update(array('subscription_status' => 'active'));
		}else{
		   print_r($response); 
		    
		}
                
             }
	     
	     
	      
	     
	     
	    }
            
           }
           
         }
       $this->onlineusers();  
    }
    
    public function sendpasswordmail(){
	//return false;
	
	$members=DB::table('brandmembers')->whereRaw('CHAR_LENGTH(password)<1')->get();
	
	foreach($members as $member){
	   $brand=array();
	    $fname=explode(" ",$member->fname);
	   if(isset($fname[0]) && isset($fname[1])){
	   $brand=array("fname"=>trim($fname[0]),"lname"=>trim($fname[1]));
	   }
	   //echo $member->id;exit;
	   
	    $brandresult=Brandmember::find($member->id );
	    $orgpass=uniqid();
	    $password= Hash::make($orgpass);
	    $brand["password"]=$password;
	   $brand["status"]='1';
	   $brand["admin_status"]='1';
            $brandresult->update($brand);
	    
	    $sitesettings = DB::table('sitesettings')->where("name","email")->first();
            $admin_users_email=$sitesettings->value;
	    
	    $user_name =$member->fname.' '.$member->lname;
	    $user_email = $member->email;
	  
	  if($member->role==0){
	      $activateLink='http://www.miramix.com/memberLogin/';
	      $changepass_link='http://www.miramix.com/member-changepass/';
	    }else{
		$activateLink='http://www.miramix.com/brandlogin/';
		$changepass_link='http://www.miramix.com/change-password/';
	    }
	    $pass = $orgpass;
	    
	    
	    $userid=$member->email;
	    $sent = Mail::send('frontend.register.newPassword', array('name'=>$user_name,'email'=>$user_email,'activate_link'=>$activateLink,'userid'=>$userid,'admin_users_email'=>$admin_users_email,'password'=>$pass,'changepass_link'=>$changepass_link), 
	    function($message) use ($admin_users_email, $user_email,$user_name)
	    {
		    $message->from($admin_users_email);
		    $message->to($user_email, $user_name)->subject('New password generated for site migration');
	    });

	    if( ! $sent) 
	    {
		    echo 'Unable to send email'.$member->id.' <br />';
	    }else{
		
		echo 'mail sent to ->'.$member->id .$member->email."<br />";
	    }
	   
	    
	    
	}
	echo count($members);
	
	
    }
    
    
public function onlineusers(){
    $session=Session::getId();
    $time=time();
    $time_check=$time-600; //SET TIME 10 Minute
    
    
    
    $count=DB::table('user_online')->where("session",$session)->count();
    if($count=="0"){
	DB::table('user_online')->insert(['session' => $session, 'time' => $time,'ip' => Request::ip()]);
    }else{
	DB::table('user_online')
                                    ->where('session', $session)
                                    ->update(['time' => $time,'ip' => Request::ip()]);
    }
    
    $count=DB::table('user_online')->count();
    echo "Online Users : ".$count;
    DB::table('user_online')->where("time",'<',$time_check)->delete(); 

 
    
}

public function checksubscriptionstart(){
	$today=date("Y-m-d");
	
	$subscriptions=Subscription::where('start_date', '=', $today)
				   ->get();	
	foreach($subscriptions as $subscription)
	{		
		$brandmembers=Brandmember::where('id', $subscription->member_id)
								   ->where('role',1)								   
								   ->count();
		if($brandmembers>0)
		{
			$brandmemberArr=Brandmember::findOrFail($subscription->member_id);			
			$brandmemberArr->subscription_status = 'active';
			$brandmemberArr->save();
		}


	}
	die();
}


public function checksubscription(){
	$today=date("Y-m-d");
	
	$subscriptions=Subscription::where('end_date', '=', $today)
				   ->get();	
	foreach($subscriptions as $subscription)
	{		
		$brandmembers=Brandmember::where('id', $subscription->member_id)
								   ->where('role',1)
								   ->where('subscription_status','active')
								   ->count();
		if($brandmembers>0)
		{
			$brandmemberArr=Brandmember::findOrFail($subscription->member_id);			
			$brandmemberArr->subscription_status = 'expired';
			$brandmemberArr->save();
		}


	}
	die();
}
    
}
?>