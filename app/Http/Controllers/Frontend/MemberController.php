<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/

use App\Model\Brandmember; /* Model name*/
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
use App\Helper\helpers;
use Cart;

class MemberController extends BaseController {

    public function __construct() 
    {
        parent::__construct();
        $obj = new helpers();
        view()->share('obj',$obj);
        
    }
    
  public function index()
    {
        $obj = new helpers();
        if(!$obj->checkMemberLogin())
        {
            return redirect('memberLogin');
        }
        
        $body_class = 'home';
        $phone_class = 'telP_top hover';
        $member_details = Brandmember::find(Session::get('member_userid'));
        return view('frontend.member.member_dashboard',compact('member_details','body_class','phone_class'),array('title'=>'MIRAMIX | Member Dashboard'));
    }
    
    
 public function memberAccount()
    {
        $obj = new helpers();
        if(!$obj->checkMemberLogin())
        {
            return redirect('memberLogin');
        }
        
        
        if(Request::isMethod('post'))
        { 
          if($_FILES['image']['name']!="")
    			{
        		$destinationPath = 'uploads/member/'; // upload path
            $thumb_path = 'uploads/member/thumb/';
            $medium = 'uploads/member/thumb/';
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
            
            $member=array('fname'=> Request::input('fname'),
			                   'lname'=> Request::input('lname'),
                         'phone_no'=> Request::input('phone_no'),
                         'preffered_communication'=>  Request::input('preffered_communication')
                          );

            if(!empty($fileName)){
                $member['pro_image']=$fileName;
            }
           
            $memberresult=Brandmember::find(Session::get('member_userid') );
            $memberresult->update($member);
            Session::flash('success', 'Your profile is successfully updated.');
            return redirect('member-account');
            
        }
      $member = Brandmember::find(Session::get('member_userid'));

        $body_class = '';
        return view('frontend.member.member_account',compact('body_class','member'),array('title'=>'Member Information'));
    }
    
     public function member_change_pass()
    { 
         $obj = new helpers();
        if(!$obj->checkMemberLogin()){
            return redirect('memberLogin');
        }
        // if(!Session::has('member_userid')){
        //     return redirect('memberLogin');
        // }
	
	$member_details =$user=Brandmember::find(Session::get('member_userid'));

        if(Request::isMethod('post'))
        {

            if(!Session::has('member_userid')){
                return redirect('memberLogin');
            }

           // print_r($_POST);exit;
          $old_password = Request::input('old_password');
          

          $password = Request::input('password');
          $conf_pass = Request::input('conf_pass');

          // Get Admin's password

          
          

          if(Hash::check($old_password, $user['password']) || empty($user['password']))
          {
            if($password!=$conf_pass){
              Session::flash('error', 'Password and confirm password is not matched.'); 
              return redirect('member-changepass');

            }
            else{
              DB::table('brandmembers')->where('id', Session::get('member_userid'))->update(array('password' => Hash::make($password)));
              
              Session::flash('success', 'Password successfully changed.'); 
              return redirect('member-changepass');
            }
          }
          else{
            Session::flash('error', 'Old Password does not match.'); 
            return redirect('member-changepass');
          }
        }
	
        return view('frontend.member.memberchangepassword',compact('member_details'),array('title' => 'Member Change Password'));
    }

 /* --------------------  Multiple Shipping Address For Members --------------------*/

 public function memberShippingAddress()
 {
    $obj = new helpers();
    if(!$obj->checkMemberLogin()){
      return redirect('memberLogin');
    }
    
    $address = DB::table('addresses')->where("mem_brand_id",Session::get('member_userid'))->orderBy('id','DESC')->get();
    $member_details = Brandmember::find(Session::get('member_userid'));
    //echo "<pre>";print_r($address);exit;
    
    return view('frontend.member.member_shipping_address',compact('address','member_details'),array('title' => 'My Addresses'));
 }
     
     
     
public function createMemberShippingAddress()
{
        $obj = new helpers();
       if(!$obj->checkMemberLogin()){
            return redirect('memberLogin');
        }
        
         $country = DB::table('countries')->orderBy('name','ASC')->get();
        
        $alldata = array();
        foreach($country as $key=>$value)
        {
            $alldata[$value->country_id] = $value->name;
        }
        
        if(Request::isMethod('post'))
        {
            $address = New Address;
            
            $address->mem_brand_id = Session::get('member_userid');
    	    $address->first_name = Request::input('first_name');
    	    $address->last_name = Request::input('last_name');
            $address->address = Request::input('address');
	         $address->address2 = Request::input('address2');
            $address->country_id = Request::input('country');
            $address->zone_id =  Request::input('zone_id'); // State id
            $address->city =  Request::input('city');
	         $address->postcode =  Request::input('postcode');
            $address->phone =  Request::input('phone');
            $address->email =  Request::input('email');
            
           if($address->save()) 
			     {
				
                if(Request::input('default_address')=='1'){
                $addressId = $address->id;
                $dataUpdateAddress = DB::table('brandmembers')
                  ->where('id',Session::get('member_userid'))
                  ->update(['address' => $addressId]);

                }
                                
              Session::flash('success', 'Shipping Address successfully added.'); 
              return redirect('member-shipping-address');
                                        
            }
          }

        
          $total_add = DB::table('addresses')->where('mem_brand_id',Session::get('member_userid'))->count();


        
        return view('frontend.member.create_member_shipping',compact('alldata','total_add'),array('title' => 'Create Shipping Address'));
     } 



public function editMemberShippingAddress()
     {
        $obj = new helpers();
       if(!$obj->checkMemberLogin()){
            return redirect('memberLogin');
        }
        
        $id=Request::input('id');
        if(empty($id)){
            
            return redirect('member-shipping-address');
        }
        
        $country = DB::table('countries')->orderBy('name','ASC')->get();
        
        $alldata = array();
        foreach($country as $key=>$value)
        {
            $alldata[$value->country_id] = $value->name;
        }
        
        
         if(Request::isMethod('post'))
        {
            $address = array();
            
            $address['mem_brand_id'] = Session::get('member_userid');
	    $address['first_name'] = Request::input('first_name');
	    $address['last_name'] = Request::input('last_name');
            $address['address'] = Request::input('address');
	    $address['address2'] = Request::input('address2');
            $address['country_id'] = Request::input('country');
            $address['zone_id'] =  Request::input('zone_id'); // State id
            $address['city'] =  Request::input('city');
	    $address['postcode'] =  Request::input('postcode');
            $address['phone'] =  Request::input('phone');
            $address['email'] =  Request::input('email');
            
            DB::table('addresses')
					->where('id',Request::input('id'))
					->update($address);
           
				
         if(Request::input('default_address')=='1'){
                                $addressId = Request::input('id');
				$dataUpdateAddress = DB::table('brandmembers')
					->where('id',Session::get('member_userid'))
					->update(['address' => $addressId]);
                
                                
           
                                        
          }
            Session::flash('success', 'Shipping Address successfully updated.'); 
              return redirect('member-shipping-address');   
            
            
        }
        
        
        
        
        
        $address = DB::table('addresses')->find($id);
        
        
        
        $states = DB::table('zones')->where('country_id', $address->country_id)->orderBy('name','ASC')->get();
        
        $allstates = array();
        foreach($states as $key=>$value)
        {
            $allstates[$value->zone_id] = $value->name;
        }
        $member_details = Brandmember::find(Session::get('member_userid'));

        $total_add = DB::table('addresses')->where('mem_brand_id',Session::get('member_userid'))->where('id','!=',$id)->count();

        
        return view('frontend.member.edit_member_shipping',compact('alldata','address','allstates','member_details','total_add'),array('title' => 'Edit Shipping Address'));
     }
     
     public function delAddress(){
        
        $obj = new helpers();
        if(!$obj->checkMemberLogin()){
            return redirect('memberLogin');
        }
        
        $id=Request::input('id');
        if(empty($id)){
            
            return redirect('member-shipping-address');
        }
        
        $address = Address::find($id);

        try {
            
            if($address->delete()){
        
        
                Session::flash('success', 'Shipping Address successfully deleted.'); 
                      return redirect('member-shipping-address');
                }else{
                    
                    Session::flash('error', 'Unable to delete record.'); 
                      return redirect('member-shipping-address');
                }
        
     
                } catch(PDOException $e){
                    
                    
                
               }
 
        
        
     }

}
?>