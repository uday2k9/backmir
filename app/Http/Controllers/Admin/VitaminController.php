<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\Vitamin; /* Model name*/
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

class VitaminController extends Controller {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function index()
   {
        $limit = 5;
		$vitamins = DB::table('vitamins')->orderBy('id','DESC')->paginate($limit);
        //echo '<pre>';print_r($vitamins); exit;
	    $vitamins->setPath('vitamin');
        return view('admin.vitamins.index',compact('vitamins'),array('title'=>'Vitamin Management','module_head'=>'Vitamins'));

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
        $vitamin=Vitamin::find($id);
        return view('admin.vitamins.edit',compact('vitamin'),array('title'=>'Edit Vitamin','module_head'=>'Edit Vitamin'));
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
        //
           $vitaminUpdate=Request::all();
           $vitamin=Vitamin::find($id);
           $vitamin->update($vitaminUpdate);
           Session::flash('success', 'Vitamin updated successfully'); 
           return redirect('admin/vitamin');
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
        Vitamin::find($id)->delete();
        return redirect('admin/vitamin');
    }
    
    public function change_password()
    {
        echo "change password"; exit;
    }

    public function create_thumbnail($path, $filename, $extension)
    {
        $width  = 50;
        $height = 50;
        $mode   = ImageInterface::THUMBNAIL_OUTBOUND;
        $size   = new Box($width, $height);

        $thumbnail   = Imagine::open("{$path}/{$filename}.{$extension}")->thumbnail($size, $mode);
        $destination = "{$filename}.thumb.{$extension}";

        $thumbnail->save("{$path}/{$destination}");
    }
}
