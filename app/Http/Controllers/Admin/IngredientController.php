<?php namespace App\Http\Controllers\Admin;

use App\User;
use App\Model\Brandmember; /* Model name*/
use App\Model\Ingredient; /* Model name*/
use App\Model\FormFactor; /* Model name*/
use App\Model\IngredientFormfactor; /* Model name*/
use App\Model\Component; /* Model name*/
use App\Model\ComponentVitamin; /* Model name*/

use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Input; /* For input */
use Validator;
use Session;
use DB;
use Mail;
use Hash;
use Auth;

class IngredientController extends Controller {

   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
  	public function __construct() {

  		//echo '<br/>'.$method = Request::getPathInfo();
  		//echo '<br/>'.Route::getCurrentRoute()->getName();
  		view()->share('ingredient_class','active');
  	}
   
    

   public function index($param = false)
   {
		
      
      $limit = 10;
      if($param){

        $ingredients = DB::table('ingredients')->where('name', 'LIKE', '%' . $param . '%')->orWhere('chemical_name', 'LIKE', '%' . $param . '%')->orderBy('id','DESC')->paginate($limit);
        $ingredients->setPath($param);
      }
      else{

        $ingredients = DB::table('ingredients')->orderBy('id','DESC')->paginate($limit);
        $ingredients->setPath('ingredient-list');
      }

      
      $ingredient = \App\Model\Ingredient::with('ingredientFormfactor')->get();

      $formfactor = \App\Model\FormFactor::with('ingredientFormfactor')->get();
      $m=0;
      $all_data_value = array();
      foreach($ingredient as $each_ingredeient)
      {
        $data= array();
        
        foreach($each_ingredeient->ingredientFormfactor as $each_formfactor)
        {
          $data[] =DB::table('form_factors')->select( 'id','name')->whereId($each_formfactor->form_factor_id)->first();
          //echo "<pre>".print_r($data);
        }

        foreach($data as $each_data)
        {
          $all_data_value[$m]['id'] = $each_data->id;
          $all_data_value[$m]['name'] = $each_data->name;
          $all_data_value[$m]['ingredient_id'] = $each_ingredeient->id;
          $m++;
        }
      }

      //echo "<pre>";print_r($all_data_value);
      foreach($ingredients as $each)
      {
          $each->formfactorname = '';

          foreach ($all_data_value as $key => $each_value) 
          {
            if($each_value['ingredient_id']==$each->id)
            $each->formfactorname .= $each_value['name'].',';
          }
      }
      $test = 1;
      return view('admin.ingredient.index',compact('ingredients','param','test'),array('title'=>'Ingredient Management','module_head'=>'Ingredient'));

   

  }
  public function create()
  {
    $all_formfactors = FormFactor::all();
    return view('admin.ingredient.create',compact('all_formfactors'),array('title'=>'ingredient Management','module_head'=>'Add ingredient'));
  }

