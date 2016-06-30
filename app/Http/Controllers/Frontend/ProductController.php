<?php namespace App\Http\Controllers\Frontend; /* path of this controller*/

use App\Model\Brandmember;          /* Model name*/
use App\Model\Product;              /* Model name*/
use App\Model\ProductIngredientGroup;    /* Model name*/
use App\Model\ProductIngredient;      /* Model name*/
use App\Model\ProductFormfactor;      /* Model name*/
use App\Model\Ingredient;             /* Model name*/
use App\Model\FormFactor;             /* Model name*/
use App\Model\Searchtag;             /* Model name*/
use App\Model\MemberProfile;             /* Model name*/
use App\Model\ProductShare;             /* Model name*/
use App\Model\Tag;                          /* Model name new*/
use App\Model\ProductFormfactorDuration;     /* Model name: For custom time frame */
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

class ProductController extends BaseController {

    public function __construct() 
    {

      parent::__construct(); 
    }
   /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {
        //return view('frontend.product.index',compact('body_class'),array('title'=>'MIRAMIX | Home'));
       //return Redirect::to('brandregister')->with('reg_brand_id', 1);
        //return redirect('product-details');
    }

    public function create()
    {
        
        if(!Session::has('brand_userid')){
            return redirect('brandLogin');
        }

        // Check if brand subscription expires show message 
        $brand_details = Brandmember::find(Session::get('brand_userid'));
        if($brand_details->subscription_status!="active"){
        	Session::flash('error', 'Your subscription is over. Subscribe to add more products.'); 
      		return redirect('my-products');
        }

        
        $ingredients = DB::table('ingredients')->whereNotIn('status',[2])->orderBy('name', 'asc')->get();
         
        $formfac = DB::table('form_factors')->get();

        //echo "<pre>";print_r($ingredients);exit;

        return view('frontend.product.create',compact('ingredients','formfac'),array('title'=>'Add product'));
    }

    public function store(Request $request)
    {
      $obj = new helpers();
  
      //echo "<pre>";print_r(Request::all());
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
        // print_r($filling_tags);
        // print_r($ingredient_tags);
        // print_r($array_to_string);
        // exit;

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

      $product['product_name'] = Request::input('product_name');
      $product['own_product'] = Request::input('own_product');
      $product['product_slug'] = $obj->create_slug(Request::input('product_name'),'products','product_slug');
      $product['image1'] = $fileName1;
      $product['image2'] = $fileName2;
      $product['image3'] = $fileName3;
      $product['image4'] = $fileName4;
      $product['image5'] = $fileName5;
      $product['image6'] = $fileName6;
      $product['description1']      = htmlentities(Request::input('description1'));
      $product['description2']      = htmlentities(Request::input('description2'));
      $product['description3']      = htmlentities(Request::input('description3'));
      $product['brandmember_id'] = Session::get('brand_userid');
      //$product['brandmember_id'] = 33;
      $product['tags'] = $array_to_string;     
      $product['sku'] = $obj->random_string(9);  
      //$product['tags'] = 'test';
      $product['script_generated'] = '<a href="'.url().'/product-details/'.$product['product_slug'].'" style="color: #FFF;background: #78d5e5 none repeat scroll 0% 0%;padding: 10px 20px;font-weight: 400;font-size: 12px;line-height: 25px;text-shadow: none;border: 0px none;text-transform: uppercase;font-weight: 200;vertical-align: middle;box-shadow: none;display: block;float: left;" onMouseOver="this.style.backgroundColor=\'#afc149\'" onMouseOut="this.style.backgroundColor=\'#78d5e5\'">Buy Now</a>';
      $product['created_at'] = date("Y-m-d H:i:s");

      /// Create Product
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
      // $allTags = array();
      // if($product['tags']!=""){
      //   $allTags = explode(",", $product['tags']);

      //   foreach ($allTags as $key => $value) {
      //     $all_data_arr[$ii]['value'] = $value;
      //     $all_data_arr[$ii]['type'] = 'tags';
      //     $ii++;
      //   }
      // }

      // // get Brand Name from brand id
      // $ii = $ii + 1;
      $brand_dtls = Brandmember::find(Session::get('brand_userid'));
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
        
        $arr_pro_fac = array('product_id'=>$lastinsertedId,'formfactor_id'=>$formfactor_value['formfactor_id'],'servings'=>$formfactor_value['servings'],'min_price'=>$formfactor_value['min_price'],'recomended_price'=>$formfactor_value['recomended_price'],'actual_price'=>$formfactor_value['actual_price']);
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

                  //isset($min_prices[$p]) && $min_prices[$p] != "" && isset($recommended_prices[$p]) && $recommended_prices[$p] != "" &&

                  if(isset($durations[$p]) && $durations[$p] != "" &&  isset($actual_prices[$p]) && $actual_prices[$p] != "")  
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
	  $row = MemberProfile::where('brandmember_id', '=', Session::get('brand_userid'))->first();
	  $row1 = array();
	  if(!empty($row)){
	  	$count = ($row->count)+1;
	  	
	  	MemberProfile::where('brandmember_id', '=', Session::get('brand_userid'))->update(['count' => $count]);
	  }
	  else{
	  	$count = 1;
	  	$row1['count'] = $count;
	  	$row1['brandmember_id'] = Session::get('brand_userid');
	  	MemberProfile::create($row1);
	  }
	  	

      Session::flash('success', 'Product added successfully'); 
      return redirect('my-products');
    }



