<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\Brandmember; /* Model name*/
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Order;            
use App\Model\OrderItems;

use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use Hash;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Illuminate\Pagination\Paginator;
use DB;
use App\Helper\helpers;
use Mail;

class MemberController extends Controller {

    public function __construct() 
    {
        view()->share('member_class','active');
	
    }

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function index()
   {
    $searchstring=Request::input('searchstring');
        $limit = 50;
	$members = DB::table('brandmembers')->orderBy('id','DESC');
        //echo '<pre>';print_r($members); exit;
	if($searchstring!=''){
	   $members->whereRaw("role='0' and (fname like '%".$searchstring."%' or lname like '%".$searchstring."%' or email like '%".$searchstring."%' or username like '%".$searchstring."%' )"); 
	}
	$members=$members->paginate($limit);
	    $members->setPath('member');
	
	
        return view('admin.members.index',compact('members','searchstring'),array('title'=>'Member Management','module_head'=>'Members'));

    }

    public function edit($id)
    {

        if (Brandmember::where('id', '=', $id)->exists()) {
          
            $member=Brandmember::find($id);
            $member->password='';
            return view('admin.members.edit',compact('member'),array('title'=>'Edit Member','module_head'=>'Edit Member'));
        }
        else{
            Session::flash('error', 'Member is not found.'); 
            return redirect('admin/member');
        }

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
	$obj = new helpers();
	
       $memberUpdate=Request::all();
       
       if($memberUpdate['password']==''){
	unset($memberUpdate['password']);
       }else{
	$memberUpdate['password']=Hash::make(Request::input('password'));
	
       }
       
       if(isset($_FILES['pro_image']['name']) && $_FILES['pro_image']['name']!="")
			{
				$destinationPath = 'uploads/member/'; // upload path
				$thumb_path = 'uploads/member/thumb/';
				$medium = 'uploads/member/thumb/';
				$extension = Input::file('pro_image')->getClientOriginalExtension(); // getting image extension
				$fileName = rand(111111111,999999999).'.'.$extension; // renameing image
				Input::file('pro_image')->move($destinationPath, $fileName); // uploading file to given path
				
                $obj->createThumbnail($fileName,661,440,$destinationPath,$thumb_path);
                $obj->createThumbnail($fileName,116,116,$destinationPath,$medium);
			}
			else
			{
				$fileName = '';
			}
                        
       
       if($fileName ==''){
	unset($memberUpdate['pro_image']);
       }else{
	$memberUpdate['pro_image']=$fileName;
	
       }
       
       
       $member=Brandmember::find($id);
       $member->update($memberUpdate);

       Session::flash('success', 'Member updated successfully'); 
       return redirect('admin/member');
    }

    public function status($id)
    {
        $brandmember=Brandmember::find($id);
        $brandmember->status = 1;
        $brandmember->update();
        
        Session::flash('success', 'Member status updated successfully'); 
        return redirect('admin/member');
    }

    public function admin_active_status($id)
    {
        $brandmember=Brandmember::find($id);
        $brandmember->admin_status = 1;
        $brandmember->update();
        
        $sitesettings = DB::table('sitesettings')->where("name","email")->first();
        $admin_users_email=$sitesettings->value;
        
        if($brandmember->fname !='')
        $user_name = $brandmember->fname.' '.$brandmember->lname;
        else
        $user_name = $brandmember->username;

        $user_email = $brandmember->email;

        $msg ="Your account has been activated by admin.Please try to log in with your valid credentials.";

        $sent = Mail::send('admin.members.activate_member', array('name'=>$user_name,'email'=>$user_email,'admin_users_email'=>$admin_users_email,'msg'=>$msg), 
        function($message) use ($admin_users_email, $user_email,$user_name,$msg)
        {
            $message->from($admin_users_email);
            $message->to($user_email, $user_name)->subject('Account Activation Mail From Miramix Support');
        });
                        
        if(!$sent)
        {
            Session::flash('error', 'something went wrong!! Mail not sent.'); 
            return redirect('admin/member');
        }
        else
        {
            Session::flash('success', 'Member status updated successfully'); 
            return redirect('admin/member');
        }
    }