  public function store(Request $request)
  {
      
    	//echo "<pre>";print_r(Request::all());exit;

	  	$fileName = '';
	  	if(Input::hasFile('image1')){
	
			$destinationPath = 'uploads/ingredient/'; 	// upload path
			$thumb_path = 'uploads/ingredient/thumb/';
			$extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
			$fileName = rand(111111111,999999999).'.'.$extension; // renameing image
			Input::file('image')->move($destinationPath, $fileName); // uploading file to given path
	    }

		$ingredient['image']             = $fileName;
		$ingredient['name']             = Request::input('name');
		$ingredient['description']      = htmlentities(Request::input('description'));
		$ingredient['chemical_name']    = Request::input('chemical_name');
		$ingredient['price_per_gram']   = Request::input('price_per_gram');
		$ingredient['list_manufacture'] = Request::input('list_manufacture');
		$ingredient['type']             = Request::input('type');
		$ingredient['organic']          = Request::input('organic');
		$ingredient['antibiotic_free']  = Request::input('antibiotic_free');
		$ingredient['gmo']              = Request::input('gmo');
    $ingredient['status']           = Request::input('status');

		$ingredient_row = Ingredient::create($ingredient);
		$lastinsertedId = $ingredient_row->id;

      

		// Add Ingredient form factor
		if(Request::input('form_factor')!=NULL){
			foreach (Request::input('form_factor') as $key => $value) {
				$arr = array('ingredient_id'=>$lastinsertedId,'form_factor_id'=>$value);
				IngredientFormfactor::create($arr);
			}	
		}
      
      	// Add Component and vitamins 
		/*$cnt = 0;
		foreach (Request::input('component_name') as $key => $each_component) {

		 if($each_component['name']!="" && $each_component['percentage']!=""){	
			$new_arr = array('ingredient_id'=>$lastinsertedId,'component_name'=>$each_component['name'],'percentage'=>$each_component['percentage']);
			$component_row = Component::create($new_arr);
			$lastComponentId = $component_row->id;
			$new_vitamin_arr = array();
			 echo '<pre>';print_r($each_component['vitamin']);exit;
			
			for($i=0;$i<count($each_component['vitamin']);$i++){
				$new_vitamin_arr = array('component_id'=>$lastComponentId,'vitamin'=>$each_component['vitamin'][$i],'weight'=>$each_component['weight'][$i],'vitamin_weight'=>$each_component['vitamin_weight_'.$cnt][0]);
				$component_row = ComponentVitamin::create($new_vitamin_arr);
				$cnt++;
			}
		  }
      	}*/
		//exit;

      
      Session::flash('success', 'Ingredient added successfully'); 
      return redirect('admin/ingredient');
  }

