<?php namespace App\Http\Controllers\Admin; /* path of this controller*/

use App\Model\Brandmember;                  /* Model name*/
use App\Model\MemberProfile;                /* Model name*/
use App\Model\Product;                      /* Model name*/
use App\Model\ProductIngredientGroup;       /* Model name*/
use App\Model\ProductIngredient;            /* Model name*/
use App\Model\ProductFormfactor;            /* Model name*/
use App\Model\ProductFormfactorDuration;     /* Model name: For custom time frame */
use App\Model\Ingredient;                   /* Model name*/
use App\Model\FormFactor;                   /* Model name*/
use App\Model\Searchtag;                    /* Model name*/
use App\Model\Tag;                          /* Model name new*/
use App\Model\Ratings; 
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
use Hash;
use Auth;
use Cookie;
use Redirect;

use App\Helper\helpers;
use App\libraries\Usps;

class ProductController extends BaseController {
  var $obj;
    public function __construct() 
    {

      parent::__construct(); 
      view()->share('product_class','active');
      $obj = new helpers();
      $this->obj = $obj;
    }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */

    public function index($discountinue = false,$param = false)
    {

      // $lib = new Usps();
      // $arr = array('user'=>'293TESTC3874','Zip5'=>'90210','FromName'=>'John Doe','FromAddress1'=>'RM 2100','FromAddress2'=>'475 L’Enfant Plaza SW','FromCity'=>'Washington','FromState'=>'DC','FromZip5'=>20260,'ToName'=>'Janice Dickens','ToFirm'=>'XYZ Corporation','ToAddress1'=>'Ste 100','ToAddress2'=>'2 Massachusetts Ave NE','ToCity'=>'Washington','ToState'=>'DC','ToZip5'=>20212,'WeightInOunces'=>10,'ServiceType'=>'PRIORITY','Size'=>'LARGE','Width'=>7,'Length'=>20.5,'Height'=>15,'Girth'=>60);
      // $lib->USPSLabel($arr);exit;
       
      $limit = 10;
      if($param){

        $condition_arr = array('is_deleted'=>0,'discountinue'=>$discountinue);
        $products = Product::with('GetBrandDetails','AllProductFormfactors')->where($condition_arr)->where('product_name', 'LIKE', '%' . $param . '%')->orderBy('id','DESC')->paginate($limit);

        $products->setPath('product-list/'.$discountinue.'/'.$param);
      }
      else{

          $condition_arr = array('is_deleted'=>0,'discountinue'=>$discountinue);
          $products = Product::with('GetBrandDetails','AllProductFormfactors')->where($condition_arr)->orderBy('id','DESC')->paginate($limit);

          //$products->setPath('product-list/'.$discountinue);
      }


      //Get all formfactor names
      if(!empty($products)){

        foreach ($products as $key => $value) {

          if(!empty($value->AllProductFormfactors)){

            $value->formfactor_name = '';
            $value->formfactor_price = '';
            foreach ($value->AllProductFormfactors as $key1 => $each_formfactor) {

              if($each_formfactor['servings']!=0){

                $frm_fctr = DB::table('form_factors')->where('id',$each_formfactor['formfactor_id'])->first();

                // Assign Form-factor and its prices  
                $value->formfactor_name .= ' '.$frm_fctr->name.' ($'.number_format($each_formfactor->actual_price,2).')<br/>';

              }

            } 
          }
        }

      }

      if($discountinue==0)
        $title = "Continue";
      else
        $title = "Discontinue";
      
     //echo "<pre>";print_r($products);     exit;
      return view('admin.product.index',compact('products','param','discountinue'),array('title'=>$title.' Products','module_head'=>$title.' Products'));
    }

    public function discontinue_product_search($param = false){

      $ingredients = DB::table('products')->where('product_name', 'LIKE', '%' . $_REQUEST['term'] . '%')->where('discountinue',$param)->groupBy('product_name')->orderBy('product_name','ASC')->get();
      $arr = array();

      foreach ($ingredients as $value) {
          
          $arr[] = $value->product_name;
      }
      echo json_encode($arr);
    }

