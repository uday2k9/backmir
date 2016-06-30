<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/
use Auth;

use App\Model\Package; /* Model name*/
use App\Model\PackageType; /* Model name*/
use App\Model\Subscription;
use App\Model\Product; /* Model name*/
//use App\Http\Requests;
//use Illuminate\Support\Facades\Input;
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
use App\Model\FormFactor;
use Cookie;
use Mail;
use App\Helper\helpers;
use Authorizenet;
use App\libraries\auth\AuthorizeNetCIM;
use App\libraries\auth\shared\AuthorizeNetCustomer;
use App\libraries\auth\shared\AuthorizeNetPaymentProfile;
use App\libraries\auth\shared\AuthorizeNetAddress;


class PackageController extends BaseController {

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
        view()->share('obj',$obj);
    }
   

    public function getIndex()
    {
        return redirect('brand-dashboard');

        if(Session::has('brand_userid')){
            $user_id = Session::get('brand_userid');
        }       
        $limit = 10;
       
        $packages=Package::whereRaw('FIND_IN_SET('.$user_id.',`brandmember`)')
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->paginate($limit);       
        
    

        return view('frontend.package.list',compact('packages'),array('title' => 'Package List'));

    }    

    public function getCreate()
    {
       if(Session::has('brand_userid')){
          $brand_user_id = Session::get('brand_userid');
       }

       $formfactors = FormFactor::lists('name', 'id');
       $package_types = PackageType::where('status',1)
                      ->orderBy('name','ASC')
                      ->lists('name', 'id');    

       return view('frontend.package.create',array(
            'title'=>'Create Package',
            'formfactors'     =>$formfactors,
            'brand_user_id'     =>$brand_user_id,
            'package_types'     =>$package_types
          ));

       //return view('frontend.package.create',array('title' => 'Create Package'));

    } 

    public function postStore(Request $request)
    {
      if(count(Request::input('formfactor')) >0)
      {
        $formfactor = implode(",",Request::input('formfactor'));
      }
      else
      {
         $formfactor = '';
      } 
          

      // create the data for our user
      $package = new Package();
      $package->name                  = Request::input('name');      
      $package->package_type          = Request::input('package_type');
      $package->formfactor            = $formfactor;
      $package->brandmember           = Request::input('brandmember');
      $package->maximum_width         = Request::input('maximum_width');
      $package->maximum_height        = Request::input('maximum_height'); 
      $package->maximum_depth         = Request::input('maximum_depth');      
      $package->minimum_unit          = Request::input('minimum_unit');
      $package->maximum_unit          = Request::input('maximum_unit');
      $package->minimum_bound_label   = Request::input('minimum_bound_label');
      $package->maximum_bound_label   = Request::input('maximum_bound_label');
      $package->status                = '1';
      $package->created_at            = \Carbon\Carbon::now();
      $package->updated_at            = \Carbon\Carbon::now();
     
     
      if($package->save())
      {
        Session::flash('success', 'Package added successfully'); 
     
        return redirect('package');
      }     

    }    

   /* Delete A Package */
   public function getDelete($id)
   {  
   // dd($id);    
     Package::findOrFail($id)->delete();
     Session::flash('success', 'Package deleted successfully');      
     return redirect('package');
   }

   public function getEdit($id)
   {
    //dd($id);
       if(Session::has('brand_userid')){
          $brand_user_id = Session::get('brand_userid');
       }

       $formfactors = FormFactor::lists('name', 'id');

       $packages=Package::where('id',$id)->get();
       $package_types = PackageType::where('status',1)
                      ->orderBy('name','ASC')
                      ->lists('name', 'id'); 
        //  dd($packages->toArray());                

       return view('frontend.package.edit',array(
            'title'=>'Update Package',
            'formfactors'     =>$formfactors,
            'brand_user_id'     =>$brand_user_id,
            'packages'     =>$packages,
            'package_types'     =>$package_types
          ));

       //return view('frontend.package.create',array('title' => 'Create Package'));

    } 

    public function postUpdate(Request $request)
    { 
       

      if(count(Request::input('formfactor')) >0)
      {
        $formfactor = implode(",",Request::input('formfactor'));
      }
      else
      {
         $formfactor = '';
      }

     
      //dd($formfactor);

      $package = Package::find(Request::input('package_id'));

      $package->name                  = Request::input('name'); 
     
      $package->formfactor            = $formfactor;
      $package->package_type            = Request::input('package_type');
      $package->maximum_width         = Request::input('maximum_width');
      $package->maximum_height        = Request::input('maximum_height'); 
      $package->maximum_depth         = Request::input('maximum_depth');      
      $package->minimum_unit          = Request::input('minimum_unit');
      $package->maximum_unit          = Request::input('maximum_unit');
      $package->minimum_bound_label   = Request::input('minimum_bound_label');
      $package->maximum_bound_label   = Request::input('maximum_bound_label');
      $package->status                = '1';

      //dd($package->toArray());
      if($package->save())
      {
        Session::flash('success', 'Package updated successfully'); 
     
        return redirect('package');
      }     
   }
}