    public function getIngDtls()
    {
        $formfacator = array();
        $ingredient_id = Input::get('ingredient_id');
        //$ingredient_id = 36;
        //$ingredients_details = DB::table('ingredients')->where('id','=',$ingredient_id)->first();

        $ingredients_details = DB::table('ingredients as I')
                              ->select(DB::raw('I.price_per_gram,FF.name'))
                              ->Join('ingredient_formfactors as IFF','I.id','=','IFF.ingredient_id')
                              ->Join('form_factors as FF','FF.id','=','IFF.form_factor_id')
                              ->where('I.id','=',$ingredient_id)
                              ->get();
        
        if(!empty($ingredients_details)){

          foreach($ingredients_details as $each_row){
            $formfacator[] = array('formfactor'=>$each_row->name,'price'=>$each_row->price_per_gram);
          }
          //print_r($formfacator);
        }
         echo json_encode($formfacator);
        exit;
    }

    public function getFormFactorPrice()
    {
        $formfacator = array();
        $formfactor_id = Input::get('formfactor_id');
        //$ingredient_id = 36;
        $form_fac = DB::table('form_factors')->where('id','=',$formfactor_id)->first();

        echo $form_fac->price;
        exit;
    }


    public function getFormFactor()
    {
        $ingredient_id = Input::get('ingredient_id');
        $id_tr = Input::get('id_tr');
        $count_formfactor = Input::get('count_formfactor');
        

        $sl = $count_formfactor;
        //$ingredient_id =36;
        $ing_frm_fctr = DB::table('ingredient_formfactors as IFF')
                     ->select(DB::raw('FF.*,IFF.ingredient_id'))
                     ->Join('form_factors as FF', 'IFF.form_factor_id', '=', 'FF.id')
                     ->where('IFF.ingredient_id', '=', $ingredient_id)
                     ->get();
       

        $str = '';
        if(!empty($ing_frm_fctr)){
          //$str .= '<tr class="form_factore_info"><td><select class="form-control">';
          $str .= '<td><select class="form-control factorname ffactor" id="fac_'.$sl.'" name="formfactor[0][formfactor_id]"><option value="">Choose FormFactor</option>';
          foreach($ing_frm_fctr as $each_form_factor){

            $str .= '<option value='.$each_form_factor->id.'>'.$each_form_factor->name.'</option>';                        
          }

  

          $str .= '</select></td><td><input class="form-control upcharge ffactor" id="upc_'.$sl.'" type="text" name="formfactor['.$sl.'][upcharge]" placeholder="Upcharge" readonly></td>
          <td><input class="form-control serv_text ffactor" type="text" id="ser_'.$sl.'" name="formfactor['.$sl.'][servings]" placeholder="Servings" ></td>
          <td>&nbsp;</td>
          <td>
                   
          <div class="optionBox">
            
            <div class="block" id="duration_block_'.$sl.'">
             <div class="Cell"><input type="text" placeholder="Duration" class="duration ffactor" id="dur_'.$sl.'_0" name="formfactor['.$sl.'][duration][]" /></div><div class="Cell"><input type="text" placeholder="Minimum Price" class="min_price ffactor" id="min_'.$sl.'_0" name="formfactor['.$sl.'][min_price][]"  /></div><div class="Cell"><input type="text" placeholder="Recommended Price" class="rec_price ffactor" id="rec_'.$sl.'_0" name="formfactor['.$sl.'][recommended_price][]" /></div><div class="Cell"><input type="text" placeholder="Actual Price" class="actual_price ffactor" id="act_'.$sl.'_0" name="formfactor['.$sl.'][actual_price][]"  /></div><span class="remove" onclick="removeDur('.$sl.', 1)"><i class="fa fa-minus-square-o"></i></span>
            </div>
            <div class="block">
            <span class="addItem" onclick="addDur('.$sl.')"><i class="fa fa-plus-square"></i> <b>Add</b></span>
            </div>
          </div>
          
          </td>';
  
          
          
        }

         
        echo $str;
        exit;
    }


