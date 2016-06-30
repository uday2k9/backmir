<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\Sitesetting; /* Model name*/
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Input; /* For input */
use Validator;
use Session;
use Imagine\Image\Box;
use Image\Image\ImageInterface;
use Illuminate\Pagination\Paginator;
use DB;

class SitesettingController extends Controller {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function __construct() {

      view()->share('sitesetting_class','active');
    }
   public function index()
   {
        $limit = 10;
		$sitesettings = DB::table('sitesettings')->orderBy('name','ASC')->paginate($limit);
        //echo '<pre>';print_r($vitamins); exit;
	    $sitesettings->setPath('sitesetting');
        return view('admin.sitesetting.index',compact('sitesettings'),array('title'=>'Site Setting Management','module_head'=>'Site Setting'));

    }

    public function edit($id)
    {
        $sitesettings=Sitesetting::find($id);
        //echo   $sitesettings ; exit;
        return view('admin.sitesetting.edit',compact('sitesettings'),array('title'=>'Edit Site Setting','module_head'=>'Edit Site Setting'));
    }

   
    public function update(Request $request, $id)
    {
        //
       $sitesettingUpdate=Request::all();
           $sitesetting=Sitesetting::find($id);
           //$cmsUpdate['description']=htmlentities($cmsUpdate['description']);
           if (Input::hasFile('image'))
            {
              $destinationPath = 'uploads/share_image/'; // upload path
              $thumb_path = 'uploads/share_image/thumb/';
              $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
              $fileName = rand(111111111,999999999).'.'.$extension; // renameing image
              Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
              // $this->create_thumbnail($thumb_path,$fileName,$extension); 
              $sitesettingUpdate['value']=$fileName;

              // unlink old photo
              @unlink('uploads/share_image/'.Request::input('share_icon'));
            }
            elseif(Request::input('share_icon')!='')
               $sitesettingUpdate['value']=Request::input('share_icon');

           $sitesetting->update($sitesettingUpdate);
	   
	    $sitesettings = DB::table('sitesettings')->get();
	    $all_sitesetting = array();
	    $current='';
	    foreach($sitesettings as $each_sitesetting)
	    {
		//$all_sitesetting[$each_sitesetting->name] = $each_sitesetting->value;
		$current .= strtoupper($each_sitesetting->name)."=".$each_sitesetting->value."\n";
	    }
	   $file = '.env';
	   
	   
	   file_put_contents($file, $current);
	   
           Session::flash('success', 'Site setting updated successfully'); 
           return redirect('admin/sitesetting');
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
        Sitesetting::find($id)->delete();
        Session::flash('success', 'Site setting deleted successfully'); 
        return redirect('admin/sitesetting');
    }
    
    
}
