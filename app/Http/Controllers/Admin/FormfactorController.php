<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\FormFactor; /* Model name*/
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Input; /* For input */
use Validator;
use Session;
use Illuminate\Pagination\Paginator;
use DB;
use App\Helper\helpers;

class FormfactorController extends Controller {

    public function __construct() 
    {
      view()->share('formfactor_class','active');
    }

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function index($param = false)
   {
    
        //echo '<pre>';print_r($_SERVER['REQUEST_URI']); exit;

        $limit = 10;
        if($param){
		  $formfactors = DB::table('form_factors')->where('name', 'LIKE', '%' . $param . '%')->orderBy('name','ASC')->paginate($limit);
          $formfactors->setPath($param);
        }
        else{
          $formfactors = DB::table('form_factors')->orderBy('name','ASC')->paginate($limit);
          $formfactors->setPath('form-factor');
        }


        return view('admin.formfactor.index',compact('formfactors','param'),array('title'=>'Form Factor Management','module_head'=>'Form Factor'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.formfactor.create',array('title'=>'Form Factor Management','module_head'=>'Add Form Factor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$formfactors=Request::all();
        //dd(Request::input('unit'));
        if($_FILES['image']['name']!="")
        {
            $destinationPath = 'uploads/formfactor/';   // upload path
            $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(111111111,999999999).'.'.$extension; // renameing image
            Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
        }
        else
        {
            $fileName = '';
        }       

        $form_factor = new FormFactor();

        $form_factor->name          = Request::input('name');
        $form_factor->image                  = $fileName;   
        $form_factor->price          = Request::input('price');
        $form_factor->minimum_weight          = Request::input('minimum_weight');
        $form_factor->maximum_weight          = Request::input('maximum_weight');
        $form_factor->count_unit          = Request::input('count_unit');

        if($form_factor->save())
        {
            Session::flash('success', 'Form Factor added successfully'); 
            return redirect('admin/formfactor');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $formfactors=FormFactor::find($id);
       return view('admin.formfactor.show',compact('formfactors'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $formfactor=FormFactor::find($id);
        return view('admin.formfactor.edit',compact('formfactor'),array('title'=>'Edit Form Factor','module_head'=>'Edit Form Factor'));
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
        //$formfactorUpdate=Request::all();
        $form_factor=Formfactor::find($id);

        if (Input::hasFile('image'))
        {
            $destinationPath = 'uploads/formfactor/';                           // upload path
            $extension = Input::file('image')->getClientOriginalExtension();    // getting image extension
            $fileName = rand(111111111,999999999).'.'.$extension;               // renameing image
            Input::file('image')->move($destinationPath, $fileName);            // uploading file to given path
           
            $form_factor->image           = $fileName;

            // Unlink old image
            @unlink('uploads/formfactor/'.Request::input('hidden_image'));
            
        }        
        $form_factor->name          = Request::input('name');           
        $form_factor->price          = Request::input('price');
        $form_factor->minimum_weight          = Request::input('minimum_weight');
        $form_factor->maximum_weight          = Request::input('maximum_weight');
        $form_factor->count_unit          = Request::input('count_unit');

      
        if($form_factor->save())
        {
            Session::flash('success', 'Form Factor updated successfully'); 
            return redirect('admin/formfactor');
        }
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
        Formfactor::find($id)->delete();
        Session::flash('success', 'Form Factor deleted successfully'); 
        return redirect('admin/formfactor');
    }
    

    public function form_factor_name($param = false)
    {
       
        $formfactors = DB::table('form_factors')->where('name', 'LIKE', '%' . $_REQUEST['term'] . '%')->groupBy('name')->orderBy('name','ASC')->get();
        $arr = array();
        foreach ($formfactors as $value) {
            
            $arr[] = $value->name;
        }
        echo json_encode($arr);
    }
    
}