  public function  private_product($sku)
  {
      //$product = Product::find($sku);
      $product = Product::select('product_slug')->where('sku',$sku)->first();

                
      $slug = $product['product_slug'];

      return redirect('product-details/'.$slug);
      //product-details/'.$product['product_slug']

  }

	public function allProductByBrand() // Get All Products For Particular user(brand)
	{
    $obj = new helpers();
    if(!$obj->checkBrandLogin())
    {
        return redirect('brandLogin');
    }
    // Check if brand subscription expires show message 
    $subscription_status = 'active';
    $brand_details = Brandmember::find(Session::get('brand_userid'));
    if($brand_details->subscription_status!="active"){
      $subscription_status = 'inactive';
    }

		$limit = 10;
		//echo "hello= " . Session::get('brand_userid'); 

		// Custom Time Frame
    // 29th Jan: Added the logic of custom time frame (product_formfactor_durations)
    $product = DB::table('products')
                 ->select(DB::raw('products.id,products.brandmember_id,products.script_generated,products.product_name,products.product_slug,products.sku, products.visiblity,products.image1, MIN(`product_formfactor_durations`.`actual_price`) as `min_price`, MAX(`product_formfactor_durations`.`actual_price`) as `max_price`'))
                 ->leftJoin('product_formfactors', 'products.id', '=', 'product_formfactors.product_id')
                 ->leftJoin('product_formfactor_durations', 'product_formfactor_durations.product_formfactor_id', '=', 'product_formfactors.id')
                 ->where('products.brandmember_id', '=', Session::get('brand_userid'))
                 ->where('products.active', 1)
                 ->where('product_formfactor_durations.actual_price','!=', 0)
                 ->where('products.is_deleted', 0)
                 ->where('products.discountinue', 0)
                 ->groupBy('product_formfactors.product_id')
                 ->orderBy('min_price', 'asc')
                 ->paginate($limit);
      //DB::enableQueryLog();
      //dd(DB::getQueryLog());
      //echo "<pre>";print_r($product); exit;


    return view('frontend.product.my_product',compact('product','subscription_status'),array('title'=>'MIRAMIX | Brand Listing'));


	 }