    public function edit111($id)
    {
      
      // Get All Ingredient whose status != 2
      $ingredients = DB::table('ingredients')->whereNotIn('status',[2])->get();
      
      // Get All Form factors
      $formfac = FormFactor::all();

      // Get Product details regarding to slug
      $products = DB::table('products')->where('id',$id)->first();

      // Get Ingredient group and their individual ingredients
      $ingredient_group = DB::table('product_ingredient_group')->where('product_id',$products->id)->get();

      $check_arr = $weight_check_arr = $arr = $ing_form_ids = $all_ingredient = $group_ingredient = array(); 
      $total_count = $tot_price = $tot_weight = 0;

     $total_group_count = 0;
      if(!empty($ingredient_group)){
        foreach($ingredient_group as $each_ing_gr){
           $i = 0;
          $total_group_weight = 0;
          $group_ingredient[$i]['group_name'] = $each_ing_gr->group_name;

          $ingredient_lists = DB::table('product_ingredients')->select(DB::raw('product_ingredients.*,ingredients.price_per_gram,ingredients.name'))->Join('ingredients','ingredients.id','=','product_ingredients.ingredient_id')->where('ingredient_group_id',$each_ing_gr->id)->get();
          if(!empty($ingredient_lists)){
            foreach($ingredient_lists as $each_ingredient_list){

              $tot_weight += $each_ingredient_list->weight;
              $total_group_weight += $each_ingredient_list->weight;

              // collect total price
              $tot_price += $each_ingredient_list->ingredient_price;

              // put all ingredient in an array
              $all_ingredient[$total_count]['id'] = $each_ingredient_list->ingredient_id;
              $all_ingredient[$total_count]['name'] = $each_ingredient_list->name;

              $group_ingredient[$i]['all_group_ing'][] = array('ingredient_id'=>$each_ingredient_list->ingredient_id,'weight'=>$each_ingredient_list->weight,'price_per_gram'=>$each_ingredient_list->price_per_gram,'ingredient_price'=>$each_ingredient_list->ingredient_price);
              $total_count++;
            }
            $group_ingredient[$i]['tot_weight'] = $total_group_weight;
          }
          $total_group_count++;
          $i++;
        }
      }
    

      //Get All individual ingredient
      $individual_total_count =0;
      $individual_ingredient_lists = DB::table('product_ingredients')->select(DB::raw('product_ingredients.*,ingredients.price_per_gram,ingredients.name'))->Join('ingredients','ingredients.id','=','product_ingredients.ingredient_id')->where('ingredient_group_id',0)->where('product_id',$products->id)->get();
      if(!empty($individual_ingredient_lists)){
        foreach ($individual_ingredient_lists as $key => $value1) {
            $tot_weight += $value1->weight;
            $tot_price += $value1->ingredient_price;

            // put all ingredient in an array
            $all_ingredient[$total_count]['id'] = $value1->ingredient_id;
            $all_ingredient[$total_count]['name'] = $value1->name;
            $total_count++;
            $individual_total_count++;
        }
      }

      
      // Ingredient and their form factors
      if(!empty($all_ingredient)){

        foreach ($all_ingredient as $key => $value) {
          $arr = array();
          $ing_form_ids = DB::table('ingredients as I')->select(DB::raw('IFF.form_factor_id'))->Join('ingredient_formfactors as IFF','I.id','=','IFF.ingredient_id')->where('I.id',$value['id'])->get();

            if(!empty($ing_form_ids)){
              foreach ($ing_form_ids as $key1 => $value1) {
                $arr[] = $value1->form_factor_id;
              }
            }
          $all_ingredient[$key]['factors'] = $arr;
        }
      }


      //Get All Form factors corresponding to that product
      $pro_form_factor = DB::table('product_formfactors as pff')->select(DB::raw('pff.*,ff.name,ff.price,ff.maximum_weight,ff.minimum_weight'))->Join('form_factors as ff','ff.id','=','pff.formfactor_id')->where('product_id',$products->id)->get();  // Get All formfactor available for this product

      $pro_form_factor_ids = array();
      if(!empty($pro_form_factor)){
        $j=0;
        foreach ($pro_form_factor as $key => $value) {
          $pro_form_factor_ids[$j]['formfactor_id'] = $value->formfactor_id;
          $pro_form_factor_ids[$j]['name'] = $value->name;

          $check_arr[] = $value->formfactor_id;
          $j++;          
        }
      }

      // Get only those form factor which is created for this particular prouct
      //$pro_form_factor = DB::table('product_formfactors as pff')->select(DB::raw('pff.*,ff.name,ff.price,ff.maximum_weight,ff.minimum_weight'))->Join('form_factors as ff','ff.id','=','pff.formfactor_id')->where('product_id',$products->id)->where('actual_price','!=',0)->get();
      // Custom Time Frame
      $pro_form_factor = DB::table('product_formfactors as pff')
                         ->select(DB::raw('pff.*,ff.name,ff.price,ff.maximum_weight,ff.minimum_weight, product_formfactor_durations.*'))
                         ->Join('form_factors as ff','ff.id','=','pff.formfactor_id')
                         ->Join('product_formfactor_durations', 'product_formfactor_durations.product_formfactor_id', '=', 'pff.id')  
                         ->where('product_id',$products->id)->get();  // Get All formfactor available for this product

      
      $discountinue = $products->discountinue;

    // echo "<pre>";print_r($total_group_count);exit;

      
      return view('admin.product.edit',compact('products','ingredients','all_ingredient','check_arr','tot_weight','tot_price','formfac','pro_form_factor','group_ingredient','individual_ingredient_lists','pro_form_factor_ids','total_count','total_group_count','individual_total_count','discountinue'),array('title'=>'Edit Product','module_head'=>'Update Product'));
      
    }

    public function edit($id){
      // Get All Ingredient whose status != 2
      $ingredients = DB::table('ingredients')->whereNotIn('status',[0,2])->orderBy('name', 'asc')->get();
      
      // Get All Form factors
      $formfac = FormFactor::all();

      // Get Product details regarding id
     $products = Product::with('AllIngredientTags','AllFillingTags')->where('id',$id)->first();
    

    $products_new = Product::with('AllIngredientTags','AllFillingTags')->where('id',$id)->first();
    
     //  // Get Filling Tag details with regards to  id
     // $products = DB::table('products')->where('id',$id)->first();
     //  // Get Product details regarding id
     // $products = DB::table('products')->where('id',$id)->first();

      // Get Ingredient group and their individual ingredients
      $ingredient_group = DB::table('product_ingredient_group')->where('product_id',$products->id)->get();

      $check_arr = $weight_check_arr = $arr = $ing_form_ids = $all_ingredient = $group_ingredient = array(); 
      $total_group_count  = $total_count = $tot_price = $tot_weight = 0;

      if(!empty($ingredient_group)){
        $i = 0;
        foreach($ingredient_group as $each_ing_gr){

          $total_group_weight = 0;
          $group_ingredient[$i]['group_name'] = $each_ing_gr->group_name;

          $ingredient_lists = DB::table('product_ingredients')->select(DB::raw('product_ingredients.*,ingredients.price_per_gram,ingredients.name'))->Join('ingredients','ingredients.id','=','product_ingredients.ingredient_id')->where('ingredient_group_id',$each_ing_gr->id)->get();
          if(!empty($ingredient_lists)){
            foreach($ingredient_lists as $each_ingredient_list){

              $tot_weight += $each_ingredient_list->weight;
              $total_group_weight += $each_ingredient_list->weight;

              // collect total price
              $tot_price += $each_ingredient_list->ingredient_price;

              // put all ingredient in an array
              $all_ingredient[$total_count]['id'] = $each_ingredient_list->ingredient_id;
              $all_ingredient[$total_count]['name'] = $each_ingredient_list->name;

              $group_ingredient[$i]['all_group_ing'][] = array('ingredient_id'=>$each_ingredient_list->ingredient_id,'weight'=>$each_ingredient_list->weight,'price_per_gram'=>$each_ingredient_list->price_per_gram,'ingredient_price'=>$each_ingredient_list->ingredient_price);
              $total_count++;
            }
            $group_ingredient[$i]['tot_weight'] = $total_group_weight;
          }
           $total_group_count++;
          $i++;
        }
      }
    

      //Get All individual ingredient
       $individual_total_count =0;
      $individual_ingredient_lists = DB::table('product_ingredients')->select(DB::raw('product_ingredients.*,ingredients.price_per_gram,ingredients.name'))->Join('ingredients','ingredients.id','=','product_ingredients.ingredient_id')->where('ingredient_group_id',0)->where('product_id',$products->id)->get();
      if(!empty($individual_ingredient_lists)){
        foreach ($individual_ingredient_lists as $key => $value1) {
            $tot_weight += $value1->weight;
            $tot_price += $value1->ingredient_price;

            // put all ingredient in an array
            $all_ingredient[$total_count]['id'] = $value1->ingredient_id;
            $all_ingredient[$total_count]['name'] = $value1->name;
            $total_count++;
            $individual_total_count++;
        }
      }

      
      // Ingredient and their form factors
      if(!empty($all_ingredient)){

        foreach ($all_ingredient as $key => $value) {
          $arr = array();
          $ing_form_ids = DB::table('ingredients as I')->select(DB::raw('IFF.form_factor_id'))->Join('ingredient_formfactors as IFF','I.id','=','IFF.ingredient_id')->where('I.id',$value['id'])->get();

            if(!empty($ing_form_ids)){
              foreach ($ing_form_ids as $key1 => $value1) {
                $arr[] = $value1->form_factor_id;
              }
            }
          $all_ingredient[$key]['factors'] = $arr;
        }
      }


      //Get All Form factors corresponding to that product
      $pro_form_factor = DB::table('product_formfactors as pff')->select(DB::raw('pff.*,ff.name,ff.price,ff.maximum_weight,ff.minimum_weight'))->Join('form_factors as ff','ff.id','=','pff.formfactor_id')->where('product_id',$products->id)->get();  // Get All formfactor available for this product

      $pro_form_factor_ids = array();
      if(!empty($pro_form_factor)){
        $j=0;
        foreach ($pro_form_factor as $key => $value) {
          $pro_form_factor_ids[$j]['formfactor_id'] = $value->formfactor_id;
          $pro_form_factor_ids[$j]['name'] = $value->name;

          $check_arr[] = $value->formfactor_id;
          $j++;          
        }
      }

      // Check whether this product owns by brand or miramix
      if($products->own_product==1){
        $cnt = 0;
         foreach ($formfac as $key => $value) {
            $pro_form_factor_ids[$cnt]['formfactor_id'] = $value->id;
            $pro_form_factor_ids[$cnt]['name'] = $value->name;
            $cnt++;
          }
      }
     



      // Get only those form factor which is created for this particular prouct
      //$pro_form_factor = DB::table('product_formfactors as pff')->select(DB::raw('pff.*,ff.name,ff.price,ff.maximum_weight,ff.minimum_weight'))->Join('form_factors as ff','ff.id','=','pff.formfactor_id')->where('product_id',$products->id)->where('actual_price','!=',0)->get();

      // Custom Time Frame
      $pro_form_factor = DB::table('product_formfactors as pff')
                         ->select(DB::raw('pff.*,ff.name,ff.price,ff.maximum_weight,ff.minimum_weight, product_formfactor_durations.*'))
                         ->Join('form_factors as ff','ff.id','=','pff.formfactor_id')
                         ->Join('product_formfactor_durations', 'product_formfactor_durations.product_formfactor_id', '=', 'pff.id')
                         ->where('product_id',$products->id)
                         ->where('product_formfactor_durations.actual_price','!=',0)
                         ->get();
      
      

      //echo "<pre>";print_r($check_arr);exit;

      // Check Total COunt
      if($total_count==0)
        $total_count++;

      
      return view('admin.product.edit',compact('products','ingredients','all_ingredient','check_arr','tot_weight','tot_price','formfac','pro_form_factor','group_ingredient','individual_ingredient_lists','pro_form_factor_ids','total_count','total_group_count','individual_total_count'),array('title'=>'Edit Product','module_head'=>'Update Product'));
    }

