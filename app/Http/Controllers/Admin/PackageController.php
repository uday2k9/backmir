<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\Package; /* Model name*/
use App\Model\PackageType; /* Model name*/
use App\Model\FormFactor;
use App\Model\Brandmember;
use App\Http\Requests;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Input; /* For input */
use Validator;
use Session;
use Illuminate\Pagination\Paginator;
use DB;
use App\Helper\helpers;
use Illuminate\Http\Request;

class PackageController extends Controller {

    public function __construct() 
    {
      view()->share('package_class','active');
    }

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function getIndex($param = false)
   {
        $limit = 10;
        if($param){
          $packages = Package::where('name', 'LIKE', '%' . $param . '%')
                      ->orderBy('id','DESC')
                      ->paginate($limit);         
          $packages->setPath($param);
        }
        else{
          $packages = Package::orderBy('id','DESC')
                      ->paginate($limit);         
          $packages->setPath('package');
        }
        
        return view('admin.package.index',compact('packages','param'),array('title'=>'Package Management','module_head'=>'Packages'));
   }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
   public function getCreate()
   {
     
      $formfactors = FormFactor::lists('name', 'id');      
      $formfactors_val = FormFactor::lists('count_unit', 'id');     
     // dd($formfactors_val);
      $package_types = PackageType::where('status',1)
                      ->orderBy('name','ASC')
                      ->lists('name', 'id');    

      return view('admin.package.create',array(
            'title'=>'Package Management',
            'module_head'=>'Add Package',
            'formfactors'     =>$formfactors,
            'package_types'     =>$package_types,
            'formfactors_val'     =>$formfactors_val
          ));
   }

   /* Save a Package */
   public function postStore(Request $request)
   {   
     // dd($request->input('maximum_unit_c'));
      if(count($request->input('formfactor')) >0)
      {
        $formfactor = implode(",",$request->input('formfactor'));
      }
      else
      {
         $formfactor = '';
      }   

      
      // create the data for our user
      $package = new Package();
      $package->name                  = $request->input('name');      
      $package->formfactor            = $formfactor;
      $package->package_type          = $request->input('package_type');
      $package->maximum_width         = $request->input('maximum_width');
      $package->maximum_height        = $request->input('maximum_height'); 
      $package->maximum_depth         = $request->input('maximum_depth');      
      $package->maximum_unit_c        = $request->input('maximum_unit_c');
      $package->minimum_unit_c        = $request->input('minimum_unit_c');
      $package->maximum_unit_g        = $request->input('maximum_unit_g');
      $package->minimum_unit_g        = $request->input('minimum_unit_g');
      $package->minimum_bound_label   = $request->input('minimum_bound_label');
      $package->maximum_bound_label   = $request->input('maximum_bound_label');
      $package->minimum_lower_bound   = $request->input('minimum_lower_bound');
      $package->maximum_lower_bound   = $request->input('maximum_lower_bound');
      $package->status                = '1';
      $package->created_at                = \Carbon\Carbon::now();
      $package->updated_at                = \Carbon\Carbon::now();
     
     
      if($package->save())
      {
        Session::flash('success', 'Package added successfully'); 
     
        return redirect('admin/package');
      }       
   }

   /* Delete A Package */
   public function getDelete($id)
   {      
     Package::findOrFail($id)->delete();
     Session::flash('success', 'Package deleted successfully');      
     return redirect('admin/package');
   }

   /* Show edit form */
   public function getEdit($id)
   {
      $package = Package::findOrFail($id);
      $formfactors = FormFactor::lists('name', 'id');
     /* $brandmembers = Brandmember::where('role',1)
                      ->orderBy('business_name','ASC')
                      ->lists('business_name', 'id');*/
      $package_types = PackageType::where('status',1)
                      ->orderBy('name','ASC')
                      ->lists('name', 'id');
      //dd($formfactors);

      $in_val=explode(",",$package['formfactor']);
   // dd($in_val); 
      //$formfactors=FormFactor::where('id',$form_factor_select)->get();
      //$formfactors=FormFactor::find([$form_factor_select]);
      $formfactors_val = FormFactor::whereIn('id',$in_val)->distinct()->lists('count_unit'); 
     // $formfactors_val = FormFactor::find([$form_factor_select]); 
     // $formfactors_arr=$formfactors_val->toArray();
      //echo "<pre>";
      //print_r($formfactors_val->toArray());
      //echo "</pre>";
      ///echo $form_factor_select;     
     // dd($formfactors_val);
      //echo $formfactors->count_unit;
      $diff_unit=array();
      foreach($formfactors_val as $key=>$val)
      {
        $diff_unit[]=$val;
      }
      $diff_unit_str=implode(",",$diff_unit);
     // dd($diff_unit); 
     // return $diff_unit;

     
      return view('admin.package.edit',array(
                    'title'       =>'Package Management',
                    'module_head' =>'Update Package',
                    'package'     =>$package,
                    'formfactors'     =>$formfactors,
                    'package_type'     =>$package_types,
                    'diff_unit'     =>$diff_unit_str
                  ));
   }