    public function edit_product($slug)
    {

       if(!Session::has('brand_userid')){
            return redirect('brandLogin');
        }

 		// Check if brand subscription expires show message 
        $brand_details = Brandmember::find(Session::get('brand_userid'));
        if($brand_details->subscription_status!="active"){
        	Session::flash('error', 'Your subscription is over. Subscribe to edit products.'); 
      		return redirect('my-products');
        }
        
      // Get All Ingredient whose status != 2
      $ingredients = DB::table('ingredients')->whereNotIn('status',[2])->orderBy('name', 'asc')->get();
      
      // Get All Form factors
      $formfac = FormFactor::all();

      // Get Product details regarding to slug
      $products = Product::with('AllIngredientTags','AllFillingTags')->where('product_slug',$slug)->first();

      // Get Ingredient group and their individual ingredients
      $ingredient_group = DB::table('product_ingredient_group')->where('product_id',$products->id)->get();

      $check_arr = $weight_check_arr = $arr = $ing_form_ids = $all_ingredient = $group_ingredient = array(); 
      $total_group_count  = $total_count = $tot_price = $tot_weight = 0;

      if(!empty($ingredient_group)){
        $i = 0;
        foreach($ingredient_group as $each_ing_gr){

          $total_group_weight = 0;
          $group_ingredient[$i]['group_name'] = $each_ing_gr->group_name;

          $ingredient_lists = DB::table('product_ingredients')->select(DB::raw('product_ingredients.*,ingredients.price_per_gram,ingredients.name'))->Join('ingredients','ingredients.id','=','product_ingredients.ingredient_id')->where('ingredient_group_id',$each_ing_gr->id)->orderBy('ingredients.name')->get();
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
      $individual_ingredient_lists = DB::table('product_ingredients')->select(DB::raw('product_ingredients.*,ingredients.price_per_gram,ingredients.name'))->Join('ingredients','ingredients.id','=','product_ingredients.ingredient_id')->where('ingredient_group_id',0)->where('product_id',$products->id)->orderBy('ingredients.name')->get();
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
      $pro_form_factor = DB::table('product_formfactors as pff')
                        ->select(DB::raw('pff.*,ff.name,ff.price,ff.maximum_weight,ff.minimum_weight'))
                        ->Join('form_factors as ff','ff.id','=','pff.formfactor_id')
                        ->where('product_id',$products->id)->get();  // Get All formfactor available for this product

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
     


      // Custom Time Frame
      // Get only those form factor which is created for this particular prouct
      //$pro_form_factor = DB::table('product_formfactors as pff')->select(DB::raw('pff.*,ff.name,ff.price,ff.maximum_weight,ff.minimum_weight'))->Join('form_factors as ff','ff.id','=','pff.formfactor_id')->where('product_id',$products->id)->where('actual_price','!=',0)->get();

      
      // Get only those form factor which is created for this particular prouct      
      $pro_form_factor = DB::table('product_formfactors as pff')
      ->select(DB::raw('pff.*,ff.name,ff.price,ff.maximum_weight,ff.minimum_weight, product_formfactor_durations.*'))
      ->Join('form_factors as ff','ff.id','=','pff.formfactor_id')
      ->Join('product_formfactor_durations', 'product_formfactor_durations.product_formfactor_id', '=', 'pff.id')                 
      ->where('product_id',$products->id)
      ->where('product_formfactor_durations.actual_price','!=',0)
      ->get();      
      

      // echo "<pre>";print_r($check_arr);exit;

      // Check Total COunt
      if($total_count==0)
      	$total_count++;

      
      return view('frontend.product.edit',compact('products','ingredients','all_ingredient','check_arr','tot_weight','tot_price','formfac','pro_form_factor','group_ingredient','individual_ingredient_lists','pro_form_factor_ids','total_count','total_group_count','individual_total_count'),array('title'=>'Edit Product'));
    }
   

    public function productPost(Request $request)
    {
      $obj = new helpers();

      //echo "<pre />";print_r(Request::all());
      //exit;

                $idsx=Request::input('product_id');
                $products_new = Product::where('id',$idsx)->first();

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
              


               $oldpname=$products_new->product_name;
               $newpname=Request::input('product_name');
              if($oldpname!=$newpname){
                Searchtag::where('product_id', '=', $idsx)->where('name','=',trim($oldpname))->where('type','=','product_name')->delete();
                $product_name_stag=Request::input('product_name');
                $arr = array('product_id'=>$idsx,'type'=>'product_name','name'=>trim($product_name_stag));
                Searchtag::create($arr);
                
              }
              //exit;
                $ingredient_tags=Tag::where('product_id', '=', $idsx)->where('type','=',2)->get()->toArray();
                $fillable_tags=Tag::where('product_id', '=', $idsx)->where('type','=',1)->get()->toArray();
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
                  Tag::where('product_id', '=', $idsx)->where('type', '=', '1')->where('tag','=',trim($filling))->delete();
                  Searchtag::where('product_id', '=', $idsx)->where('type','=',"fill_tags")->where('name','=',trim($filling))->delete();
              }
              foreach ($ingredient_array as $ingredient) {
                  Tag::where('product_id', '=', $idsx)->where('type', '=', '2')->where('tag','=',trim($ingredient))->delete();
                  Searchtag::where('product_id', '=', $idsx)->where('type','=',"ing_tags")->where('name','=',trim($ingredient))->delete();
              }
              foreach ($itag as $ingredient_tag) {
                  $arring = array('product_id'=>$idsx,'type'=>"2",'tag'=>trim($ingredient_tag));
                  Tag::create($arring);
                  $arringsc = array('product_id'=>$idsx,'type'=>"ing_tags",'name'=>trim($ingredient_tag));
                  Searchtag::create($arringsc);
              }
              foreach ($ftag as $filling_tag) {
                  $arrfill = array('product_id'=>$idsx,'type'=>"1",'tag'=>trim($filling_tag));
                  Tag::create($arrfill);
                  $arrfillsc= array('product_id'=>$idsx,'type'=>"fill_tags",'name'=>trim($filling_tag));
                  Searchtag::create($arrfillsc);
              }

      

    if(Request::input('own_product')==1)  
    {

          if(Input::hasFile('image1')){
              $destinationPath = 'uploads/product/';   // upload path
              $thumb_path = 'uploads/product/thumb/';
              $home_thumb_path = 'uploads/product/home_thumb/';
              $medium = 'uploads/product/medium/';
              $extension = Input::file('image1')->getClientOriginalExtension(); // getting image extension
              $fileName1 = rand(111111111,999999999).'.'.$extension; // renameing image
              Input::file('image1')->move($destinationPath, $fileName1); // uploading file to given path

              $obj->createThumbnail($fileName1,771,517,$destinationPath,$thumb_path);
              $obj->createThumbnail($fileName1,580,270,$destinationPath,$home_thumb_path);
              $obj->createThumbnail($fileName1,109,89,$destinationPath,$medium);

          }
          else{
            $fileName1 = Request::input('hidden_image1');
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
            $obj->createThumbnail($fileName2,580,270,$destinationPath,$home_thumb_path);
            $obj->createThumbnail($fileName2,109,89,$destinationPath,$medium);

          }
          else{
            $fileName2 = Request::input('hidden_image2');
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
      			$obj->createThumbnail($fileName3,580,270,$destinationPath,$home_thumb_path);
      			$obj->createThumbnail($fileName3,109,89,$destinationPath,$medium);

      		}
      		else{
      			$fileName3 = Request::input('hidden_image3');
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
            $obj->createThumbnail($fileName4,580,270,$destinationPath,$home_thumb_path);
            $obj->createThumbnail($fileName4,109,89,$destinationPath,$medium);

          }
          else{
                $fileName4 = Request::input('hidden_image4');
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
            $obj->createThumbnail($fileName5,580,270,$destinationPath,$home_thumb_path);
            $obj->createThumbnail($fileName5,109,89,$destinationPath,$medium);
          }
          else{
          $fileName5 = (Request::input('hidden_image5')!='')?Request::input('hidden_image5'):'';
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
            $obj->createThumbnail($fileName6,580,270,$destinationPath,$home_thumb_path);
            $obj->createThumbnail($fileName6,109,89,$destinationPath,$medium);

          }
          else{
          $fileName6 = Request::input('hidden_image6');
          }



              $lastinsertedId = $id = Request::input('product_id');
              $product = Product::find(Request::input('product_id'));

              $product['id'] = Request::input('product_id');
              $product['own_product'] = Request::input('own_product');
              $product['product_name'] = Request::input('product_name');
              $product['visiblity'] = Request::input('visiblity');
              $product['product_slug'] = $obj->edit_slug($product['product_name'],'products','product_slug',Request::input('product_id'));
              $product['image1'] = $fileName1;
              $product['image2'] = $fileName2;
              $product['image3'] = $fileName3;
              $product['image4'] = $fileName4;
              $product['image5'] = $fileName5;
              $product['image6'] = $fileName6;
              $product['description1']      = htmlentities(Request::input('description1'));
              $product['description2']      = htmlentities(Request::input('description2'));
              $product['description3']      = htmlentities(Request::input('description3'));

              $product['tags'] = $array_to_string;   

              $product['script_generated'] = '<a href="'.url().'/product-details/'.$product['product_slug'].'" style="color: #FFF;background: #78d5e5 none repeat scroll 0% 0%;padding: 10px 20px;font-weight: 400;font-size: 12px;line-height: 25px;text-shadow: none;border: 0px none;text-transform: uppercase;font-weight: 200;vertical-align: middle;box-shadow: none;display: block;float: left;" onMouseOver="this.style.backgroundColor=\'#afc149\'" onMouseOut="this.style.backgroundColor=\'#78d5e5\'">Buy Now</a>';
              $product['created_at'] = date("Y-m-d H:i:s");

              $product->save();


      		    // ++++++++++++++++++++++++++ Logic for insert brand name and tags in tag table +++++++++++++++++++++++++++++++++++++

      		    // Delete Search tags
      		    // Searchtag::where('product_id', '=', $id)->delete();

          		$allTags = array();
          		$ii=0;
              // if($product['tags']!=""){
              // 			$allTags = explode(",", $product['tags']);
              // 	foreach ($allTags as $key => $value) {
              //   				$all_data_arr[$ii]['value'] = $value;
              //   				$all_data_arr[$ii]['type'] = 'tags';
              //   				$ii++;
              // 			}
              // }

              // get Brand Name from brand id 
              // $ii = $ii + 1;
              $brand_dtls = Brandmember::find($product['brandmember_id']);

              $brand_name = $brand_dtls['fname'].' '.$brand_dtls['lname'];
              $all_data_arr[$ii]['value'] = $brand_name;
              $all_data_arr[$ii]['type'] = 'brand_name';

          //Insert Into searchtags table
              foreach ($all_data_arr as $key => $value) {
                $arr = array('product_id'=>$id,'type'=>$value['type'],'name'=>trim($value['value']));
                Searchtag::create($arr);
              }
          // ++++++++++++++++++++ Logic for insert brand name and tags in tag table +++++++++++++++++++++++++++++++++++++



          // Delete all ingredient before save new
              ProductIngredientGroup::where('product_id', '=', $id)->delete();   // Delete ingredient group

              ProductIngredient::where('product_id', '=', $id)->delete();     // Delete ingredient individual

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
              if(NULL!=Request::input('ingredient')){
                foreach (Request::input('ingredient') as $key2 => $ing_value) {
                  if($ing_value['id']!="" && $ing_value['weight']!=""){

                      $arr_next = array('product_id'=>$lastinsertedId,'ingredient_id'=>$ing_value['id'],'weight'=>$ing_value['weight'],'ingredient_price'=>$ing_value['ingredient_price'],'ingredient_group_id'=>0);
                      ProductIngredient::create($arr_next);
                  }
                    
                }
            	}		

              //echo "<pre>";print_r(Request::input('formfactor') );exit;
              // Delete all Formfactor before save new
              /*ProductFormfactor::where('product_id', '=', $id)->delete();

              // Add Ingredient form factor
              foreach (Request::input('formfactor') as $key3 => $formfactor_value) {
                
                $arr_pro_fac = array('product_id'=>$id,'formfactor_id'=>$formfactor_value['formfactor_id'],'servings'=>$formfactor_value['servings'],'min_price'=>$formfactor_value['min_price'],'recomended_price'=>$formfactor_value['recomended_price'],'actual_price'=>$formfactor_value['actual_price']);
                ProductFormfactor::create($arr_pro_fac);
              }*/


          // Start of Custom Time Frame: Old records not to be deleted... Only add new for factor & durations 

          // Add Ingredient form factor

          $product_formfactors = DB::table('product_formfactors')->where('product_id', $id)->get();
          
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

                /*print_r($durations);
                print_r($min_prices);
                print_r($recommended_prices);
                print_r($actual_prices);
                exit;*/

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

              

    }
    else
    {

          // Update Old Product to discontinue product
          $product_update['id'] = Request::input('product_id');
          $product_update['discountinue'] = 1;

          $pro_result=Product::find($product_update['id'] );
          $pro_result->update($product_update);


          if(Input::hasFile('image1')){
              $destinationPath = 'uploads/product/';   // upload path
              $thumb_path = 'uploads/product/thumb/';
              $home_thumb_path = 'uploads/product/home_thumb/';
              $medium = 'uploads/product/medium/';
              $extension = Input::file('image1')->getClientOriginalExtension(); // getting image extension
              $fileName1 = rand(111111111,999999999).'.'.$extension; // renameing image
              Input::file('image1')->move($destinationPath, $fileName1); // uploading file to given path

              $obj->createThumbnail($fileName1,771,517,$destinationPath,$thumb_path);
              $obj->createThumbnail($fileName1,580,270,$destinationPath,$home_thumb_path);
              $obj->createThumbnail($fileName1,109,89,$destinationPath,$medium);

              // Delete old image
              // @unlink('uploads/product/'.Request::input('hidden_image1'));
              // @unlink('uploads/product/thumb/'.Request::input('hidden_image1'));
              // @unlink('uploads/product/medium/'.Request::input('hidden_image1'));
          }
          else{
            $fileName1 = Request::input('hidden_image1');
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
            $obj->createThumbnail($fileName1,580,270,$destinationPath,$home_thumb_path);
            $obj->createThumbnail($fileName2,109,89,$destinationPath,$medium);

          }
          else{
            $fileName2 = Request::input('hidden_image2');
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
            $obj->createThumbnail($fileName1,580,270,$destinationPath,$home_thumb_path);
            $obj->createThumbnail($fileName3,109,89,$destinationPath,$medium);

          }
          else{
            $fileName3 = Request::input('hidden_image3');
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
            $obj->createThumbnail($fileName1,580,270,$destinationPath,$home_thumb_path);
            $obj->createThumbnail($fileName4,109,89,$destinationPath,$medium);

          }
          else{
            $fileName4 = Request::input('hidden_image4');
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
            $obj->createThumbnail($fileName1,580,270,$destinationPath,$home_thumb_path);
            $obj->createThumbnail($fileName5,109,89,$destinationPath,$medium);


          }
          else{
          $fileName5 = (Request::input('hidden_image5')!='')?Request::input('hidden_image5'):'';
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
            $obj->createThumbnail($fileName1,580,270,$destinationPath,$home_thumb_path);
            $obj->createThumbnail($fileName6,109,89,$destinationPath,$medium);

          }
          else{
            $fileName6 = Request::input('hidden_image6');
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
            $array_to_string;
          $product['product_name'] = Request::input('product_name');
          $product['own_product'] = Request::input('own_product');		
          $product['visiblity'] = Request::input('visiblity');
          $product['product_slug'] = $obj->create_slug(Request::input('product_name'),'products','product_slug');
          $product['image1'] = $fileName1;
          $product['image2'] = $fileName2;
          $product['image3'] = $fileName3;
          $product['image4'] = $fileName4;
          $product['image5'] = $fileName5;
          $product['image6'] = $fileName6;
          $product['description1']      = htmlentities(Request::input('description1'));
          $product['description2']      = htmlentities(Request::input('description2'));
          $product['description3']      = htmlentities(Request::input('description3'));
          $product['brandmember_id'] = Session::get('brand_userid');
          //$product['brandmember_id'] = 33; 
          $product['tags'] = $array_to_string;  
          $product['sku'] = $obj->random_string(9);  

          $product['script_generated'] = '<a href="'.url().'/product-details/'.$product['product_slug'].'" style="color: #FFF;background: #78d5e5 none repeat scroll 0% 0%;padding: 10px 20px;font-weight: 400;font-size: 12px;line-height: 25px;text-shadow: none;border: 0px none;text-transform: uppercase;font-weight: 200;vertical-align: middle;box-shadow: none;display: block;float: left;" onMouseOver="this.style.backgroundColor=\'#afc149\'" onMouseOut="this.style.backgroundColor=\'#78d5e5\'">Buy Now</a>';
          $product['created_at'] = date("Y-m-d H:i:s");

          //echo "<pre>";print_r($product);exit;

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

          // if($product['tags']!=""){
          //   $allTags = explode(",", $product['tags']);

          $ii=0;
          //   foreach ($allTags as $key => $value) {
          //     $all_data_arr[$ii]['value'] = $value;
          //     $all_data_arr[$ii]['type'] = 'tags';
          //     $ii++;
          //   }
          // }

          // get Brand Name from brand id
          // $ii = $ii + 1;
          $brand_dtls = Brandmember::find(Session::get('brand_userid'));
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
            
            $arr_pro_fac = array('product_id'=>$lastinsertedId,'formfactor_id'=>$formfactor_value['formfactor_id'],'servings'=>$formfactor_value['servings'],'min_price'=>$formfactor_value['min_price'],'recomended_price'=>$formfactor_value['recomended_price'],'actual_price'=>$formfactor_value['actual_price']);
            ProductFormfactor::create($arr_pro_fac);
          }*/

          // Start of Custom Time Frame: Old records not to be deleted... Only add new for factor & durations 

          //dd(Request::input('formfactor') );
          foreach (Request::input('formfactor') as $key3 => $formfactor_value) {
            
            
            
            $arr_pro_fac = array('product_id'=>$lastinsertedId, 'formfactor_id'=>$formfactor_value['formfactor_id'],'servings'=>$formfactor_value['servings']);
    
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

                /*print_r($durations);
                print_r($min_prices);
                print_r($recommended_prices);
                print_r($actual_prices);
                exit;*/

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

                      $arr_pro_factor = array('product_id'=>$lastinsertedId,'formfactor_id'=>$value);
                      ProductFormfactor::create($arr_pro_factor);

              }
          }

          //Add count to MemberProfile
      	  $row = MemberProfile::where('brandmember_id', '=', Session::get('brand_userid'))->first();
      	  $row1 = array();
          if(!empty($row)){
              $count = ($row->count)+1;

              MemberProfile::where('brandmember_id', '=', Session::get('brand_userid'))->update(['count' => $count]);
          }
          else{
            $count = 1;
            $row1['count'] = $count;
            $row1['brandmember_id'] = Session::get('brand_userid');
            MemberProfile::create($row1);
          }
          $idsx=Request::input('product_id');
          $products_new = Product::where('id',$idsx)->first();
          
    }
      //exit;
	   Session::flash('success', 'Product updated successfully'); 
	   return redirect('my-products');

    //exit;
    }