    public function update(Request $request, $id)
    {
       //echo "<pre>";print_r(Request::all()); exit;
       $products_new = Product::where('id',$id)->first();

       $filling_tagsnewver=explode(",",Request::input('filling_tags'));
        $ingredient_tagsnewver=explode(",",Request::input('ingredient_tags'));
        $tags_new=array_merge($filling_tagsnewver,$ingredient_tagsnewver);
        $array_to_string="";
        foreach($tags_new as $tagsu){
          if($array_to_string==""){
            $array_to_string=$tagsu;
          }else{
            $array_to_string=$array_to_string.",".$tagsu;
          }

        }
        $array_to_string;

       $oldpname=$products_new->product_name;
       $newpname=Request::input('product_name');
       if($oldpname!=$newpname){
        Searchtag::where('product_id', '=', $id)->where('name','=',trim($oldpname))->where('type','=','product_name')->delete();
        $product_name_stag=Request::input('product_name');
        $arr = array('product_id'=>$id,'type'=>'product_name','name'=>trim($product_name_stag));
          Searchtag::create($arr);
       }
       //exit;
      $ingredient_tags=Tag::where('product_id', '=', $id)->where('type','=',2)->get()->toArray();
      $fillable_tags=Tag::where('product_id', '=', $id)->where('type','=',1)->get()->toArray();
      $ingredient_array=array();
      $fillable_array=array();
      $ingredient_array_new=array();
      $fillable_array_new=array();
      foreach ($ingredient_tags as $valueit) {
       $ingredient_array[$valueit['id']]=$valueit['tag'];
       $ingredient_array_new[$valueit['id']]=$valueit['tag'];
      }
      foreach ($fillable_tags as $valueft) {
       $fillable_array[$valueft['id']]=$valueft['tag'];
       $fillable_array_new[$valueft['id']]=$valueft['tag'];
      }
      
      $ftag=explode(',',Request::input('filling_tags'));
      $itag=explode(',',Request::input('ingredient_tags'));

      foreach ($ftag as $keyftag=>$svalue) {
       $key = array_search($svalue, $fillable_array,true);
        if($key!=""){
          unset($fillable_array[$key]);
          unset($ftag[$key]);
        }

      }
      foreach ($itag as $keyitag=>$ivalue) {
        $keyi = array_search($ivalue, $ingredient_array,true);
        if($keyi!=""){
          unset($ingredient_array[$keyi]);
          unset($itag[$keyi]);
        }
      }
      

      foreach ($ingredient_array_new as $key => $value) {
       $keyix = array_search($value,$itag,true);
       
        if($keyix!=""){
          
          unset($itag[$keyix]);
          
        }
        if($keyix==0){
          
          unset($itag[$keyix]);
          
        }
      }
      foreach ($fillable_array_new as $key => $value) {
        $keyix = array_search($value,$ftag,true);
        
        if($keyix!=""){
          
          unset($ftag[$keyix]);
          
        }
        if($keyix==0){
          
          unset($ftag[$keyix]);
          
        }
      }
      
      foreach ($fillable_array as $filling) {
        Tag::where('product_id', '=', $id)->where('type', '=', '1')->where('tag','=',trim($filling))->delete();
        Searchtag::where('product_id', '=', $id)->where('type','=',"fill_tags")->where('name','=',trim($filling))->delete();
      }
      foreach ($ingredient_array as $ingredient) {
        Tag::where('product_id', '=', $id)->where('type', '=', '2')->where('tag','=',trim($ingredient))->delete();
        Searchtag::where('product_id', '=', $id)->where('type','=',"ing_tags")->where('name','=',trim($ingredient))->delete();
      }
      foreach ($itag as $ingredient_tag) {
        $arring = array('product_id'=>$id,'type'=>"2",'tag'=>trim($ingredient_tag));
          Tag::create($arring);
        $arringsc = array('product_id'=>$id,'type'=>"ing_tags",'name'=>trim($ingredient_tag));
          Searchtag::create($arringsc);
      }
      foreach ($ftag as $filling_tag) {
        $arrfill = array('product_id'=>$id,'type'=>"1",'tag'=>trim($filling_tag));
          Tag::create($arrfill);
        $arrfillsc= array('product_id'=>$id,'type'=>"fill_tags",'name'=>trim($filling_tag));
          Searchtag::create($arrfillsc);
      }




      

      if(Input::hasFile('image1')){
        $destinationPath = 'uploads/product/';   // upload path
        $thumb_path = 'uploads/product/thumb/';
        $medium = 'uploads/product/medium/';
        $home_thumb_path = 'uploads/product/home_thumb/';
        $extension = Input::file('image1')->getClientOriginalExtension(); // getting image extension
        $fileName1 = rand(111111111,999999999).'.'.$extension; // renameing image
        Input::file('image1')->move($destinationPath, $fileName1); // uploading file to given path

        $this->obj ->createThumbnail($fileName1,771,517,$destinationPath,$thumb_path);
        $this->obj->createThumbnail($fileName1,109,89,$destinationPath,$medium);
        $this->obj->createThumbnail($fileName1,380,270,$destinationPath,$home_thumb_path);

      }
      else{
        $fileName1 = Request::input('hidden_image1');
      }

      if(Input::hasFile('image2')){
        $destinationPath = 'uploads/product/';   // upload path
        $thumb_path = 'uploads/product/thumb/';
        $medium = 'uploads/product/medium/';
        $home_thumb_path = 'uploads/product/home_thumb/';
        $extension = Input::file('image2')->getClientOriginalExtension(); // getting image extension
        $fileName2 = rand(111111111,999999999).'.'.$extension; // renameing image
        Input::file('image2')->move($destinationPath, $fileName2); // uploading file to given path

        $this->obj->createThumbnail($fileName2,771,517,$destinationPath,$thumb_path);
        $this->obj->createThumbnail($fileName2,109,89,$destinationPath,$medium);
        $this->obj->createThumbnail($fileName2,380,270,$destinationPath,$home_thumb_path);
        
      }
      else{
        $fileName2 = Request::input('hidden_image2');
      }

      if(Input::hasFile('image3')){
        $destinationPath = 'uploads/product/';   // upload path
        $thumb_path = 'uploads/product/thumb/';
        $medium = 'uploads/product/medium/';
        $home_thumb_path = 'uploads/product/home_thumb/';
        $extension = Input::file('image3')->getClientOriginalExtension(); // getting image extension
        $fileName3 = rand(111111111,999999999).'.'.$extension; // renameing image
        Input::file('image3')->move($destinationPath, $fileName3); // uploading file to given path

        $this->obj->createThumbnail($fileName3,771,517,$destinationPath,$thumb_path);
        $this->obj->createThumbnail($fileName3,109,89,$destinationPath,$medium);
        $this->obj->createThumbnail($fileName3,380,270,$destinationPath,$home_thumb_path);
      }
      else{
        $fileName3 = Request::input('hidden_image3');
      }

      if(Input::hasFile('image4')){
        $destinationPath = 'uploads/product/';   // upload path
        $thumb_path = 'uploads/product/thumb/';
        $medium = 'uploads/product/medium/';
        $home_thumb_path = 'uploads/product/home_thumb/';
        $extension = Input::file('image4')->getClientOriginalExtension(); // getting image extension
        $fileName4 = rand(111111111,999999999).'.'.$extension; // renameing image
        Input::file('image4')->move($destinationPath, $fileName4); // uploading file to given path

        $this->obj->createThumbnail($fileName4,771,517,$destinationPath,$thumb_path);
        $this->obj->createThumbnail($fileName4,109,89,$destinationPath,$medium);
        $this->obj->createThumbnail($fileName4,380,270,$destinationPath,$home_thumb_path);
      
      }
      else{
        $fileName4 = Request::input('hidden_image4');
      }
      if(Input::hasFile('image5')){
        $destinationPath = 'uploads/product/';   // upload path
        $thumb_path = 'uploads/product/thumb/';
        $medium = 'uploads/product/medium/';
        $home_thumb_path = 'uploads/product/home_thumb/';
        $extension = Input::file('image5')->getClientOriginalExtension(); // getting image extension
        $fileName5 = rand(111111111,999999999).'.'.$extension; // renameing image
        Input::file('image5')->move($destinationPath, $fileName5); // uploading file to given path

        $this->obj->createThumbnail($fileName5,771,517,$destinationPath,$thumb_path);
        $this->obj->createThumbnail($fileName5,109,89,$destinationPath,$medium);
        $this->obj->createThumbnail($fileName5,380,270,$destinationPath,$home_thumb_path);

      }
      else{
        $fileName5 = (Request::input('hidden_image5')!='')?Request::input('hidden_image5'):'';
      }
      
      if(Input::hasFile('image6')){
        $destinationPath = 'uploads/product/';   // upload path
        $thumb_path = 'uploads/product/thumb/';
        $medium = 'uploads/product/medium/';
        $home_thumb_path = 'uploads/product/home_thumb/';
        $extension = Input::file('image6')->getClientOriginalExtension(); // getting image extension
        $fileName6 = rand(111111111,999999999).'.'.$extension; // renameing image
        Input::file('image6')->move($destinationPath, $fileName6); // uploading file to given path

        $this->obj->createThumbnail($fileName6,771,517,$destinationPath,$thumb_path);
        $this->obj->createThumbnail($fileName6,109,89,$destinationPath,$medium);
        $this->obj->createThumbnail($fileName6,380,270,$destinationPath,$home_thumb_path);

      }
      else{
        $fileName6 = Request::input('hidden_image6');
      }

      if(Input::hasFile('label')){
        $destinationPath = 'uploads/product/';   // upload path
        $thumb_path = 'uploads/product/thumb/';
        $medium = 'uploads/product/medium/';
        $extension = Input::file('label')->getClientOriginalExtension(); // getting image extension
        $label = rand(111111111,999999999).'.'.$extension; // renameing image
        Input::file('label')->move($destinationPath, $label); // uploading file to given path

        $this->obj->createThumbnail($label,600,650,$destinationPath,$thumb_path);
        $this->obj->createThumbnail($label,109,89,$destinationPath,$medium);
      }
      else{
        $label = Request::input('hidden_label');
      }
      $filling_tags=explode(",",Request::input('filling_tags'));
        $ingredient_tags=explode(",",Request::input('ingredient_tags'));
        $tags_new=array_merge($filling_tags,$ingredient_tags);
        $array_to_string="";
        foreach($tags_new as $tagsu){
          if($array_to_string==""){
            $array_to_string=$tagsu;
          }else{
            $array_to_string=$array_to_string.",".$tagsu;
          }

        }
        
      $product = Product::find($id);

      $product['id'] = $id;
      $product['product_name'] = Request::input('product_name');
      $product['product_slug'] = $this->obj->edit_slug($product['product_name'],'products','product_slug',$id);
      $product['image1'] = $fileName1;
      $product['image2'] = $fileName2;
      $product['image3'] = $fileName3;
      $product['image4'] = $fileName4;
      $product['image5'] = $fileName5;
      $product['image6'] = $fileName6;
      $product['label']   = $label;
      $product['description1']      = htmlentities(Request::input('description1'));
      $product['description2']      = htmlentities(Request::input('description2'));
      $product['description3']      = htmlentities(Request::input('description3'));
      
      $product['tags'] = $array_to_string;
      
      $product['visiblity'] = Request::input('visiblity');
      $product['script_generated'] = '<a href="'.url().'/product-details/'.$product['product_slug'].'" style="color: #FFF;background: #78d5e5 none repeat scroll 0% 0%;padding: 10px 20px;font-weight: 400;font-size: 12px;line-height: 25px;text-shadow: none;border: 0px none;text-transform: uppercase;font-weight: 200;vertical-align: middle;box-shadow: none;display: block;float: left;" onMouseOver="this.style.backgroundColor=\'#afc149\'" onMouseOut="this.style.backgroundColor=\'#78d5e5\'">Buy Now</a>';
      $product['created_at'] = date("Y-m-d H:i:s");

      $product->save();

      // ++++++++++++++++++++++++++ Logic for insert brand name and tags in tag table +++++++++++++++++++++++++++++++++++++

      // // Delete Search tags---not needed
      // Searchtag::where('product_id', '=', $id)->delete();

      // $allTags = array(); $ii=0;
      // if($product['tags']!=""){
      //   $allTags = explode(",", $product['tags']);

       
      //   foreach ($allTags as $key => $value) {
      //     $all_data_arr[$ii]['value'] = trim($value);
      //     $all_data_arr[$ii]['type'] = 'tags';
      //     $ii++;
      //   }
      // }

      // // get Brand Name from brand id ---not needed
       $ii = 0;

      $brand_dtls = Brandmember::find($product['brandmember_id']);
      Searchtag::where('product_id', '=', $id)->where('type', '=', 'brand_name')->delete();
       $brand_name = $brand_dtls['business_name'];
       $all_data_arr[$ii]['value'] = $brand_name;
       $all_data_arr[$ii]['type'] = 'brand_name';

      // //Insert Into searchtags table
       foreach ($all_data_arr as $key => $value) {
         $arr = array('product_id'=>$id,'type'=>$value['type'],'name'=>trim($value['value']));
         Searchtag::create($arr);
       }
      


  // ++++++++++++++++++++ Logic for insert brand name and tags in tag table +++++++++++++++++++++++++++++++++++++


      // Delete all ingredient before save new
        ProductIngredientGroup::where('product_id', '=', $id)->delete();   // Delete ingredient group

        ProductIngredient::where('product_id', '=', $id)->delete();     // Delete ingredient individual


       // Create Product Ingredient group 
     $flag = 0;
    if(NULL!=Request::input('ingredient_group')){
      foreach (Request::input('ingredient_group') as $key => $value) {
        
            // Check if that group contain atleast one ingredient
             if(isset($value['ingredient']) && NULL!=$value['ingredient']){
               
                foreach ($value['ingredient'] as $key1 => $next_value) {
                   if($next_value['ingredient_id']!="" && $next_value['weight']!=""){
                      $flag = 1;
                      break;
                   }
                }
              }


            // ========================  Insert If flag==1 =====================
             if($flag==1) {
                  $arr = array('product_id'=>$id,'group_name'=>$value['group_name']);
                  $pro_ing_grp = ProductIngredientGroup::create($arr);
                  $group_id = $pro_ing_grp->id;

                   if(NULL!=$value['ingredient']){

                      foreach ($value['ingredient'] as $key1 => $next_value) {
                        if($next_value['ingredient_id']!="" && $next_value['weight']!=""){

                          $arr_next = array('product_id'=>$id,'ingredient_id'=>$next_value['ingredient_id'],'weight'=>$next_value['weight'],'ingredient_price'=>$next_value['ingredient_price'],'ingredient_group_id'=>$group_id);
                          ProductIngredient::create($arr_next);

                        }
                        
                      }
                   }
                }
            //  ========================  Insert If flag==1 =====================
          }
        }
      // Create Product Ingredient 
	    if(NULL!=Request::input('ingredient')){
        foreach (Request::input('ingredient') as $key2 => $ing_value) {
          if($ing_value['id']!="" && $ing_value['weight']!=""){

              $arr_next = array('product_id'=>$id,'ingredient_id'=>$ing_value['id'],'weight'=>$ing_value['weight'],'ingredient_price'=>$ing_value['ingredient_price'],'ingredient_group_id'=>0);
              ProductIngredient::create($arr_next);
          }
            
        }
      } 

      /*
      // Delete all Formfactor before save new
      ProductFormfactor::where('product_id', '=', $id)->delete();

      // Add Ingredient form factor
      foreach (Request::input('formfactor') as $key3 => $formfactor_value) {
        
        $arr_pro_fac = array('product_id'=>$id,'formfactor_id'=>$formfactor_value['formfactor_id'],'servings'=>$formfactor_value['servings'],'min_price'=>$formfactor_value['min_price'],'recommended_price'=>$formfactor_value['recommended_price'],'actual_price'=>$formfactor_value['actual_price']);
        ProductFormfactor::create($arr_pro_fac);
      }*/


      // Start of Custom Time Frame: Old records not to be deleted... Only add new for factor & durations 

        // Add Ingredient form factor

        //dd(Input::all());
        $product_formfactors = DB::table('product_formfactors')->where('product_id', $id)->get();
        //dd($product_formfactors);

        foreach ($product_formfactors as $product_formfactor)
        {
            $pff_id = $product_formfactor->id;
            ProductFormfactorDuration::where('product_formfactor_id', '=', $pff_id)->delete();
        }
        //exit;


        ProductFormfactor::where('product_id', '=', $id)->delete();

        foreach (Request::input('formfactor') as $key3 => $formfactor_value) {
          
          
          
          $arr_pro_fac = array('product_id'=>$id, 'formfactor_id'=>$formfactor_value['formfactor_id'],'servings'=>$formfactor_value['servings']);
  
          $ffactor_row = ProductFormfactor::create($arr_pro_fac);

            

          // Custom Time Frame
          // Added the multiple durations
          $lastFormfactorInsertedId = $ffactor_row->id;
          //echo $lastDurationInsertedId;
          //exit;
          if(isset($formfactor_value['duration'])) {


              //ProductFormfactorDuration::where('product_formfactor_id', '=', $prod_formfactor_id)->delete();

              $durations = $formfactor_value['duration'];
              $min_prices = $formfactor_value['min_price'];
              $recommended_prices = $formfactor_value['recommended_price'];
              $actual_prices = $formfactor_value['actual_price'];

              

              for($p=0; $p < count($durations); $p++) {

                  if(isset($durations[$p]) && $durations[$p] != "" && isset($min_prices[$p]) && $min_prices[$p] != "" && isset($recommended_prices[$p]) && $recommended_prices[$p] != "" && isset($actual_prices[$p]) && $actual_prices[$p] != "")  
                  {              

                    $arr_pro_fac_dur = array('product_formfactor_id'=>$lastFormfactorInsertedId, 'duration'=>$durations[$p], 'min_price'=> $min_prices[$p], 'recommended_price'=>$recommended_prices[$p], 'actual_price'=>$actual_prices[$p]);
                    
                    $ffactor_dur_row = ProductFormfactorDuration::create($arr_pro_fac_dur);

                    //$p++;

                  }

              }

          }



        }

        // End of Custom Time Frame  


      // Add Ingredient form factor for available form factor
      if(Request::input('excluded_val')!=""){
        $all_form_factor_ids = rtrim(Request::input('excluded_val'),",");
        $all_ids = explode(",", $all_form_factor_ids);

        foreach ($all_ids as $key => $value) {
         
          $arr_pro_factor = array('product_id'=>$id,'formfactor_id'=>$value);
          ProductFormfactor::create($arr_pro_factor);

        }
      }

    

      Session::flash('success', 'Product edit successfully'); 
      return redirect('admin/product-list/'.$product['discountinue']);


      //echo "<pre>";print_r(Request::all());exit;
    }