   public function postUpdate(Request $request)
   {      
      
      if(count($request->input('formfactor')) >0) 
      {
        $formfactor = implode(",",$request->input('formfactor'));
      }
      else
      {
         $formfactor = '';
      }      

      $package = Package::find($request->input('package_id'));

      $package->name                  = $request->input('name');
      
      $package->formfactor            = $formfactor;
      $package->package_type           = $request->input('package_type');
      $package->maximum_width         = $request->input('maximum_width');
      $package->maximum_height        = $request->input('maximum_height'); 
      $package->maximum_depth         = $request->input('maximum_depth');
      $package->maximum_unit_c        = $request->input('maximum_unit_c');
      $package->minimum_unit_c        = $request->input('minimum_unit_c');
      $package->maximum_unit_g        = $request->input('maximum_unit_g');
      $package->minimum_unit_g        = $request->input('minimum_unit_g');
      $package->minimum_bound_label   = $request->input('minimum_bound_label');
      $package->maximum_bound_label   = $request->input('maximum_bound_label');
      $package->minimum_lower_bound   = $request->input('minimum_lower_bound');
      $package->maximum_lower_bound   = $request->input('maximum_lower_bound');
      $package->status                = '1';
      $package->updated_at                = \Carbon\Carbon::now();

      if($package->save())
      {
        Session::flash('success', 'Package updated successfully'); 
     
        return redirect('admin/package');
      }     
   }

   public function package_name($param = false)
    {
      
        $package = Package::where('name', 'LIKE', '%' . $_REQUEST['term'] . '%')->groupBy('name')->orderBy('name','ASC')->get();
        
        $arr = array();
        foreach ($package as $value) {
            
            $arr[] = $value->name;
        }
        echo json_encode($arr);
    }

   public function getType($param = false)
   {
    
      view()->share('package_type_class','active');
      view()->share('package_class','');

        $limit = 10;
        if($param){
          $packages = PackageType::where('name', 'LIKE', '%' . $param . '%')
                      ->orderBy('id','DESC')
                      ->paginate($limit);
         
          $packages->setPath($param);
        }
        else{
          $packages = PackageType::orderBy('id','DESC')
                      ->paginate($limit);
         
          $packages->setPath('package');
        }

       
        return view('admin.package.show_type',compact('packages','param'),array('title'=>'Package Management','module_head'=>'Package Type'));
   }


   /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
   public function getCreatetype()
   {
      view()->share('package_type_class','active');
      view()->share('package_class','');


      $formfactors = FormFactor::lists('name', 'id');
      $brandmembers = Brandmember::where('role',1)
                      ->orderBy('business_name','ASC')
                      ->lists('business_name', 'id');
     // dd($brandmember)

      return view('admin.package.createtype',array(
            'title'=>'Package Type Management',
            'module_head'=>'Add Package Type',
            'formfactors'     =>$formfactors,
            'brandmembers'     =>$brandmembers
          ));
   }


   /* Save a Package */
   public function postStoretype(Request $request)
   {     
    
      if($_FILES['image']['name']!="")
      {    
          $destinationPath = 'uploads/package/type';   // upload path
          if (!is_dir($destinationPath) && !mkdir($destinationPath)){
              chmod($destinationPath, 0777);           
              return Redirect::back()->withErrors(['error', 'Unable to upload image.']);
          }

          
          $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
          $fileName = time().rand(111111111,999999999).'.'.$extension; // renameing image
          Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
      }
      else
      {
          $fileName = '';
      }      

      // create the data for our user
      $package = new PackageType();
      $package->name                  = $request->input('name');      
      $package->image                 = $fileName;      
      $package->status                = '1';
      $package->created_at                = \Carbon\Carbon::now();
      $package->updated_at                = \Carbon\Carbon::now();
     
     
      if($package->save())
      {
        Session::flash('success', 'Package type added successfully'); 
     
        return redirect('admin/package/type');
      }       
   }

   /* Show edit form */
   public function getEdittype($id)
   {
      view()->share('package_type_class','active');
      view()->share('package_class','');

      $package_type = PackageType::findOrFail($id);
     
     
      return view('admin.package.edittype',array(
                    'title'       =>'Package Type Management',
                    'module_head' =>'Update Package Type',
                    'package'     =>$package_type                   
                  ));
   }