  public function edit($id)
  {
    $ingredient=Ingredient::find($id);
    $all_formfactors = FormFactor::all();
    $check_formfactors = DB:: table('ingredient_formfactors')->select('form_factor_id')->where('ingredient_id','=',$id)->get();
    $all_check_formfactors=array();
    foreach ($check_formfactors as $key => $value) 
    {
      $all_check_formfactors[]=$value->form_factor_id;
    } 
    //print_r($all_check_formfactors);exit;

    $components = DB:: table('components')->where('ingredient_id','=',$id)->get();

    //echo "<pre>";print_r($components);exit;
    $all_components=array();
    if(!empty($components)){
	    foreach ($components as $each_component) 
	    {
	      $tmp['vitamins'] = array();
	      $tmp['component_details']=$each_component;
	      $vitamins = DB:: table('component_vitamins')
	                        ->select('vitamin','weight','vitamin_weight')
	                        ->where('component_id','=',$each_component->id)
	                        ->get();

	      $m = 0;
	      foreach ($vitamins as $key_vitamin => $each_vitamin) 
	      {
	        $tmp['vitamins'][$m]=$each_vitamin->vitamin;
	        $tmp['weights'][$m]=$each_vitamin->weight;
			$tmp['vitamin_weight'][$m]=$each_vitamin->vitamin_weight;
	        $m++;
	      }
	      $all_components[]=$tmp;

	    }
	} 
    
    //echo "<pre>";print_r($all_components); exit;
    return view('admin.ingredient.edit',compact('ingredient','all_formfactors','all_check_formfactors','all_components'),array('title'=>'Edit Ingredient','module_head'=>'Edit Ingredient'));
  }

   
  public function update(Request $request, $id)
  {
    //print_r($_POST);exit;

		$ingredient['name']             = Request::input('name');
		$ingredient['description']      = htmlentities(Request::input('description'));
		$ingredient['chemical_name']    = Request::input('chemical_name');
		$ingredient['price_per_gram']   = Request::input('price_per_gram');
		$ingredient['list_manufacture'] = Request::input('list_manufacture');
		$ingredient['type']             = Request::input('type');
		$ingredient['organic']          = Request::input('organic');
		$ingredient['antibiotic_free']  = Request::input('antibiotic_free');
		$ingredient['gmo']              = Request::input('gmo');
    $ingredient['status']           = Request::input('status');
   // $ingredient['weight_measurement']= Request::input('weight_measurement');

		if(Input::hasFile('image')){
			$destinationPath = 'uploads/ingredient/'; 	// upload path
			$thumb_path = 'uploads/ingredient/thumb/';
			$extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
			$fileName = rand(111111111,999999999).'.'.$extension; // renameing image
			Input::file('image')->move($destinationPath, $fileName); // uploading file to given path

			// Unlink old image
			@unlink('uploads/ingredient/'.Request::input('hidden_image'));
		}
		else{
			$fileName = Request::input('hidden_image');
		}
		$ingredient['image'] = $fileName;

		$ingredient_result=Ingredient::find($id);
		$ingredient_result->update($ingredient);

		
		// Delete All Form factor first and then add
		DB::table('ingredient_formfactors')->where('ingredient_id',$id)->delete();
		if(Request::input('form_factor')!=NULL){
			// Add Ingredient form factor
			foreach (Request::input('form_factor') as $key => $value) {

				$arr = array('ingredient_id'=>$id,'form_factor_id'=>$value);
				IngredientFormfactor::create($arr);
			}
		}

		/*// Delete All Component and Vitamins first and then add
		$all_component = DB::table('components')->where('ingredient_id',$id)->get();
		$component_ids = array();
		if(!empty($all_component)){
			foreach($all_component as $each_exist_component){
				$component_ids[] = $each_exist_component->id;
			}
		}
		// Delete All Vitamins to that Component
		DB::table('component_vitamins')->where('component_id',$component_ids)->delete();
		
		//Delete Component
		DB::table('components')->where('ingredient_id',$id)->delete();

		// Add Component and vitamins

		foreach (Request::input('component_name') as $key => $each_component) {

			$new_arr = array('ingredient_id'=>$id,'component_name'=>$each_component['name'],'percentage'=>$each_component['percentage']);
			$component_row = Component::create($new_arr);
			$lastComponentId = $component_row->id;
			$new_vitamin_arr = array();
			//foreach ($each_component['vitamin'] as $key => $each_vitamin) {
			for($i=0;$i<count($each_component['vitamin']);$i++) {
             $weight_vitamin = $each_component['weight'][$i];

			 $new_vitamin_arr = array('component_id'=>$lastComponentId,'vitamin'=>$each_component['vitamin'][$i],'weight'=>$weight_vitamin,'vitamin_weight'=>$each_component['vitamin_weight_'.$i][0]);
			 $component_row = ComponentVitamin::create($new_vitamin_arr);
			}
		}*/

        //echo $id;
        //echo "<pre>";print_r(Request::all());exit;

         Session::flash('success', 'Ingredient updated successfully'); 
         return redirect('admin/ingredient-list');
  }

  public function destroy($id)
  {
      //
      Ingredient::find($id)->delete();
      Session::flash('success', 'Ingredient deleted successfully'); 
      return redirect('admin/ingredient');
  }

  public function ingredient_search($param = false){

    $ingredients = DB::table('ingredients')->where('name', 'LIKE', '%' . $_REQUEST['term'] . '%')->groupBy('name')->orderBy('name','ASC')->get();
    $arr = array();

    foreach ($ingredients as $value) {
        
        $arr[] = $value->name;
    }
    echo json_encode($arr);
  }

  public function viatmin_auto_search($param = false){

    $vitamins = DB::table('component_vitamins')->where('vitamin', 'LIKE', '%' . $_REQUEST['term'] . '%')->groupBy('vitamin')->orderBy('vitamin','ASC')->get();
    $arr = array();

    foreach ($vitamins as $value) {
        
        $arr[] = $value->vitamin;
    }
    echo json_encode($arr);
  }

  public function component_auto_search($param = false){

    $components = DB::table('components')->where('component_name', 'LIKE', '%' . $_REQUEST['term'] . '%')->groupBy('component_name')->orderBy('component_name','ASC')->get();
    $arr = array();

    foreach ($components as $value) {
        
        $arr[] = $value->component_name;
    }
    echo json_encode($arr);
  }


}