    public function destroy($id)
    {        
        $pro = Product::find($id);
        $pro['is_deleted'] = 1;
        
        $pro->save();

        Session::flash('success', 'Product deleted successfully'); 
        return redirect('admin/product-list/'.$pro['discountinue']);
    }

    public function change_related_status()
    {   
        $product_id = Request::segment(3);
        $related = Request::segment(4);



        $pro = Product::find($product_id);
        
        $pro['related'] = $related;
        
        $pro->save();

         Session::flash('success', 'Product updated successfully'); 
         return redirect('admin/product-list/'.$pro['discountinue']);
    }

    public function ratings($id){
            $limit = 50;
            $ratings = Ratings::with('getRatings','getMembers')->where('product_id',$id)->orderBy('created_on', 'desc')->paginate($limit);
            $ratings->setPath('');
           return view('admin.product.ratings',compact('ratings'));
    }
        
    public function destroyrating($id)
    {
      $ratings = DB::table('product_rating')->where("rating_id",$id)->first();
      DB::table('product_rating')->where("rating_id",$id)->delete();
      
      Session::flash('success', 'Rating deleted successfully'); 
      return redirect('admin/ratings/'.$ratings->product_id);
    }

    public function ratingstatus($id){
        $ratings=DB::table('product_rating')->where("rating_id",$id)->first();
        
        if($ratings->status=='1'){
           
            DB::table('product_rating')->where('rating_id', $id)->update(['status' =>0]);
        }else{
             DB::table('product_rating')->where('rating_id', $id)->update(['status' =>1]);
        }

        Session::flash('success', 'Rating status updated successfully'); 
        return redirect('admin/ratings/'.$ratings->product_id);
    }