   public function postUpdatetype(Request $request)
   { 
      if($_FILES['image']['name']!="")
      {         
          $destinationPath = 'uploads/package/type/';   // upload path
          if (!is_dir($destinationPath) && !mkdir($destinationPath)){
              chmod($destinationPath, 0777);           
              return Redirect::back()->withErrors(['error', 'Unable to upload image.']);
          }
          
          $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
          $fileName = time().rand(111111111,999999999).'.'.$extension; // renameing image
          Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
      }
      else
      {
          $fileName = '';
      }      

      
     

      $package_type = PackageType::find($request->input('package_id'));

      $package_type->name                  = $request->input('name');
      if($_FILES['image']['name']!="")
      {       
        $package_type->image                 = $fileName;
      }      
      $package_type->status                = '1';
      $package_type->updated_at                = \Carbon\Carbon::now();

      if($package_type->save())
      {
        Session::flash('success', 'Package type updated successfully'); 
     
        return redirect('admin/package/type');
      }     
   }


   /* Delete A Package */
   public function getDeletetype($id)
   {      
     $package_type = PackageType::findOrFail($id);
     $file_path=url()."/uploads/package/type/".$package_type->image;
     //dd($file_path);
     if(file_exists($file_path))
     {
      //dd("A");
        unlink($file_path);
     }
     $package_type ->delete();
     Session::flash('success', 'Package type deleted successfully');      
     return redirect('admin/package/type');
   }


   public function package_type_name($param = false)
    {
      //dd("a");

        $package = PackageType::where('name', 'LIKE', '%' . $_REQUEST['term'] . '%')->groupBy('name')->orderBy('name','ASC')->get();
        //$package = DB::table('package')->where('name', 'LIKE', '%' . $_REQUEST['term'] . '%')->groupBy('name')->orderBy('name','ASC')->get();
        $arr = array();
        foreach ($package as $value) {
            
            $arr[] = $value->name;
        }
        echo json_encode($arr);
    }

    /* Save a Package */
   public function postAppendfield($form_factor_select)
   {  
      
      $in_val=explode(",",$form_factor_select);
    //dd($in_val); 
      //$formfactors=FormFactor::where('id',$form_factor_select)->get();
      //$formfactors=FormFactor::find([$form_factor_select]);
      $formfactors_val = FormFactor::whereIn('id',$in_val)->distinct()->lists('count_unit'); 
     // $formfactors_val = FormFactor::find([$form_factor_select]); 
     // $formfactors_arr=$formfactors_val->toArray();
      //echo "<pre>";
      //print_r($formfactors_val->toArray());
      //echo "</pre>";
      ///echo $form_factor_select;     
     // dd($formfactors_val);
      //echo $formfactors->count_unit;
      foreach($formfactors_val as $key=>$val)
      {
        $diff_unit[]=$val;
      }
      return $diff_unit;
     // dd($diff_unit);
      //echo "VV";
      //dd($diff_unit);
      
     // for($i=0; $i<count($diff_unit); $i++)
     // {
        //$html1='';
        //$html2='';
        //$html='';
          //echo $diff_unit[$i];
        /*if($diff_unit[$i]==1)
        {
          $html1.='<input type="text" name="maximum_unit[]" placeholder="Maximum Unit" id="maximum_unit_1" class="span4">
                 <input type="text" name="minimum_unit[]" placeholder="Minimum Unit" id="minimum_unit_1" class="span4"><br/>';
        }
        if($diff_unit[$i]==2)
        {
          $html2.='<input type="text" name="maximum_unit[]" placeholder="Maximum Unit" id="maximum_unit_2" class="span4">
                 <input type="text" name="minimum_unit[]" placeholder="Minimum Unit" id="minimum_unit_2" class="span4"><br/>';
        }
        $html=$html1.$html2;*/
                  //<input style="display:none;" type="text" name="maximum_unit[]" placeholder="Maximum Unit" id="maximum_unit_1" class="span4">
                 //<input style="display:none;" type="text" name="minimum_unit[]" placeholder="Minimum Unit" id="minimum_unit_1" class="span4">

                 //<input style="display:none;" type="text" name="maximum_unit[]" placeholder="Maximum Unit" id="maximum_unit_2" class="span4">
                 //<input style="display:none;" type="text" name="minimum_unit[]" placeholder="Minimum Unit" id="minimum_unit_2" class="span4">
     // }
     // dd(json_encode($formfactors_val));
      //return json_encode($formfactors_val);
    //  if(count($diff_unit)>0)
      //{
       // echo json_encode($formfactors_val);
      //}
      //else
      //{
        //echo 'NAN';
      //}
     // echo "C";
   }
    
}
