<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\Brandmember; /* Model name*/
use App\Http\Requests;
use App\Http\Controllers\Controller;
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
use App\Model\Address;
use Mail; 

class BrandController extends Controller {

    public function __construct() 
    {
        view()->share('brand_class','active');
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
        $brands = DB::table('brandmembers')->where('role',1)->orderBy('id','DESC');
        //echo '<pre>';print_r($brands); exit;
        if($searchstring!=''){
	   $brands->whereRaw("fname like '%".$searchstring."%' or lname like '%".$searchstring."%' or email like '%".$searchstring."%' or username like '%".$searchstring."%'"); 
	}
	$brands=$brands->paginate($limit);
        
        $brands->setPath('brand');
        return view('admin.brands.index',compact('brands','searchstring'),array('title'=>'Brand Management','module_head'=>'Brands'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vitamins.create',array('title'=>'Vitamin Management','module_head'=>'Add Vitamins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vitamin=Request::all();
        Vitamin::create($vitamin);
        Session::flash('success', 'Vitamin added successfully'); 
        return redirect('admin/vitamin');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $vitamin=Vitamin::find($id);
       return view('admin.vitamins.show',compact('vitamin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand=Brandmember::find($id);
        $brand->password='';
         //$brand->slug='';
        $baddress=Address::find($brand->address);
       
       $country = DB::table('countries')->orderBy('name','ASC')->get();
        
        $alldata = array();
        foreach($country as $key=>$value)
        {
            $alldata[$value->country_id] = $value->name;
        }
        
         $states = DB::table('zones')->where('country_id',  $baddress->country_id)->orderBy('name','ASC')->get();
        
        $allstates = array();
        foreach($states as $key=>$value)
        {
            $allstates[$value->zone_id] = $value->name;
        }
        return view('admin.brands.edit',compact('brand','baddress','alldata','allstates'),array('title'=>'Edit Brand','module_head'=>'Edit Brand'));
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
       $brandUpdate=Request::all();
       $brand=Brandmember::find($id);
       
       if($brandUpdate['password']==''){
	unset($brandUpdate['password']);
       }else{
	$brandUpdate['password']=Hash::make(Request::input('password'));
	
       }
     /*  
       if($brandUpdate['slug']==''){
	unset($brandUpdate['slug']);
       }else{
	$brandUpdate['slug']=$obj->edit_slug(Request::input('slug'),'brandmembers','slug',$id);
	
       }*/
        $brandUpdate['slug']=$obj->edit_slug(Request::input('slug'),'brandmembers','slug',$id);
       
        $address['first_name'] = Request::input('first_name');
    	$address['last_name']  = Request::input('last_name');
    	$address['address']  = Request::input('address1');
    	$address['address2']  = Request::input('address2');
    	$address['country_id'] = Request::input('country_id');
    	$address['zone_id'] =  Request::input('zone_id'); // State id
    	$address['city'] =  Request::input('city');
    	$address['postcode'] =  Request::input('postcode');
                        
       Address::where('id', '=', Request::input('address'))->update($address);
        unset($brandUpdate['first_name']);
	unset($brandUpdate['last_name']);
        unset($brandUpdate['address1']);
        unset($brandUpdate['address2']);
        unset($brandUpdate['country_id']);
        unset($brandUpdate['zone_id']);
        unset($brandUpdate['city']);
        unset($brandUpdate['postcode']);
       
       if(isset($_FILES['pro_image']['name']) && $_FILES['pro_image']['name']!="")
			{
				$destinationPath = 'uploads/brandmember/'; // upload path
                                $thumb_path = 'uploads/brandmember/thumb/';
                                $medium = 'uploads/brandmember/thumb/';
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
	unset($brandUpdate['pro_image']);
       }else{
	$brandUpdate['pro_image']=$fileName;
	
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
	 if($government_issue ==''){
            unset($brandUpdate['government_issue']);
           }else{
            $brandUpdate['government_issue']=$government_issue;
            
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
			
           if($business_doc ==''){
            unset($brandUpdate['business_doc']);
           }else{
            $brandUpdate['business_doc']=$business_doc;
            
           }	             
       $brand->update($brandUpdate);
       
       
       
       
        
       Session::flash('success', 'Brand updated successfully'); 
       return redirect('admin/brand');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response	7278876384
     */
    public function destroy($id)
    {
        //
        Brandmember::find($id)->delete();

        Session::flash('success', 'Brand deleted successfully'); 
        return redirect('admin/brand');
    }
    
    public function status($id)
    {
        $brandmember=Brandmember::find($id);
        $brandmember->status = 1;
        $brandmember->update();

        Session::flash('success', 'Brand status updated successfully'); 
        return redirect('admin/brand');
        
    }

    public function admin_active_status($id)
    {
        //echo $id;exit;
        
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

        
        $sent = Mail::send('admin.brands.activate_brand', array('name'=>$user_name,'email'=>$user_email,'admin_users_email'=>$admin_users_email,'msg'=>$msg), 
        function($message) use ($admin_users_email, $user_email,$user_name,$msg)
        {
            $message->from($admin_users_email);
            $message->to($user_email, $user_name)->subject('Miramix Brand Account Now Activated');
        });
                        
        if(!$sent)
        {
            Session::flash('error', 'something went wrong!! Mail not sent.'); 
            return redirect('admin/brand');
        }
        else
        {
            Session::flash('success', 'Brand status updated successfully'); 
            return redirect('admin/brand');
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
        
        $sent = Mail::send('admin.brands.activate_brand', array('name'=>$user_name,'email'=>$user_email,'admin_users_email'=>$admin_users_email,'msg'=>$msg), 
        function($message) use ($admin_users_email, $user_email,$user_name,$msg)
        {
            $message->from($admin_users_email);
            $message->to($user_email, $user_name)->subject('Miramix Brand Account Now Deactivated');
        });
                        
        if(!$sent)
        {
            Session::flash('error', 'something went wrong!! Mail not sent.'); 
            return redirect('admin/brand');
        }
        else
        {
            Session::flash('success', 'Brand status updated successfully'); 
            return redirect('admin/brand');
        }
        
    }
}