    public function delete_product($id){


      // Check if brand subscription expires show message 
      $brand_details = Brandmember::find(Session::get('brand_userid'));
      if($brand_details->subscription_status!="active"){
          Session::flash('error', 'Your subscription is over. Subscribe to delete products.'); 
          return redirect('my-products');
      }

      $product_update['id'] = $id;
      $product_update['discountinue'] = 1;

      $pro_result=Product::find($product_update['id'] );
      $pro_result->update($product_update);

      Session::flash('success', 'Product deleted successfully'); 
      return redirect('my-products');

    }
    public function show(){
        return redirect('my-products');
    }
  
  /*public function saveShare()
  {
    if(Session::has('share_product_id'))
    {
      $new_array = Session::get('share_product_id');
      print_r($new_array); 
      $new = array_merge($new_array,array(Input::get('product_id')));
      Session::put('share_product_id',$new); 
      print_r(Session::get('share_product_id')); 
    }
    else
    {
      $product_array[] = Input::get('product_id');
      Session::put('share_product_id',$product_array);
      print_r(Session::get('share_product_id'));
    }

    // If Social Share from Cart Page and Checkout Page.
    if(Input::get('product_id') == 'social_share')
    {
      Session::put('force_social_share','social_share'); 
    }
    //echo 1; exit;
  }*/