    public function create()
    {

      $ingredients = DB::table('ingredients')->whereNotIn('status',[0,2])->orderBy('name', 'asc')->get();
       
      $formfac = DB::table('form_factors')->get();

     /* $cond = array('role'=>1,'status'=>1,'admin_status'=>1); */

      $cond = array('role'=>1,'status'=>1);

      $all_brands = DB::table('brandmembers')->select('business_name','id')->where($cond)->get();

      //echo "<pre>";print_r($all_brands);exit;

      return view('admin.product.create',compact('ingredients','formfac','all_brands'),array('title'=>'Add product','module_head'=>'Add Product'));
    }


    public function store(Request $request)
    {
        $obj = new helpers();
    
        //echo "<pre>";print_r(Request::all());exit;
        $xx=Request::input();
        //print_r($xx);
        $filling_tags=explode(",",Request::input('filling_tags'));
        $ingredient_tags=explode(",",Request::input('ingredient_tags'));
        $tags_new=array_merge($filling_tags,$ingredient_tags);
        $array_to_string="";
        foreach($tags_new as $tagsu){
          if($array_to_string==""){
            $array_to_string=$tagsu;
          }else{
            $array_to_string=$array_to_string.",".$tagsu;
          }

        }
        $array_to_string;
        //exit; 
        if(Input::hasFile('image1')){
          $destinationPath = 'uploads/product/';   // upload path
          $thumb_path = 'uploads/product/thumb/';
          $home_thumb_path = 'uploads/product/home_thumb/';
          $medium = 'uploads/product/medium/';
          $extension = Input::file('image1')->getClientOriginalExtension(); // getting image extension
          $fileName1 = rand(111111111,999999999).'.'.$extension; // renameing image
          Input::file('image1')->move($destinationPath, $fileName1); // uploading file to given path

          $obj->createThumbnail($fileName1,771,517,$destinationPath,$thumb_path);
          $obj->createThumbnail($fileName1,380,270,$destinationPath,$home_thumb_path);
          $obj->createThumbnail($fileName1,109,89,$destinationPath,$medium);
        }
        else{
          $fileName1 = '';
        }

        if(Input::hasFile('image2')){
          $destinationPath = 'uploads/product/';   // upload path
          $thumb_path = 'uploads/product/thumb/';
          $home_thumb_path = 'uploads/product/home_thumb/';
          $medium = 'uploads/product/medium/';
          $extension = Input::file('image2')->getClientOriginalExtension(); // getting image extension
          $fileName2 = rand(111111111,999999999).'.'.$extension; // renameing image
          Input::file('image2')->move($destinationPath, $fileName2); // uploading file to given path

          $obj->createThumbnail($fileName2,771,517,$destinationPath,$thumb_path);
          $obj->createThumbnail($fileName2,380,270,$destinationPath,$home_thumb_path);
          $obj->createThumbnail($fileName2,109,89,$destinationPath,$medium);
        }
        else{
          $fileName2 = '';
        }

        if(Input::hasFile('image3')){
          $destinationPath = 'uploads/product/';   // upload path
          $thumb_path = 'uploads/product/thumb/';
          $home_thumb_path = 'uploads/product/home_thumb/';
          $medium = 'uploads/product/medium/';
          $extension = Input::file('image3')->getClientOriginalExtension(); // getting image extension
          $fileName3 = rand(111111111,999999999).'.'.$extension; // renameing image
          Input::file('image3')->move($destinationPath, $fileName3); // uploading file to given path

          $obj->createThumbnail($fileName3,771,517,$destinationPath,$thumb_path);
          $obj->createThumbnail($fileName3,380,270,$destinationPath,$home_thumb_path);
          $obj->createThumbnail($fileName3,109,89,$destinationPath,$medium);
        }
        else{
          $fileName3 = '';
        }

        if(Input::hasFile('image4')){
          $destinationPath = 'uploads/product/';   // upload path
          $thumb_path = 'uploads/product/thumb/';
          $home_thumb_path = 'uploads/product/home_thumb/';
          $medium = 'uploads/product/medium/';
          $extension = Input::file('image4')->getClientOriginalExtension(); // getting image extension
          $fileName4 = rand(111111111,999999999).'.'.$extension; // renameing image
          Input::file('image4')->move($destinationPath, $fileName4); // uploading file to given path

          $obj->createThumbnail($fileName4,771,517,$destinationPath,$thumb_path);
          $obj->createThumbnail($fileName4,380,270,$destinationPath,$home_thumb_path);
          $obj->createThumbnail($fileName4,109,89,$destinationPath,$medium);
        }
        else{
          $fileName4 = '';
        }
        if(Input::hasFile('image5')){
          $destinationPath = 'uploads/product/';   // upload path
          $thumb_path = 'uploads/product/thumb/';
          $home_thumb_path = 'uploads/product/home_thumb/';
          $medium = 'uploads/product/medium/';
          $extension = Input::file('image5')->getClientOriginalExtension(); // getting image extension
          $fileName5 = rand(111111111,999999999).'.'.$extension; // renameing image
          Input::file('image5')->move($destinationPath, $fileName5); // uploading file to given path

          $obj->createThumbnail($fileName5,771,517,$destinationPath,$thumb_path);
          $obj->createThumbnail($fileName5,380,270,$destinationPath,$home_thumb_path);
          $obj->createThumbnail($fileName5,109,89,$destinationPath,$medium);
        }
        else{
          $fileName5 = '';
        }
        if(Input::hasFile('image6')){
          $destinationPath = 'uploads/product/';   // upload path
          $thumb_path = 'uploads/product/thumb/';
          $home_thumb_path = 'uploads/product/home_thumb/';
          $medium = 'uploads/product/medium/';
          $extension = Input::file('image6')->getClientOriginalExtension(); // getting image extension
          $fileName6 = rand(111111111,999999999).'.'.$extension; // renameing image
          Input::file('image6')->move($destinationPath, $fileName6); // uploading file to given path

          $obj->createThumbnail($fileName6,771,517,$destinationPath,$thumb_path);
          $obj->createThumbnail($fileName6,380,270,$destinationPath,$home_thumb_path);
          $obj->createThumbnail($fileName6,109,89,$destinationPath,$medium);
        }
        else{
          $fileName6 = '';
        }

         if(Input::hasFile('label')){
            $destinationPath = 'uploads/product/';   // upload path
            $thumb_path = 'uploads/product/thumb/';
            $medium = 'uploads/product/medium/';
            $extension = Input::file('label')->getClientOriginalExtension(); // getting image extension
            $label = rand(111111111,999999999).'.'.$extension; // renameing image
            Input::file('label')->move($destinationPath, $label); // uploading file to given path

            $this->obj->createThumbnail($label,600,650,$destinationPath,$thumb_path);
            $this->obj->createThumbnail($label,109,89,$destinationPath,$medium);
          }
          else{
            $label = Request::input('hidden_label');
          }

        $product['product_name'] = Request::input('product_name');
        $product['own_product'] = Request::input('own_product');
        $product['product_slug'] = $obj->create_slug(Request::input('product_name'),'products','product_slug');
        $product['image1'] = $fileName1;
        $product['image2'] = $fileName2;
        $product['image3'] = $fileName3;
        $product['image4'] = $fileName4;
        $product['image5'] = $fileName5;
        $product['image6'] = $fileName6;
        $product['label']   = $label;
        $product['description1']      = htmlentities(Request::input('description1'));
        $product['description2']      = htmlentities(Request::input('description2'));
        $product['description3']      = htmlentities(Request::input('description3'));
        $product['brandmember_id'] = Request::input('brandmember_id');
        $product['tags'] = $array_to_string;     
        $product['sku'] = $obj->random_string(9);  
        $product['visiblity'] = Request::input('visiblity');  

        $product['script_generated'] = '<a href="'.url().'/product-details/'.$product['product_slug'].'" style="color: #FFF;background: #78d5e5 none repeat scroll 0% 0%;padding: 10px 20px;font-weight: 400;font-size: 12px;line-height: 25px;text-shadow: none;border: 0px none;text-transform: uppercase;font-weight: 200;vertical-align: middle;box-shadow: none;display: block;float: left;" onMouseOver="this.style.backgroundColor=\'#afc149\'" onMouseOut="this.style.backgroundColor=\'#78d5e5\'">Buy Now</a>';
        $product['created_at'] = date("Y-m-d H:i:s");

        // Create Product
        $product_row = Product::create($product); 
        $lastinsertedId = $product_row->id;
      // ++++++++++++++++++++ Logic for insert filling tags and ingredient tags in tags table +++++++++++++++++++++++++++++++++++++
      $filling_tags=explode(",",Request::input('filling_tags'));
      $ingredient_tags=explode(",",Request::input('ingredient_tags'));
      //Insert filling tag Into Tag table
      foreach ($filling_tags as $key => $filling) {
          $arr = array('product_id'=>$lastinsertedId,'type'=>"1",'tag'=>trim($filling));
          Tag::create($arr);
          $arringsc = array('product_id'=>$lastinsertedId,'type'=>"fill_tags",'name'=>trim($filling));
          Searchtag::create($arringsc);
        }
      //Insert ingredient tag Into Tag table
      foreach ($ingredient_tags as $key => $ingredient) {
          $arr = array('product_id'=>$lastinsertedId,'type'=>"2",'tag'=>trim($ingredient));
          Tag::create($arr);
          $arringsc = array('product_id'=>$lastinsertedId,'type'=>"ing_tags",'name'=>trim($ingredient));
          Searchtag::create($arringsc);
        }
        // ++++++++++++++++++++++++++ Logic for insert Product name searchtag table +++++++++++++++++++++++++++++++++++++
        $product_name_stag=Request::input('product_name');
        $arr = array('product_id'=>$lastinsertedId,'type'=>'product_name','name'=>trim($product_name_stag));
          Searchtag::create($arr);

    // ++++++++++++++++++++++++++ Logic for insert brand name and tags in tag table +++++++++++++++++++++++++++++++++++++

        $ii=0;
        $allTags = array();
        // if(!empty($tags_new)){
        //   //$allTags = explode(",", $product['tags']);

        //   foreach ($tags_new as $key => $value) {
        //     $all_data_arr[$ii]['value'] = $value;
        //     $all_data_arr[$ii]['type'] = 'tags';
        //     $ii++;
        //   }
        // }

        // get Brand Name from brand id
        // $ii = $ii + 1;
        $brand_dtls = Brandmember::find(Request::input('brandmember_id'));
        $brand_name = $brand_dtls['business_name'];
        $all_data_arr[$ii]['value'] = $brand_name;
        $all_data_arr[$ii]['type'] = 'brand_name';

        //Insert Into searchtags table
        foreach ($all_data_arr as $key => $value) {
          $arr = array('product_id'=>$lastinsertedId,'type'=>$value['type'],'name'=>trim($value['value']));
          Searchtag::create($arr);
        }



    // ++++++++++++++++++++ Logic for insert brand name and tags in tag table +++++++++++++++++++++++++++++++++++++
        
        // Create Product Ingredient group 
         $flag = 0;
        if(NULL!=Request::input('ingredient_group')){
          foreach (Request::input('ingredient_group') as $key => $value) {
          
          // Check if that group contain atleast one ingredient
             if(isset($value['ingredient']) && NULL!=$value['ingredient']){
               
                foreach ($value['ingredient'] as $key1 => $next_value) {
                   if($next_value['ingredient_id']!="" && $next_value['weight']!=""){
                      $flag = 1;
                      break;
                   }
                }
              }


            // ========================  Insert If flag==1 =====================
             if($flag==1) {
                  $arr = array('product_id'=>$lastinsertedId,'group_name'=>$value['group_name']);
                  $pro_ing_grp = ProductIngredientGroup::create($arr);
                  $group_id = $pro_ing_grp->id;

                   if(NULL!=$value['ingredient']){

                      foreach ($value['ingredient'] as $key1 => $next_value) {
                        if($next_value['ingredient_id']!="" && $next_value['weight']!=""){

                          $arr_next = array('product_id'=>$lastinsertedId,'ingredient_id'=>$next_value['ingredient_id'],'weight'=>$next_value['weight'],'ingredient_price'=>$next_value['ingredient_price'],'ingredient_group_id'=>$group_id);
                          ProductIngredient::create($arr_next);

                        }
                        
                      }
                   }
                }
              //  ========================  Insert If flag==1 =====================
            }
          }

        // Create Product Ingredient 
        foreach (Request::input('ingredient') as $key2 => $ing_value) {
          if($ing_value['id']!="" && $ing_value['weight']!=""){

              $arr_next = array('product_id'=>$lastinsertedId,'ingredient_id'=>$ing_value['id'],'weight'=>$ing_value['weight'],'ingredient_price'=>$ing_value['ingredient_price'],'ingredient_group_id'=>0);
              ProductIngredient::create($arr_next);
          }
            
        }

        // Add Ingredient form factor
        /*foreach (Request::input('formfactor') as $key3 => $formfactor_value) {
          
          $arr_pro_fac = array('product_id'=>$lastinsertedId,'formfactor_id'=>$formfactor_value['formfactor_id'],'servings'=>$formfactor_value['servings'],'min_price'=>$formfactor_value['min_price'],'recommended_price'=>$formfactor_value['recommended_price'],'actual_price'=>$formfactor_value['actual_price']);
          ProductFormfactor::create($arr_pro_fac);
        }*/


      // Custom Time Frame
      // Add Ingredient form factor
      //dd(Request::input('formfactor'));
      foreach (Request::input('formfactor') as $key3 => $formfactor_value) {

        // Start of adding formfactor duration for custom time frames
        // Removed certain columns that are not required


        if(isset($formfactor_value['servings']) && $formfactor_value['servings'] > 0)
        {
          $arr_pro_fac = array('product_id'=>$lastinsertedId,'formfactor_id'=>$formfactor_value['formfactor_id'],'servings'=>$formfactor_value['servings']);
          
          $ffactor_row = ProductFormfactor::create($arr_pro_fac);

          

          // Added the multiple durations
          $lastDurationInsertedId = $ffactor_row->id;
          if(isset($formfactor_value['duration'])) {

              $durations = $formfactor_value['duration'];
              $min_prices = $formfactor_value['min_price'];
              $recommended_prices = $formfactor_value['recommended_price'];
              $actual_prices = $formfactor_value['actual_price'];

              for($p=0; $p < count($durations); $p++) {

                  if(isset($durations[$p]) && $durations[$p] != "" && isset($min_prices[$p]) && $min_prices[$p] != "" && isset($recommended_prices[$p]) && $recommended_prices[$p] != "" && isset($actual_prices[$p]) && $actual_prices[$p] != "")  
                  {              

                    $arr_pro_fac_dur = array('product_formfactor_id'=>$lastDurationInsertedId, 'duration'=>$durations[$p], 'min_price'=> $min_prices[$p], 'recommended_price'=>$recommended_prices[$p], 'actual_price'=>$actual_prices[$p]);
                    
                    $ffactor_dur_row = ProductFormfactorDuration::create($arr_pro_fac_dur);

                    //$p++;

                  }

              }


          }

        }
        

      }
      // End of adding formfactor duration for custom time frames

        // Add Ingredient form factor for available form factor
        if(Request::input('excluded_val')!=""){
          $all_form_factor_ids = rtrim(Request::input('excluded_val'),",");
          $all_ids = explode(",", $all_form_factor_ids);

          foreach ($all_ids as $key => $value) {
           
            $arr_pro_factor = array('product_id'=>$lastinsertedId,'formfactor_id'=>$value);
            ProductFormfactor::create($arr_pro_factor);

          }
        }

        
        //Add count to MemberProfile
      $row = MemberProfile::where('brandmember_id', '=', Request::input('brandmember_id'))->first();
      $row1 = array();
      if(!empty($row)){
        $count = ($row->count)+1;
        
        MemberProfile::where('brandmember_id', '=', Request::input('brandmember_id'))->update(['count' => $count]);
      }
      else{
        $count = 1;
        $row1['count'] = $count;
        $row1['brandmember_id'] = Request::input('brandmember_id');
        MemberProfile::create($row1);
      }
        

        Session::flash('success', 'Product added successfully'); 
        return redirect('admin/product-list/0');
      }
              
  }