    public function admin_inactive_status($id)
    {
        $brandmember=Brandmember::find($id);
        $brandmember->admin_status = 0;
        $brandmember->update();

        $sitesettings = DB::table('sitesettings')->where("name","email")->first();
        $admin_users_email=$sitesettings->value;
        
        if($brandmember->fname !='')
        $user_name = $brandmember->fname.' '.$brandmember->lname;
        else
        $user_name = $brandmember->username;

        $user_email = $brandmember->email;

        $msg = "Your account has been de-activated by admin.Please contact with miramix support.";
        
        $sent = Mail::send('admin.members.activate_member', array('name'=>$user_name,'email'=>$user_email,'admin_users_email'=>$admin_users_email,'msg'=>$msg), 
        function($message) use ($admin_users_email, $user_email,$user_name,$msg)
        {
            $message->from($admin_users_email);
            $message->to($user_email, $user_name)->subject('Account Deactivation Mail From Miramix Support');
        });
                        
        if(!$sent)
        {
            Session::flash('error', 'something went wrong!! Mail not sent.'); 
            return redirect('admin/member');
        }
        else
        {
            Session::flash('success', 'Member status updated successfully'); 
            return redirect('admin/member');
        }
       
    }

    public function destroy($id)
    {
        

        Brandmember::find($id)->delete();

        Session::flash('success', 'Member deleted successfully'); 
        return redirect('admin/member');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function add_member(){
    if(Request::isMethod('post'))
        {
	    
	    $register=Request::all();
	    //print_r($register);
	    
	    $hashpassword = Hash::make($register['password']);
	    
	     if(isset($_FILES['pro_image']['name']) && $_FILES['pro_image']['name']!="")
			{
				$destinationPath = 'uploads/member/'; // upload path
				$thumb_path = 'uploads/member/thumb/';
				$medium = 'uploads/member/thumb/';
				$extension = Input::file('pro_image')->getClientOriginalExtension(); // getting image extension
				$fileName = rand(111111111,999999999).'.'.$extension; // renameing image
				Input::file('pro_image')->move($destinationPath, $fileName); // uploading file to given path
				
                $obj->createThumbnail($fileName,661,440,$destinationPath,$thumb_path);
                $obj->createThumbnail($fileName,116,116,$destinationPath,$medium);
			}
			else
			{
				$fileName = '';
			}
                        
			
			
    
	    $brandmember= Brandmember::create([
		'email'             => $register['email'],
		'username'          => $register['username'],
		'fname'             => $register['fname'],
		'lname'             => $register['lname'],
		'phone_no'          => $register['phone_no'],
		'pro_image'	    => $fileName,
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
    
	    $user_name = $register['username'];
	    $user_email = $register['email'];
	   
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
		return redirect('admin/add-member');
	    }
	    else
	    {
		Session::flash('success', 'Member created successfully.'); 
		return redirect('admin/member');
	    }
	}
     return view('admin.members.add',array('title'=>'Add Member','module_head'=>'Members'));
   }
   
   public function brand_orders($brandid){
    
    $limit = 10;
   /* $currentPage = Request::segment(4);
    Paginator::currentPageResolver(function() use ($currentPage) {
	    return $currentPage;
	});
     */   
        //$order_list = Order::with('getOrderMembers','AllOrderItems')->orderBy('id','DESC')->paginate($limit);
	
	//$order_list = Order::with('getOrderMembers','AllOrderItems')->orderBy('id','DESC');
	//$order_list = DB::table('order_items')->where('brand_id', $brandid)->groupby("order_id")->paginate($limit);
	$order_list = OrderItems::with('order')->where('brand_id', $brandid)->orderBy('order_id','DESC')->paginate($limit);
	/*
	$order_list = Order::with(array('getOrderMembers','AllOrderItems' => function($query)
	    {
	      $query->where('brand_id', '=', 1038);
	    }))   ->orderBy('id','DESC')->paginate($limit);
	*/
	//$order_list->whereRaw("brand_id1='".$brandid."'");
	//$order_list=$order_list->get();
      //$order_list->setPath('brand-orders');
	$orderstatus='';
	$filterdate='';
        
        return view('admin.order.brand_order_history',compact('order_list','orderstatus','filterdate'),array('title'=>'MIRAMIX | All Order','module_head'=>'Orders'));

   }

}