  public function saveShare()
  {

    if(Session::has('share_product_id')) {
      $new_array = Session::get('share_product_id');
     
      $new = array_merge($new_array,array(Input::get('product_id')));
      Session::put('share_product_id',$new); 
     
    } else {
      $product_array[] = Input::get('product_id');
      Session::put('share_product_id',$product_array);
     
    }

    // If Social Share from Cart Page and Checkout Page.
    if(Input::get('product_id') == 'social_share') {
      Session::put('force_social_share','social_share'); 
    }

    $email = Input::get('email');
    $product_id = Input::get('product_id');

    if(Session::has('member_userid') && (!Session::has('brand_userid'))) { // only for member login
      $product_share['user_email'] = Session::get('member_user_email');
    }
    elseif((!Session::has('member_userid')) && (!Session::has('brand_userid'))) { // without any login
      $product_share['user_email'] = $email;
    }
    
    $product_share['product_id'] = $product_id;
    $product_share['created_at'] = date('Y-m-d h:i:s');

    $hasemail = DB::table('product_shares')->where('user_email','=',$product_share['user_email'])->where('product_id','=',$product_id)->count();
    if($hasemail<1)
    {


      if($email !='')
      {
        // insert
        ProductShare::create($product_share);
        $res = 1;
      }
      else
      {
        if(Session::has('member_userid'))
        {
          // insert 
          ProductShare::create($product_share);
        }
        else
        {
          //No Action
        }
      }
    } // hasmail if end

  }
              
}