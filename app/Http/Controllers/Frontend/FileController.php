<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/

use App\Model\Directory; /* Model name*/
use App\Model\Subscription;
use App\Model\Product; /* Model name*/
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
use Mail;
use App\Helper\helpers;
use Authorizenet;
use App\libraries\auth\AuthorizeNetCIM;
use App\libraries\auth\shared\AuthorizeNetCustomer;
use App\libraries\auth\shared\AuthorizeNetPaymentProfile;
use App\libraries\auth\shared\AuthorizeNetAddress;
use File;
use App\Model\Filemanagement; 


class FileController extends BaseController {

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
       if(Session::has('brand_userid')){
          $brand_user_id = Session::get('brand_userid');
       }   

        $limit=5;
        $directories = Filemanagement::where('status',1)
                      ->where('uploaded_by',$brand_user_id)
                      ->whereIn('directory_id', [0,99999])
                      ->orderBy('updated_at','DESC')
                      ->paginate($limit);       
            
        return view('frontend.file.list',compact('directories'),array('title'=>'File Management','brand_active'=>'active'));

    }

    public function getCreate()
    {      
       if(Session::has('brand_userid')){
          $brand_user_id = Session::get('brand_userid');
       }

       $directories = Filemanagement::where('uploaded_by',$brand_user_id)->where('directory',1)->get();
       return view('frontend.file.create',compact('directories'),array('title' => 'Create Directory'));

    } 

    public function postCreate(Request $request)
    {     
        // check if brand user id exists in session   
        if(Session::has('brand_userid')){
            $brand_user_id = Session::get('brand_userid');
            $root_folder_name=$brand_user_id;  //root folder name
            $root_folder = base_path().'/uploads/file_management/'.$root_folder_name;
            $root_folder_upload = '/uploads/file_management/'.$root_folder_name;
           
            //Check if base folder exists
            if (!file_exists($root_folder)) {

                // if not create new folder
                File::makeDirectory($root_folder, $mode = 0777, true, true);      

                // check if folder created          
                if (!file_exists($root_folder)) {
                    Session::flash('error', 'Error! Please try again.'); 

                    return redirect('file');
                }
            }
        }
       
        $file_name=Request::input('directory_name');       
        
        $upload_path = '';      
        $http_path=url().$root_folder.'/'.$upload_path.$file_name;        
        $absolute_path=$root_folder.'/'.$upload_path.$file_name;   
        $path=$absolute_path;       
        //Check if user's choice folder exists
        if (!file_exists($path)) {

            //Create user's choice folder
            File::makeDirectory($path, $mode = 0777, true, true);

            // check if file exists
            if (file_exists($path)) {               
                // store data in database  
                $filemanagement = new Filemanagement();     
                $directory_id=Request::input('directory_name');
                $filemanagement->directory_id  = $directory_id;
                $filemanagement->file_name  = $file_name;
                $filemanagement->original_file_name  = $file_name; 
                $filemanagement->file_path  = $absolute_path;
                $filemanagement->file_url  = $http_path;
                $filemanagement->status  = '1';
                $filemanagement->directory  = 1;
                $filemanagement->uploaded_by  = $brand_user_id;
                $filemanagement->created_at  = \Carbon\Carbon::now();
                $filemanagement->updated_at  = \Carbon\Carbon::now();


                if($filemanagement->save())
                {
                    Session::flash('success', 'Directory added successfully');      
                    return redirect('file');
                }
            }            
        }
        else
        {
            Session::flash('error', 'Directory already exists.'); 
     
            return redirect('file');
        }
    }  

    public function getList($id)
    {
       $limit=5;
       if(Session::has('brand_userid')){
          $brand_user_id = Session::get('brand_userid');
       }

       $filemanagement = Filemanagement::where('uploaded_by',$brand_user_id)
                         ->where('directory_id',$id)
                         ->where('status',1)
                         ->paginate($limit);      

       return view('frontend.file.files',compact('filemanagement','id'),array('title'=>'File Management','brand_active'=>'active'));

    }

    public function getUpload($id='')
    {       
       if(Session::has('brand_userid')){
          $brand_user_id = Session::get('brand_userid');
       }
       $directories = Filemanagement::where('uploaded_by',$brand_user_id)
                      ->where('directory',1)
                      ->get();

       return view('frontend.file.upload',compact('directories','id'),array('title'=>'Upload File','brand_active'=>'active'));

    }

    public function postUpload()
    {      
        // check if brand user id exists in session   
        if(Session::has('brand_userid')){
            $brand_user_id = Session::get('brand_userid');
            $root_folder_name=$brand_user_id;  //root folder name
            $root_folder = base_path().'/uploads/file_management/'.$root_folder_name;
            $root_folder_upload = '/uploads/file_management/'.$root_folder_name;
           
            //Check if base folder exists
            if (!file_exists($root_folder)) {

                // if not create new folder
                File::makeDirectory($root_folder, $mode = 0777, true, true);      

                // check if folder created          
                if (!file_exists($root_folder)) {
                    Session::flash('error', 'Error! Please try again.'); 

                    return redirect('file');
                }
            }
        }
        $folder_name=Request::input('directory_name');       
        if($folder_name!=99999)
        {          
          $http_path=url().'/uploads/file_management/'.$root_folder_name.'/'.Request::input('dir_name');  
          $absolute_path=$root_folder.'/'.Request::input('dir_name');  
        }
        else
        {
          $file_name='';
          $http_path=url().'/uploads/file_management/'.$root_folder_name;
          $absolute_path=$root_folder;         
        }
       
        //dd($http_path);
        //Check if user's choice folder exists
        if (file_exists($absolute_path)) {        
          $file_ori = Input::file('image1');
          for($i=0; $i<count($file_ori); $i++)
          {
            $file = Input::file('image1');           
            // create random file name
            $new_file_name='file_'.time().rand(100000,999999).'_'.$brand_user_id.'.'.$file[$i]->getClientOriginalExtension();                     
            // create upload path
            $destinationPath = $absolute_path;        
            // set http url
            $destinationUrl = $http_path.'/'.$new_file_name;           
            // move file to specific folder
            $file[$i]->move($destinationPath, $new_file_name);
            // store data
            $filemanagement = new Filemanagement();     
            $directory_id=Request::input('directory_name');
            $filemanagement->directory_id  = $directory_id;
            $filemanagement->file_name  = $new_file_name;
            $filemanagement->original_file_name  = $file[$i]->getClientOriginalName(); 
            $filemanagement->file_path  = $destinationPath;
            $filemanagement->file_url  = $destinationUrl;
            $filemanagement->status  = '1';
            $filemanagement->directory  = 0;
            $filemanagement->uploaded_by  = $brand_user_id;
            $filemanagement->created_at  = \Carbon\Carbon::now();
            $filemanagement->updated_at  = \Carbon\Carbon::now();
            $filemanagement->save();
          }
          Session::flash('success', 'File added successfully'); 
          return redirect('file');
      }
      else
      {
        Session::flash('error', 'Error! Please try again.'); 

        return redirect('file');          
      }
      
    }

    
    public function getDelete($id)
    {        
      $directories=Filemanagement::findOrFail($id);      
      $dir_path=$directories->file_path;
      if($directories->directory==0)
      {
        $file_details=Filemanagement::find($id);
       // dd($file_details->directory_id);
        $full_path=$directories->file_path.'/'.$directories->file_name;
        if(File::exists($full_path)) 
        { 
          File::delete($full_path);
        }        
        Filemanagement::where('id',$id)->delete();

        Session::flash('success', 'Data deleted successfully');  
        if($file_details->directory_id==99999)   
        {
          return redirect('file');
        }
        else
        {
          return redirect('file/list/'.$file_details->directory_id); 
        }      
        return redirect('file/');
      }
      if($directories->directory==1)
      {        
        //check if directory exists
        if(File::exists($dir_path)) 
        {             
          //Delete directory and all files under it    
          $success = File::deleteDirectory($dir_path);
          
          //Remove all files from database under directory 
          Filemanagement::where('id',$id)->delete();

          Session::flash('success', 'Data deleted successfully');      
          return redirect('file/');
        } // if directory not found through error
        else
        {
          Session::flash('error', 'An error occurred! Please try again later.');      
          return redirect('file/');
        }
      }     

    }

    public function getRename($id)
    {  
      $files=Filemanagement::findOrFail($id);
      $file_name=$files->file_name;
      $get_ext=pathinfo($file_name, PATHINFO_EXTENSION);
      $file_ext='.'.$get_ext;
      $only_file_name=basename($file_name,$file_ext);
      
      return view('frontend.file.rename',compact('files','id','only_file_name','file_ext'),array('title'=>'Rename File','brand_active'=>'active'));
      
    }

    public function postRename()
    {    

      $file_id=Request::input('id');
      $new_name_only=Request::input('name');
      $file_ext=Request::input('file_ext');
      $new_name=$new_name_only.$file_ext;
      $files=Filemanagement::findOrFail($file_id);
      //dd($files->directory_id);
      $old_name=$files->file_name;
      $old_path=$files->file_path.'/'.$old_name;
      $new_path=$files->file_path.'/'.$new_name;
      $old_url=$files->file_url;
     // end(split('-',$str))
      $file_url_only=explode('/',$old_url);
      $num = (count($file_url_only) - 1);      
     
      $new_url=str_replace($file_url_only[$num],$new_name,$old_url);      
     
      if(!File::exists($new_path)) 
      {        
        //rename file
        if ( ! File::move($old_path, $new_path))
        {
           Session::flash('error', 'An error occurred! Please try again later.');      
           return redirect('file');
        }
        else
        {
             // update file name in database
             $filemanagement = new Filemanagement();                  
             $files->file_name  = $new_name;           
             $files->file_url  = $new_url;     
             $files->updated_at  = \Carbon\Carbon::now();
            
             if($files->save()) // save file
             {
                Session::flash('success', 'File rename successfully');   
                if($files->directory_id==99999)   
                {
                  return redirect('file');
                }
                else
                {
                  return redirect('file/list/'.$files->directory_id); 
                }
             }
        }//
      }
      else
      {
          Session::flash('error', 'File with same name already exists!');
          if($files->directory_id==99999)   
          {
            return redirect('file');
          }
          else
          {
            return redirect('file/list/'.$files->directory_id); 
          }     
          
      }
    }

    
}