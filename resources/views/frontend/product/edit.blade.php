@extends('frontend/layout/frontend_template')
@section('content')

<?php //echo "<pre>";print_r($total_count);exit; ?>
<link href="<?php echo url();?>/public/frontend/css/stacktable.css" rel="stylesheet">
<!-- Custom Time Frame -->
<link href="<?php echo url();?>/public/frontend/css/divtable.css" rel="stylesheet">

<div class="inner_page_container nomar_bottom add_prod_new">  

<div id="nav-icon2">
  <span></span>
  <span></span>
  <span></span>
  <span></span>
  <span></span>
  <span></span>
</div>
<div class="mob_topmenu_back"></div>
       
<!-- Start Add Products panel -->
<div class="top_menu_port">
    	@include('frontend/includes/left_menu')
</div>
     
  {!! Form::open(['url' => 'productPost','method'=>'POST', 'files'=>true, 'id'=>'product_form']) !!}
    <input class="form-control" type="hidden" value="<?php echo $products->id;?>" name="product_id">
    <div class="container"><div class="row"><div class="add_product_panel">
         <div class="prod_panel">
         <h2 class="text-center">Edit Your Product</h2>
              
            <div class="product_name">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="col-sm-6 text-right"><label>Product Name </label></div>
                        <div class="col-sm-6"><input type="text" name="product_name" id="product_name" maxlength="10000" value="{!! $products->product_name!!}"></div>
                    </div>
                    <div class="col-sm-6">
                    	<div class="check_box_tab green_color marg_left pull-left">                            
                        <input type="radio" <?php if($products->own_product==0){?> checked="checked" <?php } ?> value="0" name="own_product" id="radio-4" class="regular-checkbox">
                        <label for="radio-4">Miramix Product</label>
                      </div>
                      <div class="check_box_tab green_color marg_left pull-left">                            
                          <input type="radio" <?php if($products->own_product==1){?> checked="checked" <?php } ?> value="1" name="own_product" id="radio-5" class="regular-checkbox">
                          <label for="radio-5">Non Miramix product</label>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="check_box_tab green_color marg_left pull-left">                            
                          <input type="radio" <?php if($products->visiblity==0){?> checked="checked" <?php } ?> value="0" name="visiblity" id="radio-99" class="regular-checkbox">
                          <label for="radio-99">Public</label>
                      </div>
                      <div class="check_box_tab green_color marg_left pull-left">                            
                        <input type="radio" <?php if($products->visiblity==1){?> checked="checked" <?php } ?> value="1" name="visiblity" id="radio-98" class="regular-checkbox">
                        <label for="radio-98">Private</label>
                      </div>                        
                    </div>
                </div>
            </div> 
            <div class="form_ingredient_panel">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-sm-6 right_border">
                  	 <div class="total_ingre">
                     <?php 
						            $conti_incr =1;
                        if(!empty($group_ingredient)){
                          $gr = 0;
                          foreach($group_ingredient as $each_gr_ing){
                        ?>
                     
                        <div class="form_ingredient_group_panel tt35 pull-left">
                          <div class="form_ingredient_group">
                            <div class="top_panel">
                              <table width="95%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                  <tr>
                                    <td width="5%"><a href="javascript:void(0);" class="btn collapsing_btn" data-toggle="collapse" data-target="#toggleDemo<?php echo $gr;?>"><i class="fa fa-plus-square"></i></a></td>
                                    <td align="right" width="30%"><label>Ingredient Group</label></td>
                                    <td width="20%"><input class="form-control grp_name_text" type="text" value="<?php echo $each_gr_ing['group_name'];?>" name="ingredient_group[<?php echo $gr;?>][group_name]"></td>
                                    <td align="right" width="20%"><label>Weight</label></td>
                                    <td width="20%"><input class="form-control back_bg" type="text" value="<?php echo $each_gr_ing['tot_weight'];?>" name="ingredient_group[<?php echo $gr;?>][group_weight]" readonly></td>
                                    <td width="5%"><a href="javascript:void(0);" class="btn del_btn"><i class="fa fa-trash"></i></a></td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                            <div class="collapse_pan collapse in" id="toggleDemo<?php echo $gr;?>">
                              <div class="bottom_panel">
                                <table class="ingredient_new_form" align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tbody>
                                    <tr>
                                      <td width="35%"><label>Ingredients</label></td>
                                      <td width="25%"><label>Weight <small>(gm)</small></label></td>
                                      <td width="17%"><label>Price / <small>gm</small></label></td>
                                      <td width="18%"><label>Total</label></td>
                                      <td align="left" valign="middle">&nbsp;</td>
                                    </tr>
                                    <?php 
                                        if(!empty($each_gr_ing['all_group_ing'])){
                                          $gr_ing = 0;
                                          foreach($each_gr_ing['all_group_ing'] as $each_grp_ingredient){  
                                     ?>
                                      <tr class="ingredient_new_form_info">
                                        <td>
                                          <select class="form-control selectclass" id="select_<?php echo $conti_incr;?>" name="ingredient_group[<?php echo $gr;?>][ingredient][<?php echo $gr_ing;?>][ingredient_id]">
                                            <option value="">Choose Ingredient</option>
                                            <?php foreach($ingredients as $each_ingredient){?>
                                              <option value="<?php echo $each_ingredient->id;?>" <?php if($each_grp_ingredient['ingredient_id']==$each_ingredient->id){?> selected="selected" <?php }?> ><?php echo $each_ingredient->name;?></option> 
                                            <?php } ?>
                                          </select>
                                        </td>
                                        <td><input class="form-control weightclass" type="text" Placeholder="Weight" value="<?php echo $each_grp_ingredient['weight']?>" name="ingredient_group[<?php echo $gr;?>][ingredient][<?php echo $gr_ing;?>][weight]"></td>
                                        <td><input class="form-control get_val" value="<?php echo $each_grp_ingredient['price_per_gram']?>" name="ingredient_group[<?php echo $gr;?>][ingredient][<?php echo $gr_ing;?>][price_per_gm]" placeholder="Price/Gm" type="text" readonly></td>
                                        <td><input class="form-control tot_val" placeholder="Total" value="<?php echo $each_grp_ingredient['ingredient_price']?>" name="ingredient_group[<?php echo $gr;?>][ingredient][<?php echo $gr_ing;?>][ingredient_price]" readonly type="text"></td>
                                        <td align="left" valign="middle" width="10%"><a href="javascript:void(0);" class="remove_row"><i class="fa fa-minus-square-o"></i></a></td>
                                      </tr>
                                    <?php
                                          $gr_ing++; $conti_incr++;
                                          }
                                        } 
                                    ?>
                                  </tbody>
                                </table>
                                <a href="javascript:void(0);" class="add_row pull-left"><i class="fa fa-plus-square"></i> Add Another Ingredient</a>
                              </div>
                            </div>
                          </div>
                        </div>
                       <?php
                          $gr++;  
                          }
                        }
                        ?>
                       
                      </div>
                  	<div class="ingredient_form_panel">
                      <table class="ingredient_form" width="100%" border="0" cellspacing="0" cellpadding="0">
                       <thead>
                                <tr>
                                <th width="40%">Ingredient</th>
                                <th width="20%">Weight(gm)</th>
                                <th width="20%">Price/gm</th>
                                <th width="15%">Total</th>
                                <th align="left" valign="middle">&nbsp;</th>
                                </tr>
                       </thead>
                       <?php 
                        $j=0;
                        //dd(count($individual_ingredient_lists));
                         if(empty($individual_ingredient_lists)){
                       ?>
                       <tr>
                          <td width="40%">
                            <select class="form-control selectclass loop_cls" name="ingredient[0][id]" id="select_<?php echo $conti_incr;?>">
                            <option value="">Choose Ingredient</option>
                              <?php foreach($ingredients as $each_ingredient){?>
                              <option value="<?php echo $each_ingredient->id;?>"><?php echo $each_ingredient->name;?></option>
                              <?php } ?>
                            </select>
                          </td>
                          <td width="20%"><input class="form-control weightclass" type="text" value="" name="ingredient[0][weight]" placeholder="Weight(gm)" ></td>
                          <td width="20%"><input class="form-control get_val" type="text" value="" name="ingredient0][ingredient_price_gm]" placeholder="Price/gm" id="ingredient_price_gm" readonly></td>
                          <td width="15%"><input class="form-control tot_val" type="text" value="" name="ingredient[0][ingredient_price]" readonly placeholder="Total" ></td>
                          
                          <td align="left" valign="middle">&nbsp;</td>
                        </tr>
                       <?php 
                        $j++;
                        }
                        else
                        {
                            
                            foreach($individual_ingredient_lists as $each_individual_ingredient){  
                       ?>
                       <tr>
                          <td width="40%">
                            <select class="form-control selectclass <?php if($j==0) echo "loop_cls"; ?>" name="ingredient[<?php echo $j?>][id]" id="select_<?php echo $conti_incr;?>">
                            <option value="">Choose Ingredient</option>
                              <?php foreach($ingredients as $each_ingredient){?>
                              <option value="<?php echo $each_ingredient->id;?>" <?php if($each_ingredient->id==$each_individual_ingredient->ingredient_id){?> selected="selected" <?php } ?>><?php echo $each_ingredient->name;?></option>
                              <?php } ?>
                            </select>
                          </td>
                          <td width="20%"><input class="form-control weightclass" type="text" value="<?php echo $each_individual_ingredient->weight;?>" name="ingredient[<?php echo $j?>][weight]" placeholder="Weight(gm)" ></td>
                          <td width="20%"><input class="form-control get_val" type="text" value="<?php echo $each_individual_ingredient->price_per_gram;?>" name="ingredient[<?php echo $j?>][ingredient_price_gm]" placeholder="Price/gm" id="ingredient_price_gm" readonly></td>
                          <td width="15%"><input class="form-control tot_val" type="text" value="<?php echo $each_individual_ingredient->ingredient_price;?>" name="ingredient[<?php echo $j?>][ingredient_price]" readonly placeholder="Total" ></td>                            
                          <td align="left" valign="middle"><?php if($j==0) {?>&nbsp; <?php } else {?><a href="javascript:void(0);" class="remove_row"><i class="fa fa-minus-square-o"></i></a> <?php } ?></td>
                            
                            
                        </tr>
                        <?php
								              $j++;$conti_incr++;
                              }
                            }
                        ?>  
                      	
                      </table>
                    </div>                            
                  	<div class="button_panel">
                      	<a href="javascript:void(0);" class="click_more pull-right"><i class="fa fa-plus-square"></i> Add Ingredient Group</a>
                        <a href="javascript:void(0);" class="add_ingre pull-left"><i class="fa fa-plus-square"></i> Add Ingredient</a>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="upload_image_panel">
                   	  <div class="image_upload_panel" id="1">
                        	<div class="upload_button_panel">
                          	<p class="upload_image">
                         	  <input class="upload_button files" type="file" name="image1" id="image1" accept="image/*"></p>
                            <div class="selectedFiles">
                              <?php if($products->image1!="" && file_exists('uploads/product/'.$products->image1)) {?>
                                <img src="<?php echo url();?>/uploads/product/<?php echo $products->image1;?>" alt="" width="125" />
                                <input type="hidden" name="hidden_image1" id="hidden_image1" value="<?php echo $products->image1;?>">
                              <?php } else { ?>
                                <img src="<?php echo url();?>/public/frontend/images/upload-image-btn.png" alt=""/>
                              <?php } ?>
                            </div>
                           </div> 
                           <textarea rows="" cols="" maxlength="10000" name="description1" id="description1">{!! $products->description1;!!}</textarea>
                      </div>
                      <div class="image_upload_panel" id="2">
                        	<div class="upload_button_panel">
                            <p class="upload_image">
                            <input class="upload_button files" type="file" name="image2" id="image2" accept="image/*"></p>
                            <div class="selectedFiles">
                              <?php if($products->image2!="" && file_exists('uploads/product/'.$products->image2)) {?>
                                <img src="<?php echo url();?>/uploads/product/<?php echo $products->image2;?>" alt="" width="125" />
                                <input type="hidden" name="hidden_image2" id="hidden_image2" value="<?php echo $products->image2;?>">
                              <?php } else {?>
                                <img src="<?php echo url();?>/public/frontend/images/upload-image-btn.png" alt=""/>
                              <?php } ?>                            
                            </div>
                          </div> 
                          <textarea rows="" cols="" maxlength="10000" name="description2" id="description2">{!! $products->description2;!!}</textarea>
                      </div>
                      <div class="image_upload_panel" id="3">
                        	<div class="upload_button_panel">
                          	<p class="upload_image">
                         	  <input class="upload_button files" type="file" name="image3" id="image3" accept="image/*"></p>
                            <div class="selectedFiles">
                              <?php if($products->image3!="" && file_exists('uploads/product/'.$products->image3)) {?>
                                <input type="hidden" name="hidden_image3" id="hidden_image3" value="<?php echo $products->image3;?>">
                                <img src="<?php echo url();?>/uploads/product/<?php echo $products->image3;?>" alt="" width="125" />
                              <?php } else {?>
                                <img src="<?php echo url();?>/public/frontend/images/upload-image-btn.png" alt=""/>                            
                              <?php } ?>     
                              </div>
                            </div>
                          <textarea rows="" cols="" maxlength="10000" name="description3" id="description3">{!! $products->description3;!!}</textarea>
                      </div>
                      <div class="upload_button_panel img_modify">
                        	<p class="upload_image">
                       	  <input class="upload_button files" type="file" name="image4" id="image4" accept="image/*"></p>
                          <div class="selectedFiles">
                              <?php if($products->image4!="" && file_exists('uploads/product/'.$products->image4)) {?>
                                <img src="<?php echo url();?>/uploads/product/<?php echo $products->image4;?>" alt="" width="125" />
                                <input type="hidden" name="hidden_image4" id="hidden_image4" value="<?php echo $products->image4;?>">
                              <?php } else {?>
                                <img src="<?php echo url();?>/public/frontend/images/upload-image-btn.png" alt=""/>                            
                              <?php } ?>     
                          </div>
                      </div>                   
                      <div class="upload_button_panel img_modify">
                        	<p class="upload_image">
                       	  <input class="upload_button files" type="file" name="image5" id="image5" accept="image/*"></p>
                          <div class="selectedFiles">
                            <?php if($products->image5!="" && file_exists('uploads/product/'.$products->image5)) {?>
                              <img src="<?php echo url();?>/uploads/product/<?php echo $products->image5;?>" alt="" width="125" />
                              <input type="hidden" name="hidden_image5" id="hidden_image5" value="<?php echo $products->image5;?>">
                           <?php } else {?>
                              <img src="<?php echo url();?>/public/frontend/images/upload-image-btn.png" alt=""/>   
                            <?php } ?>                            
                          </div>
                      </div> 
                      <div class="upload_button_panel img_modify">
                        <p class="upload_image">
                       	<input class="upload_button files" type="file" name="image6" id="image6" accept="image/*"></p>
                        <div class="selectedFiles">
                          <?php if($products->image6!="" && file_exists('uploads/product/'.$products->image6)) {?>
                            <img src="<?php echo url();?>/uploads/product/<?php echo $products->image6;?>" alt="" width="125" />
                             <input type="hidden" name="hidden_image6" id="hidden_image6" value="<?php echo $products->image6;?>">
                          <?php } else {?>
                            <img src="<?php echo url();?>/public/frontend/images/upload-image-btn.png" alt=""/>
                          <?php } ?>                                
                        </div>
                      </div> 
                    </div>
                  </div>
                </div>
              </div>    
            </div>
            <div class="form_ingredient_price_panel">
              <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                          <div class=" total_price">
                            <p class="text-right">Total Ingredient Cost : 
                              <span><strong id="showTotalPrice"><?php echo '$'.$tot_price;?></strong></span>
                              <input type="hidden" id="TotalPrice" name="TotalPrice">
                            </p>
                            <p class="text-right">Total Weight : 
                              <span><strong id="showTotalWeight"><?php echo $tot_weight;?></strong>(gm)</span>
                              <input type="hidden" id="TotalWeight" name="TotalWeight">
                              <input type="hidden" id="TotalIngredients" name="TotalIngredients" value="<?php echo count($individual_ingredient_lists) ?>">
                            </p>
                          </div>
                        </div>
                        
                        <div class="col-sm-12">
                        <div class="table-responsive form_check_table">
                        <div id="load_table" class="black_loader"></div>
                        <table class="table table-bordered">
                        	<thead>
                             	
                              <tr>
                                <th>Ingredient<span class="weight_span">Weight Range</span></th>
                               @if (!empty($formfac))
                                  @foreach ($formfac as $key => $value) 
                                     <th data-rel="{!! $value->id; !!}" data-min-weight="{!! $value->minimum_weight; !!}" data-max-weight="{!! $value->maximum_weight; !!}" <?php if (in_array($value->id, $check_arr)){?> class="all_selec" <?php } ?> >{!! $value->name; !!}<span class="weight_span">{!! $value->minimum_weight; !!}--{!! $value->maximum_weight; !!}(gm)<a href="#" class="toll_tipfor_red" data-toggle="tooltip" title="Not Within Available Weight Range">i</a></span></th>
                                  @endforeach
                                @endif
                                
                              </tr>
                            </thead>
                            
                            <tbody>
                            <?php 
                              if(!empty($all_ingredient)){
								                  $ing_metix = 1;
                                foreach($all_ingredient as $key => $eachformid){
                             ?> 
                                   <tr id="tr_select_<?php echo $ing_metix;?>">
                                      <td><?php echo $eachformid['name'];?></td>
                                       <?php  foreach ($formfac as $key1 => $value1) { ?>
                                        <?php if (in_array($value1->id, $eachformid['factors'])){?>
                                      <td class="<?php echo $value1->id;?> all_selec">
                                        <i class="fa fa-check"></i></td>
                                      </td>
                                        <?php } else { ?>
                                           <td class="<?php echo $value1->id;?>">
                                            <i class="fa fa-times"></i></td>
                                          </td>
                                          <?php } ?>
                                      <?php } ?>
                                    </tr>
                             <?php      
									$ing_metix++;
                                  }
                              }
                            ?>
                            </tbody>
                        </table>
                        </div>
                        </div>
                        
                    </div>
                </div>    
            </div>
            <div class="form_factore_panel">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-sm-12">

                  
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" id="formfactortable">
                          <tbody>
                            <tr>
                              <td width="15%"><label>Form Factor</label></td>
                              <td width="8%"><label>Form Fee</label></td>
                              <td width="15%"><label>Servings per day</label></td>
                              <td width="4%"></td><!-- Remove-->
                              <td width="53%"><label>Duration/Pricing</label></td>
                              <td width="5%"></td>
                              
                            </tr>
                            <?php 
                               
                              if(!empty($pro_form_factor)){
                                $k=0;
                                $temp_ffactor_id = "";
                                foreach ($pro_form_factor as $key => $value) 
                                {

                                  
                                  if($temp_ffactor_id != $value->product_formfactor_id)
                                  {

                                  ?>
                                  <tr class="form_factore_info">                              
                                      <td>

                                        <select class="form-control factorname ffactor" id="fac_<?php echo $k;?>" name="formfactor[<?php echo $k;?>][formfactor_id]">
                                          <option value="">Choose Form-factor</option>
                                          <?php 
                                            foreach($pro_form_factor_ids as $each_id){
                                          ?> 
                                          <option value="<?php echo $each_id['formfactor_id'];?>" <?php if($each_id['formfactor_id']==$value->formfactor_id){?> selected <?php } ?>><?php echo $each_id['name'];?></option>
                                          <?php } ?>
                                        </select>
                                      </td>
                                      

                                      <td><input class="form-control upcharge ffactor" id="upc_<?php echo $k ?>" type="text" name="formfactor[<?php echo $k ?>][upcharge]" placeholder="Upcharge" value="<?php echo $value->price ?>" readonly></td>
                                      <td><input class="form-control serv_text ffactor" type="text" id="ser_<?php echo $k ?>" name="formfactor[<?php echo $k ?>][servings]" placeholder="Servings" value="<?php echo $value->servings ?>" ></td>
                                      <td>
                                        <?php
                                        if($k > 0)
                                        {
                                          ?><a href="javascript:void(0);" class="remove_row_formfactortable"><i class="fa fa-minus-square-o"></i></a><?php
                                        }
                                        ?>
                                      </td>
                                      <td>
                                      
                                      
                                      <div class="optionBox">
                                      <?php
                                      // FOR CUSTOM TIME FRAME
                                      $m = 0;
                                      $s = 0;
                                      $stemp_ffactor_id = 0;
                                      foreach ($pro_form_factor as $key1 => $value1) 
                                      {

                                        if($value->product_formfactor_id == $value1->product_formfactor_id)
                                        {
                                        ?>
                                            <div class="block block_<?php echo $k ?>_<?php echo $s ?>" id="duration_block_<?php echo $k ?>">
                                                <div class="Cell"><input type="text" placeholder="Duration" class="duration ffactor" id="dur_<?php echo $k ?>_<?php echo $s ?>" name="formfactor[<?php echo $k ?>][duration][]" value="<?php echo $value1->duration ?>" /></div><div class="Cell"><input type="text" placeholder="Minimum Price" class="min_price ffactor" id="min_<?php echo $k ?>_<?php echo $s ?>" name="formfactor[<?php echo $k ?>][min_price][]"  value="<?php echo number_format($value1->min_price, 2, '.', '') ?>"  /></div><div class="Cell"><input type="text" placeholder="Recommended Price" class="rec_price ffactor" id="rec_<?php echo $k ?>_<?php echo $s ?>" name="formfactor[<?php echo $k ?>][recommended_price][]"  value="<?php echo number_format($value1->recommended_price, 2, '.', '') ?>" /></div><div class="Cell"><input type="text" placeholder="Actual Price" class="actual_price ffactor" id="act_<?php echo $k ?>_<?php echo $s ?>" name="formfactor[<?php echo $k ?>][actual_price][]" value="<?php echo number_format($value1->actual_price, 2, '.', '') ?>" /></div><span class="remove" onclick="removeDur(<?php echo $k ?>, <?php echo $s ?>)"><i class="fa fa-minus-square-o"></i></span>
                                                <input type="hidden" name="formfactor[<?php echo $k ?>][product_formfactor_id][]" value="<?php echo $value1->product_formfactor_id ?>" />
                                            </div>
                                          
                                        <?php 
                                          $s++;
                                        }
                                        if($m == count($pro_form_factor)-1) {
                                        ?>
                                        <div class="block">
                                          <span class="addItem" onclick="addDur(<?php echo $k ?>)"><i class="fa fa-plus-square"></i> <b>Add</b></span>
                                        </div>
                                        <?php  
                                        }
                                            
                                      $stemp_ffactor_id = $value1->product_formfactor_id;
                                      $m++;


                                    }
                                    ?>
                                    </div></td>
                      

                                    <td align="left" valign="middle" width="10%"></td>                              
                                  </tr>
                                  <?php
                                  $temp_ffactor_id = $value->product_formfactor_id;
                                  $k++;
                                  } // End of if($temp_ffactor_id != $value->id)
                                  

                                } // End of foreach ($pro_form_factor as $key => $value) {
                              } // End of if(!empty($pro_form_factor))
                              ?>
                            
                          </tbody>
                        </table>
                        

                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12"><a href="javascript:void(0);" class="add_form"><i class="fa fa-plus-square"></i> Add Form Factor</a></div>
                </div>
                </div>
              
            </div>
            <div class="submit_panel">
              <div class="container-fluid">
                    <div class="row">
                      <div class="col-sm-12">
                            <!--<a href="<?php echo url();?>/my-products" class="backbtn pull-left"><i class="fa fa-angle-left"></i> Back to Product</a>-->
                              <input type="hidden" id="excluded_val" name="excluded_val">
                            <input type="submit" class="btn" value="Submit Your Product">
                            <div class="product_name edit_prod col-xs-12 pull-right">
                                <div class="row">
                                  <div class="row">
                                    <div class="col-sm-4 text-right">
                                      <label>Feeling Tags</label>
                                    </div>
                                    <div class="col-sm-8">
                                      <input type="text" name="filling_tags" id="filling_tags" value="<?php $ft="";
                                      foreach ($products->AllFillingTags as $key => $value) {
                                          if($ft==""){
                                            $ft=$value->tag;
                                          }
                                          else{
                                            $ft=$ft.",".$value->tag;
                                          }
                                      }
                                      echo $ft;
                                  ?>">
                                    </div>
                                  </div>
                                </div>
                                <div class="row" style="margin-top: 5px;">
                                  <div class="row">
                                    <div class="col-sm-4 text-right">
                                      <label>Ingredient Tags</label>
                                    </div>
                                    <div class="col-sm-8">
                                      <input type="text" name="ingredient_tags" id="ingredient_tags" value="<?php $it="";
                                      foreach ($products->AllIngredientTags as $key => $value) {
                                          if($it==""){
                                            $it=$value->tag;
                                          }
                                          else{
                                            $it=$it.",".$value->tag;
                                          }
                                      }
                                      echo $it;
                                  ?>">
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div></div>
        </div></div></div>
     {!! Form::close() !!}
<!-- End Add Products panel --> 
 </div>
 
<script type="text/javascript">
  var z=0;
  var checkradiostate_var=true;
  var emptyweight_checkfornonmiramix=true;
  var count_formfactor=0;
  var sele_id=[];
  var arr_dupvals=[];
  var flag=false;
  var serv_textflag=true;
  var groupnameflag=true;
  var msg='';
  var mflag = true;
  
  function check_addrow(){
			var total_opt=$("#formfactortable tr:nth-child(2) select.form-control option").size();
			total_opt=parseInt(total_opt)-1;
			//alert(total_opt);
			var count_total=$('#formfactortable tr').length;
			count_total=parseInt(count_total)-1;

      count_formfactor = count_total;
			//alert('count total row:'+count_total);
			if(total_opt==count_total || total_opt==0){
			//alert(total_opt);
			$('.add_form').hide();	
			}
			else{
			$('.add_form').show();	
			}	
		}
		
function selec_option(){
		var opt=[];
		var total_opt='<option value="">Choose Form Factor</option>';
		//alert(checkradiostate_var);
		$('.form_check_table th:gt(0)').each(function(index, element) {
			if(checkradiostate_var==true){
				 if($(this).hasClass('all_selec')){
						//alert($(this).text()); 
					var this_text=$(this).clone().children().remove().end().text();
					var this_val=$(this).attr('data-rel');
						
					total_opt=total_opt+'<option value="'+this_val+'">'+this_text+'</option>'; 
				}
			}
			else{
					var this_text=$(this).clone().children().remove().end().text();
					var this_val=$(this).attr('data-rel');						
					total_opt=total_opt+'<option value="'+this_val+'">'+this_text+'</option>';
			}
        });
			  //alert(total_opt);
        //$("#formfactortable").find("tr:gt(2)").find("tr:gt(1)").remove();
		
		$('#formfactortable tr:nth-child(2) td select.form-control').empty().append(total_opt);
		$('#formfactortable tr:nth-child(2) td .upcharge.form-control,#formfactortable tr:nth-child(2) td .serv_text.form-control,#formfactortable tr:nth-child(2) td .min_price.form-control,#formfactortable tr:nth-child(2) td .actual_price.form-control').val(''); 
		check_addrow();	
		clearall();
}

function total_weight(obj){
var total_pr = total_wg = 0;
var $this=obj;	
var this_vald=$this.val();
if(this_vald==''){
this_vald=0;	
}
else{
this_vald=$this.val();	
}
//alert(this_vald);

var total_value = parseFloat(this_vald) * parseFloat($this.parent().parent().find('.get_val').val());

        //$('.tot_val').val(total_value.toFixed(2));
        $this.parent().parent().find('.tot_val').val(total_value.toFixed(2));

        var numingr = $("#TotalIngredients").val();
        console.log("Total Ingri "+numingr);

        $( ".weightclass" ).each(function( index ) {
  		    var $this=$(this);	
  		    var each_weightval=$( this ).val();
    		  if(each_weightval==''){
    			   each_weightval=0;	
    		  }
    		  else{
    			   each_weightval=$this.val();	
    		  }	
          total_wg += parseFloat(each_weightval);
          console.log( index + ": " + each_weightval );
          console.log( "Num Ingr: " + numingr);
          numingr++
        });

        

        $("#TotalIngredients").val(numingr)

		    total_wg=total_wg.toFixed(2);
		    //alert(total_wg);
        // calculate Total weight 
        $('#showTotalWeight').html(total_wg);
        $('#TotalWeight').val(total_wg);

        // calculate Total  Price
        $( ".tot_val" ).each(function( index ) {
    		  var $this=$(this);
    		  var this_total_val=$this.val();
    		  if(this_total_val==''){
    			 this_total_val=0; 
    		  }
    		  else{
    			 this_total_val=$this.val(); 
    		  }
              total_pr += parseFloat(this_total_val);
              console.log( index + ": " + this_total_val );
        });
		//alert(total_pr);

    

        $('#showTotalPrice').html('$'+total_pr);
        $('#TotalPrice').val(total_pr);
		setTimeout(function(){
		check_addrow();	
		},4000);
}

 // For Ingredient dropdown
  $(document).on('change','select.selectclass',function(){
    count_formfactor=0;
    var $this=$(this);
	var this_selcval = $("option:selected", this).text();
	//var this_selcval_val = $("option:selected", this).val();
	var this_id=$this.attr('id');
	
	
	var prevValue = $(this).data('previous');
	if(prevValue==''){}
	else{
	$('.right_border select.selectclass').not(this).find('option[value="'+prevValue+'"]').show();
	}
	var value = $(this).val();
	//alert('value:'+value);
	$(this).data('previous',value);
	if(value==''){}
	else
	$('.right_border select.selectclass').not(this).find('option[value="'+value+'"]').hide();
	
	//alert(this_selcval);
    if($this.val()!=""){

      $.ajax({
          url: '<?php echo url();?>/getIngDtls',
          method: "POST",
         
          data: { ingredient_id : $this.val()  ,_token: '{!! csrf_token() !!}'},
          success:function(data)
          {
            //console.log(data);return false;
            //jsonval = JSON.parse(data);
            //alert(jsonval);
            var getprice;
			     var html='';
            //if(!empty(data)){
            var json = JSON.parse(data);
           

            $(json).each(function(i,val){
              $.each(val,function(k,v){

                   if(k=='price')    
                    getprice = v;

                  if(k=='formfactor')
                    html=html+v+',';
                    
              });
			  //console.log(html);
			  $this.parent().parent().find('.tooltipcls').attr('data-original-title',html).tooltip('fixTitle');
            });

              $this.parent().parent().find('.get_val').val(getprice);

              //alert($this.parent().parent().find('.weightclass').val());

              if($this.parent().parent().find('.weightclass').val()!=""){

                var total_value = parseFloat($this.parent().parent().find('.weightclass').val()) * parseFloat($this.parent().parent().find('.get_val').val());
                $this.parent().parent().find('.tot_val').val(total_value.toFixed(2))
              }

          //}

           
          }
      });
	  
      getFormFactor($this.val(),this_selcval,this_id); 
    } 
    else{
        $this.parent().parent().find('.get_val').val('');
        $this.parent().parent().find('.weightclass').val('');
        $this.parent().parent().find('.tot_val').val('');
    } 
  });


  function getFormFactor(ingredient_id,ingredient_text,id_tr){

    $.ajax({
          url: '<?php echo url();?>/getFormFactor',
          method: "POST",
          data: { ingredient_id : ingredient_id  ,_token: '{!! csrf_token() !!}',ingredient_text : ingredient_text,id_tr : id_tr, count_formfactor:count_formfactor},
          success:function(data)
          {
            if(data !='' ) 
            {
			$('#load_table').show();
			$('.form_check_table table').css({'opacity':0});
			
			z++;
			var arry_demo=[];	
			  //console.log(data);	
              //$('.form_factore_info').html(data);
			  //$('.form_factore_info').remove();
			  $("#formfactortable").find("tr:gt(0)").remove();
              //$this.parent().parent().find('.get_val').val(data);
			  $('#formfactortable').append('<tr>'+data+'</tr>');
			  
			  if($(".form_check_table table tr#tr_"+id_tr).length){
				  //alert($(".form_check_table table tr#tr_"+id_tr).attr('id'));
				  //$(this).attr('id');
				  $(".form_check_table table tr#tr_"+id_tr).html('<td>'+ingredient_text+'</td><td class="1"><i class="fa fa-times"></i></td><td class="2"><i class="fa fa-times"></i></td><td class="4"><i class="fa fa-times"></i></i></td><td class="5"><i class="fa fa-times"></i></td><td class="6"><i class="fa fa-times"></i></td><td class="10"><i class="fa fa-times"></i></td>')
			  }
			  else{
				  $('.form_check_table table').append('<tr class="formcheck_'+z+'" id="tr_'+id_tr+'"><td>'+ingredient_text+'</td><td class="1"><i class="fa fa-times"></i></td><td class="2"><i class="fa fa-times"></i></td><td class="4"><i class="fa fa-times"></i></i></td><td class="5"><i class="fa fa-times"></i></td><td class="6"><i class="fa fa-times"></i></td><td class="10"><i class="fa fa-times"></i></td></tr>');
			  }
			  
			  
			  
			  $("#formfactortable tr:nth-child(2) select.form-control option").each(function()
				{
					var $this=$(this);
					//alert($(this).val()+$(this).text());
					arry_demo.push($(this).val());
					//temp.push($(this).text());
			  });
			  setTimeout(function(){
				for(var x=1;x<arry_demo.length;x++){
				//alert(".form_check_table table tr.formcheck_"+z+" td."+arry_demo[x]);
				$(".form_check_table table tr#tr_"+id_tr+" td."+arry_demo[x]).html('<i class="fa fa-check"></i>'); 
				$(".form_check_table table tr#tr_"+id_tr+" td."+arry_demo[x]).addClass('all_selec');
				
			  } 
			  
			  },400);
			  
			  	var this_weightclass=$('select#'+id_tr).parent().parent().find('.weightclass');
     
     			total_weight(this_weightclass);
			 	checktable_val(); 
			  
			  
            }
          }
      });

  }
  
  function check_td(y,asd){
	  
	var $this=y;
	var lisdsfd=asd;
					  
	if($this.hasClass('all_selec')){
		asd.push('yes');
	}
	else{
		asd.push('no');	  
	}  
  }


  $(document).on('keyup','.weightclass', function() { 
        this.value = this.value.match(/[0-9]*\.?[0-9]*/);
  });

  // For Ingredient price 
  $(document).on('blur','.weightclass',function(){
    var intRegex = /^-?\d*(\.\d+)?$/;
    var $this = $(this);
	var this_val=$this.val();
    //alert($this.parent().parent().find('.selectclass option:selected').val());
	var this_sel_val=$this.parent().parent().find('.selectclass option:selected').val();
	if(this_sel_val==''){
		$this.parent().parent().find('.selectclass').addClass('red_border');
		$this.parent().parent().parent().parent().parent().find('.error_p').remove();
		$this.parent().parent().parent().parent().parent().append('<p class="error_p">Please Select A value from unselected Select options</p>');
		$this.val('')
	}
	else{
		$this.removeClass('red_border');
		$this.parent().parent().parent().parent().parent().find('.error_p').remove();
		$this.parent().parent().find('.selectclass').removeClass('red_border');
    if(intRegex.test($(this).val()) && $this.parent().parent().find('.get_val').val()!=""){
		//alert();
        //alert($this.parent().parent().find('.get_val').val());
		total_weight($this);
        
      }
	  	var this_id=$this.parent().parent().find('.selectclass').attr('id');
		//alert(this_id);
		checktable_val();
		$('#tr_'+this_id).find('.inputed_weight').html('Total Weight:&nbsp;'+this_val);
	}
	  
  });
var select_opt;
var select_ophtml;
var evryselecval;

function checkandadd_sel(){
select_opt={};
		evryselecval={};
		select_ophtml=''; 
      // Individual ingredient
	  //alert(select_ophtml)
	  
	  $('.right_border .selectclass').each(function(index, element) {
        	var this_val=$('option:selected',this).val();
			var this_text=$('option:selected',this).text();
			//evryselecval.push(this_val);
			//alert(this_val);
			evryselecval[this_val]=this_text;
      });
	  
	  
	  $('.selectclass.loop_cls option').each(function(index, element) {		  	
			//alert(selectec_val);
		    var $this=$(this); 
			var this_opttext=$(this).text();
			var this_optval=$(this).val();
			//alert(this_optval);
			var mileche_ki=false;
			
			for(var key in evryselecval){
				
				if(this_opttext=='' || this_optval==key){
				//alert('key'+sele_val+'value'+sele_text);
				console.log(key); 
				mileche_ki=true
				}
				else{
					console.log(this_opttext);
					
				} 
	  		}			
			if(mileche_ki==false){
				select_opt[this_optval]=this_opttext;
			}			
    	});

    // Updated on 10th Feb 2016: Display Ingredients in sorted order: Ingredient Sorting
    $('#select_1 option').each(function(index, item) {

         //set new select to value of old select
         //$(item).val( $originalSelects.eq(index).val() );
         var ingVal = $(this).text();
         
        
         for(var key in select_opt){
            if(select_opt[key] == ingVal && ingVal !='Choose Ingredient') {
              
                select_ophtml=select_ophtml+'<option value="'+key+'">'+ingVal+'</option>';
              
            }
            

         }

    });

    /*
	
		for(var key in select_opt)
		{
		  //alert(select_opt[key]);
		  if(select_opt[key]=='Choose Ingredient'){
			  
		  }
		  else{
		  select_ophtml=select_ophtml+'<option value="'+key+'">'+select_opt[key]+'</option>';
		  }
		}
		//alert(select_ophtml);
		for(var key in evryselecval){
			//alert(evryselecval[key]);			  
		  select_ophtml=select_ophtml+'<option hidden value="'+key+'">'+evryselecval[key]+'</option>';
		
		}	*/
}

function onlyLoadChecktable(){
  

      var array_flag1=[],
			array_flag2=[],
			array_flag3=[],
			array_flag4=[],
			array_flag5=[],
			array_flag6=[];
			  $('.form_check_table table tr td.1').each(function(index, element) {
				  	var $this=$(this);
              	  	if($this.hasClass('all_selec')){
						array_flag1.push('yes');
					}
					else{
						array_flag1.push('no');	  
					} 			  
              });
			  $('.form_check_table table tr td.2').each(function(index, element) {
				  	var $this=$(this);
					if($this.hasClass('all_selec')){
						array_flag2.push('yes');
					}
					else{
						array_flag2.push('no');	  
					} 
              	  	//check_td($this,array_flag2);			  
              });
			  $('.form_check_table table tr td.4').each(function(index, element) {
				  	var $this=$(this);
					if($this.hasClass('all_selec')){
						array_flag3.push('yes');
					}
					else{
						array_flag3.push('no');	  
					} 
              	  	//check_td($this,array_flag3);			  
              });
			  $('.form_check_table table tr td.5').each(function(index, element) {
				  	var $this=$(this);
					if($this.hasClass('all_selec')){
						array_flag4.push('yes');
					}
					else{
						array_flag4.push('no');	  
					} 
              	  	//check_td($this,array_flag4);			  
              });
			  $('.form_check_table table tr td.6').each(function(index, element) {
				  	var $this=$(this);
					if($this.hasClass('all_selec')){
						array_flag5.push('yes');
					}
					else{
						array_flag5.push('no');	  
					} 
              	  	//check_td($this,array_flag5);			  
              });
			  $('.form_check_table table tr td.10').each(function(index, element) {
				  	var $this=$(this);
					if($this.hasClass('all_selec')){
						array_flag6.push('yes');
					}
					else{
						array_flag6.push('no');	  
					} 
              	  	//check_td($this,array_flag6);			  
              });
			  
			  
			  
			
			  var found1 = array_flag1.indexOf("no");
			  var found2 = array_flag2.indexOf("no");
			  var found3 = array_flag3.indexOf("no");
			  var found4 = array_flag4.indexOf("no");
			  var found5 = array_flag5.indexOf("no");
			  var found6 = array_flag6.indexOf("no");
			  
			  //alert('found1'+found1+'found2'+found2+'found3'+found3+'found4'+found4+'found5'+found5+'found6'+found6);
			  var showTotalWeight=parseFloat($('#TotalWeight').val());
			  //alert(showTotalWeight);
			  var pow_min=parseFloat($('.form_check_table table th:nth-child(2)').attr('data-min-weight'));
			  var pow_max=parseFloat($('.form_check_table table th:nth-child(2)').attr('data-max-weight'));
			  
			  var cap_min=parseFloat($('.form_check_table table th:nth-child(3)').attr('data-min-weight'));
			  var cap_max=parseFloat($('.form_check_table table th:nth-child(3)').attr('data-max-weight'));
			  
			  var gum_min=parseFloat($('.form_check_table table th:nth-child(4)').attr('data-min-weight'));
			  var gum_max=parseFloat($('.form_check_table table th:nth-child(4)').attr('data-max-weight'));
			  
			  var kcup_min=parseFloat($('.form_check_table table th:nth-child(5)').attr('data-min-weight'));
			  var kcup_max=parseFloat($('.form_check_table table th:nth-child(5)').attr('data-max-weight'));
			  
			  var pill_min=parseFloat($('.form_check_table table th:nth-child(6)').attr('data-min-weight'));
			  var pill_max=parseFloat($('.form_check_table table th:nth-child(6)').attr('data-max-weight'));
			  
			  var tea_min=parseFloat($('.form_check_table table th:nth-child(7)').attr('data-min-weight'));
			  var tea_max=parseFloat($('.form_check_table table th:nth-child(7)').attr('data-max-weight'));
			  
			  if(found1==-1 && (showTotalWeight>=pow_min && showTotalWeight<=pow_max)){
				$('.form_check_table table th:nth-child(2)').removeClass('red_selc');  
				$('.form_check_table table th:nth-child(2)').addClass('all_selec');  
			  }
			  else{
				$('.form_check_table table th:nth-child(2)').removeClass('all_selec red_selc');  
				if(found1==-1)
				$('.form_check_table table th:nth-child(2)').addClass('red_selc'); 
				
			  }
			  if(found2==-1 && (showTotalWeight>=cap_min && showTotalWeight<=cap_max)){
				$('.form_check_table table th:nth-child(3)').removeClass('red_selc');   
				$('.form_check_table table th:nth-child(3)').addClass('all_selec');  
			  }
			  else{
				$('.form_check_table table th:nth-child(3)').removeClass('all_selec red_selc'); 
				if(found2==-1)
				$('.form_check_table table th:nth-child(3)').addClass('red_selc');  
			  }
			  if(found3==-1 && (showTotalWeight>=gum_min && showTotalWeight<=gum_max)){
				$('.form_check_table table th:nth-child(4)').removeClass('red_selc');     
				$('.form_check_table table th:nth-child(4)').addClass('all_selec');  
			  }
			  else{
				$('.form_check_table table th:nth-child(4)').removeClass('all_selec red_selc');
				if(found3==-1)
				$('.form_check_table table th:nth-child(4)').addClass('red_selc');  
			  }
			  if(found4==-1 && (showTotalWeight>=kcup_min && showTotalWeight<=kcup_max)){
				$('.form_check_table table th:nth-child(5)').removeClass('red_selc');     
				$('.form_check_table table th:nth-child(5)').addClass('all_selec');  
			  }
			  else{
				$('.form_check_table table th:nth-child(5)').removeClass('all_selec red_selc');
				if(found4==-1)
				$('.form_check_table table th:nth-child(5)').addClass('red_selc');  
			  }
			  if(found5==-1 && (showTotalWeight>=pill_min && showTotalWeight<=pill_max)){
				$('.form_check_table table th:nth-child(6)').removeClass('red_selc');     
				$('.form_check_table table th:nth-child(6)').addClass('all_selec');  
			  }
			  else{
				$('.form_check_table table th:nth-child(6)').removeClass('all_selec red_selc');
				if(found5==-1) 
				$('.form_check_table table th:nth-child(6)').addClass('red_selc'); 
			  }
			  if(found6==-1 && (showTotalWeight>=tea_min && showTotalWeight<=tea_max)){
				$('.form_check_table table th:nth-child(7)').removeClass('red_selc');     
				$('.form_check_table table th:nth-child(7)').addClass('all_selec');  
			  }
			  else{
				$('.form_check_table table th:nth-child(7)').removeClass('all_selec red_selc');
				if(found6==-1)
				$('.form_check_table table th:nth-child(7)').addClass('red_selc');  
			  }
			  
			  $('.form_check_table table').css({'opacity':1});
			  $('#load_table').hide(); 
			  
			  
			  $('.form_check_table .table>thead>tr>th:not(.all_selec) .toll_tipfor_red').tooltip("option", "content", "Does not have all the ingredients");
			  $('.form_check_table .table>thead>tr>th.red_selc .toll_tipfor_red').tooltip("option", "content", "Not Within Available Weight Range");
              

}

function checktable_val(){
		setTimeout(function(){

        onlyLoadChecktable();
			  
			  
			  selec_option();
			  
			  
			  
			  
			  },1000);
		}

   $(document).ready(function(e) {
    //for adding new ingredient
    var x='<?php echo ($individual_total_count);?>'; var z=0;
    var y = '<?php echo ($total_group_count-1);?>';
	var no_count='<?php echo count($pro_form_factor);?>';
	var for_selc='<?php echo ($total_count);?>';
	var selected_val;
	var selected_text;
	
	
	
	var this_val=[];
      $(document).on('click','.add_ingre',function(){
		
        for_selc++;
		checkandadd_sel();
	
        $('.ingredient_form').append('<tr><td width="40%"><select class="form-control selectclass" id="select_'+for_selc+'" name="ingredient['+x+'][id]"><option value="">Choose Ingredient</option>'+select_ophtml+'</select></td><td width="20%"><input class="form-control weightclass" type="text" name="ingredient['+x+'][weight]" placeholder="Weight(gm)" ></td><td width="20%"><input class="get_val form-control" type="text" name="ingredient_price_gm" id="ingredient_price_gm" readonly placeholder="Price/gm" ></td><td width="15%"><input class="form-control tot_val" type="text" readonly name="ingredient['+x+'][ingredient_price]" placeholder="Total" ></td><td align="left" valign="middle"><a href="javascript:void(0);" class="remove_row"><i class="fa fa-minus-square-o"></i></a></td></tr>');
        x++;
      });

      // Individual ingredient
      $(document).on('click','.remove_row',function(){		
        var $this=$(this);
		console.log($this);
		//calc_weight_ingred($this);
		var tot_recieve=calc_weight_ingred($this);
		//alert(tot_recieve);
		$(this).parent().parent().parent().parent().parent().parent().parent().children('.top_panel').find('.back_bg').val(tot_recieve);
		
		$(this).closest('tr').remove();
		var this_sle_id=$(this).parent().parent().find('.selectclass').attr('id');
		$('.form_check_table table tr#tr_'+this_sle_id).remove();
		var this_reqval=$this.parent().parent().find('.selectclass option:selected').val();
		//alert(this_reqval);
		if(this_reqval==''){
		}
		else{
			$('.right_border select.selectclass').each(function(index, element) {
				//var $this=$(this);
				
				//remember ayan
				$('option[value="'+this_reqval+'"]',this).show();
			});
		}
		
		$('.form_check_table table').css({'opacity':0});
		$('#load_table').show();
		
		
		
		total_weight($this);
		
		checktable_val();
		
		
		
		
		
		//check_addrow();
		
      });
	  
	  //remove row for formfactortable
	  $(document).on('click','.remove_row_formfactortable',function(){
		  var $this=$(this);
		  $(this).closest('tr').remove();
		  var removed_val=$this.parent().parent().find('select.form-control option:selected').val();
		  //alert(removed_val);
		  if(removed_val==''){
			  
		  }
		  else{
			$('#formfactortable select.form-control').each(function(index, element) {
               $('option[value="'+removed_val+'"]',this).show();
            });  
		  }
		  check_addrow(); 
	  });

      // Ingredient Group
      $(document).on('click','.click_more',function(){
       y++;
	   for_selc++;
	   
	   checkandadd_sel();
	   
	   
      $('.total_ingre').append('<div class="form_ingredient_group_panel tt35 pull-left"><div class="form_ingredient_group"><div class="top_panel"><table width="95%" align="center" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td width="5%"><a href="javascript:void(0);" class="btn collapsing_btn" data-toggle="collapse" data-target="#toggleDemo'+y+'"><i class="fa fa-plus-square"></i></a></td><td align="right" width="30%"><label>Ingredient Group</label></td><td width="20%"><input class="form-control grp_name_text" type="text" name="ingredient_group['+y+'][group_name]"></td><td align="right" width="20%"><label>Weight</label></td><td width="20%"><input class="form-control back_bg" type="text" name="ingredient_group['+y+'][group_weight]"></td><td width="5%"><a href="javascript:void(0);" class="btn del_btn"><i class="fa fa-trash"></i></a></td></tr></tbody></table></div><div class="collapse_pan collapse in" id="toggleDemo'+y+'"><div class="bottom_panel"><table class="ingredient_new_form" align="center" width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td width="35%"><label>Ingredients</label></td><td width="25%"><label>Weight <small>(gm)</small></label></td><td width="17%"><label>Price / <small>gm</small></label></td><td width="18%"><label>Total</label></td><td align="left" valign="middle">&nbsp;</td></tr><tr class="ingredient_new_form_info"><td><select class="form-control selectclass" id="select_'+for_selc+'" name="ingredient_group['+y+'][ingredient][0][ingredient_id]"><option value="">Choose Ingredient</option>'+select_ophtml+'</select></td><td><input class="form-control weightclass" type="text" Placeholder="Weight" name="ingredient_group['+y+'][ingredient][0][weight]"></td><td><input class="form-control get_val" name="ingredient_group['+y+'][ingredient][0][price_per_gm]" placeholder="Price/Gm" readonly type="text"></td><td><input class="form-control tot_val" placeholder="Total" readonly name="ingredient_group['+y+'][ingredient][0][ingredient_price]" type="text"></td><td align="left" valign="middle" width="10%"><a href="javascript:void(0);" class="remove_row"><i class="fa fa-minus-square-o"></i></a></td></tr></tbody></table><a href="javascript:void(0);" class="add_row pull-left"><i class="fa fa-plus-square"></i> Add Another Ingredient</a></div></div></div></div>');
     
        //console.log('click='+y);
      });

    //for removing group
      $(document).on('click','.del_btn',function(){
        var $this=$(this);
		$this.parent().parent().parent().parent().parent().parent().find('.selectclass').each(function(index, element) {
            var this_selec_p_id=$(this).attr('id');
			//alert($(this).attr('id'));
			var this_removeval=$('select#'+this_selec_p_id+' option:selected').val();
			//alert(this_removeval);			
			$('.right_border .selectclass').each(function(index, element) {
             $('option[value="'+this_removeval+'"]',this).show();   
            });
			
			$('.form_check_table table tr#tr_'+this_selec_p_id).remove();
			 selec_option();
			 clearall();
        });
		
		
		
		
		
		$('.form_check_table table').css({'opacity':0});
		$('#load_table').show();
		checktable_val()		
		
        $this.parents('.form_ingredient_group_panel').remove();
		total_weight($this);
		//$('#formfactortable').remove();
		
		$("#formfactortable").find("tr:gt(1)").remove();
		$('.recom_text').val('');
		
      }); 
	  
	  

    //for adding a ingredient in group
      $(document).on('click','.add_row',function(){
        z++;
		for_selc++;
        var $this=$(this);
		
		checkandadd_sel();
		
		
        $this.parent().find('.ingredient_new_form').append('<tr class="ingredient_new_form_info"><td><select class="form-control selectclass" id="select_'+for_selc+'" name="ingredient_group['+y+'][ingredient]['+z+'][ingredient_id]"><option value="">Choose Ingredient</option>'+select_ophtml+'</select></td><td><input class="form-control weightclass" type="text" Placeholder="Weight" name="ingredient_group['+y+'][ingredient]['+z+'][weight]"></td><td><input class="form-control get_val" name="ingredient_group['+y+'][ingredient]['+z+'][price_per_gm]" readonly placeholder="Price/Gm" type="text"></td><td><input class="form-control tot_val" name="ingredient_group['+y+'][ingredient]['+z+'][ingredient_price]" readonly placeholder="Total" type="text"></td><td align="left" valign="middle" width="10%"><a href="javascript:void(0);" class="remove_row"><i class="fa fa-minus-square-o"></i></a></td></tr>');
        //console.log('sub='+y);
      });
      
	  
	  //for adding new form factor
		$(document).on('click','.add_form',function(){

      // Added for removal of form factor item dynamically
      var formfactorIds=[];
      var m=0;
      console.log("Inside Formfactor Value")
      if($('.factorname').length>0){
        $('.factorname').each(function(index, element) {
        var $this=$(this);
        var sel_optval=$('option:selected',this).val();
        console.log("Formfactor Value :"+sel_optval)
        if(sel_optval!='' && sel_optval!=0){
          //formfactorIds[m] = sel_optval;
          formfactorIds.push(sel_optval.toString());
          m++;
        }
        });
      }

  		
  		
  		var opt=[];
  		var total_opt='<option value="">Choose Form Factor</option>';
  		$('.form_check_table th:gt(0)').each(function(index, element) {
  			if(checkradiostate_var==true){	
  				 if($(this).hasClass('all_selec')){
  						//alert($(this).text()); 
  					var this_text=$(this).clone().children().remove().end().text();
  					var this_val=$(this).attr('data-rel');
  						
  					//total_opt=total_opt+'<option value="'+this_val+'">'+this_text+'</option>'; 

            // Added for removal of form factor item dynamically based on the selection made in the last factors
            var pflag = true;
            for(var k=0; k<formfactorIds.length; k++)
            {
              if(formfactorIds[k] == this_val)
              {
                pflag = false;
                break;
              }
              
            }           
            if(pflag)
              total_opt=total_opt+'<option value="'+this_val+'">'+this_text+'</option>'; 

  				}
  			}
  			else{
  				  var this_text=$(this).clone().children().remove().end().text();
  					var this_val=$(this).attr('data-rel');						
  					//total_opt=total_opt+'<option value="'+this_val+'">'+this_text+'</option>'; 

            // Added for removal of form factor item dynamically based on the selection made in the last factors
            var pflag = true;
            for(var k=0; k<formfactorIds.length; k++)
            {
              if(formfactorIds[k] == this_val)
              {
                pflag = false;
                break;
              }
              
            }           
            if(pflag)
              total_opt=total_opt+'<option value="'+this_val+'">'+this_text+'</option>'; 

  			}
      });
  			  //alert(total_opt);
  		//$('#formfactortable tr:nth-child(2) td select.form-control').empty().append(total_opt);
  		check_addrow();	
      no_count = count_formfactor;
      console.log("Count Formfactor: "+no_count)
  		
  		//$('#formfactortable').append('<tr class="form_factore_info"><td><select class="form-control" name="formfactor['+no_count+'][formfactor_id]">'+total_opt+'</select></td><td><input class="form-control upcharge" type="text" name="formfactor['+no_count+'][upcharge]" placeholder="Upcharge" readonly></td><td><input class="form-control serv_text" type="text" name="formfactor['+no_count+'][servings]" placeholder="Servings" ></td><td><input class="form-control min_price" readonly type="text" name="formfactor['+no_count+'][min_price]" placeholder="Min Price"></td><td><input class="form-control recom_text" type="text" name="formfactor['+no_count+'][recomended_price]" placeholder="Recomended Price" readonly></td><td style="width:14%;"><input class="form-control actual_price" type="text" name="formfactor['+no_count+'][actual_price]" placeholder="Actual price" style="width: 66%;float: left;"><a href="javascript:void(0);" class="remove_row_formfactortable"><i class="fa fa-minus-square-o"></i></a></td></tr>');
      $('#formfactortable').append('<tr class="form_factore_info"><td><select class="form-control factorname ffactor" id="fac_'+no_count+'" name="formfactor['+no_count+'][formfactor_id]">'+total_opt+'</select></td><td><input class="form-control upcharge ffactor" id="upc_'+no_count+'" type="text" name="formfactor['+no_count+'][upcharge]" placeholder="Upcharge" readonly></td><td><input class="form-control serv_text ffactor" id="ser_'+no_count+'" type="text" name="formfactor['+no_count+'][servings]" placeholder="Servings" ></td><td><a href="javascript:void(0);" class="remove_row_formfactortable"><i class="fa fa-minus-square-o"></i></a></td><td><div class="optionBox"><div class="block" id="duration_block_'+no_count+'"><div class="Cell"><input type="text" class="duration ffactor" placeholder="Duration" id="dur_'+no_count+'_0" name="formfactor['+no_count+'][duration][]" /></div><div class="Cell"><input type="text" class="min_price ffactor" placeholder="Minimum Price" id="min_'+no_count+'_0" name="formfactor['+no_count+'][min_price][]"  /></div><div class="Cell"><input type="text" class="rec_price ffactor" placeholder="Recommended Price" id="rec_'+no_count+'_0" name="formfactor['+no_count+'][recommended_price][]" /></div><div class="Cell"><input type="text" class="actual_price ffactor" placeholder="Actual Price" id="act_'+no_count+'_0" name="formfactor['+no_count+'][actual_price][]" /></div><span class="remove" onclick="removeDur('+no_count+', 1)"><i class="fa fa-minus-square-o"></i></span></div><div class="block"><span class="addItem" onclick="addDur('+no_count+')"><i class="fa fa-plus-square"></i> <b>Add</b></span></div></div></td></tr>');
      
  		//no_count++;
  		check_addrow();
  		
		
		});
		


  $(document).on('change','#formfactortable select.form-control',function(){
    var $this = $(this);


    $.ajax({
          url: '<?php echo url();?>/getFormFactorPrice',
          method: "POST",      
          data: { formfactor_id : $("option:selected", this).val()  ,_token: '{!! csrf_token() !!}'},
          success:function(data)
          {
              $this.parent().parent().find('.upcharge').val(data);
			  $this.parent().parent().find('.serv_text').trigger('blur'); 
           
          }
      });


      $this.parent().parent().find('.min_price,.recom_text,.actual_price,.serv_text').val('');

    


  });



	$('[data-toggle="tooltip"]').tooltip({
        placement : 'bottom'
    });
  });
  
  $(function() {
	flag=false;
	var check_duplicate=false;
	var check_weight_empty=false;
	
	
  $("#product_form").validate({
    
        // Specify the validation rules
        rules: {
            product_name: "required",
      			/*description1: "required",
      			description2: "required",
      			description3: "required"*/
        },
        
        // Specify the validation error messages
        messages: {
            product_name: "Please enter Product Name",
			      /*description1: "Please Enter Value",
    			  description2: "Please Enter Value",
    			  description3: "Please Enter Value"*/
        },
        
        submitHandler: function(form, event) {
    		console.log("mflag: "+mflag);
    		if(checkradiostate_var==true){	
    			if(flag==false || priceflag==false || mflag==false || (arr_dupvals.length)!=0 || check_weight_empty==false || groupnameflag==false || serv_textflag==false){
    			//alert(priceflag);
    			$('.alert-danger').remove();
    			if(flag==false){
    				
    				$('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Oops!</strong> Please Choose A form Factor.</div>');
    			}
    			else if(check_weight_empty==false){
    				$('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Oops!</strong> Incomplete Selection</div>');	
    			}
    			else if((arr_dupvals.length)!=0){
    				$('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Oops!</strong> Please Choose Different Form Factors.</div>');
    			}
    			else if(groupnameflag==false){
    						$('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Oops!</strong>Please Enter Group Name</div>');
    			}
    			else if(serv_textflag==false){
    						$('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Oops!</strong>'+msg+'</div>');
    			}
          else if(mflag==false){
                $('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Oops!</strong>Please make sure that all prices are greater than zero.</div>');
          }
    			else{
    				$('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Oops!</strong> Please make sure that actual price is at least equal to minimum price.</div>');	
    			}
    			if($('.red_border').length>0){
    					$('html, body').animate({
    						scrollTop: $('.red_border').first().offset().top-200
    					}, 400);
    			}
    			
    				return false;	
    			}
    			else{
    			   //alert('else:'+flag)	
             form.submit();
    			}
    		}
    		else {
    				//alert(emptyweight_checkfornonmiramix);
    				$('.alert-danger').remove();
    				if(emptyweight_checkfornonmiramix==false || (arr_dupvals.length)!=0 || flag==false || serv_textflag==false || groupnameflag==false){	
    					if(emptyweight_checkfornonmiramix==false){			  
    				      $('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Oops!</strong> Please Select A weight Value.</div>');
    					}
    					else if(flag==false){
    						$('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Oops!</strong> Please Choose A form Factor.</div>');
    					}
    					else if(serv_textflag==false){
    						$('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Oops!</strong>'+msg+'</div>');
    					}
    					else if(groupnameflag==false){
    						$('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Oops!</strong>Please Enter Group Name</div>');
    					}
    					else{
    						$('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Oops!</strong> Please Choose Different Form Factors.</div>');
    					}
    					if($('.red_border').length>0){
    					$('html, body').animate({
    						scrollTop: $('.red_border').first().offset().top-200
    					}, 400);
    					}
    					return false;	
    				}
    				else{				
    					form.submit();	
    				}
    			}
		
        }
    });
	
	$(document).on('click','.add_product_panel .submit_panel input[type="submit"]',function(){
		$('.right_border select.selectclass.red_border, .weightclass.red_border').removeClass('red_border');
		$('.error_p').remove();	
		if(checkradiostate_var==true){
		$('#excluded_val').val('');
		var hid_vald='';
		hid_vald=$('#excluded_val').val();
		var tot_lastopt=[];
		var index_val=0;
		
		sele_id=[];
		allselected_opt();
		
		$('.add_product_panel .form_factore_panel tr:nth-child(2) select.form-control option').each(function(index, element) {
			var $this=$(this); 
			var this_opttext=$(this).text();
			var this_optval=$(this).val();
			var check_bool=false;
			for(var x=0;x<sele_id.length;x++){
				//alert(tot_lastopt[x]);
				if(sele_id[x]==this_optval || this_optval==''){
				check_bool=true;	
				}
				else{
					
				}
				
			}
			if(check_bool==false){
				//alert(this_optval);
				hid_vald=hid_vald+this_optval+',';
			}
		});
		$('#excluded_val').val(hid_vald);
		
		/**from tomorrow***/
		check_duplicate=false;
		
		blank_ingredientgroupname_check();
		serv_textblankcheck('serv_text');		
		  if(serv_textflag==false){}
		else
		  serv_textblankcheck('actual_price');
		
		checkDuplicateFormFactor();
		//alert(arr_dupvals.length);
		
		/**from tomorrow***/
		
		check_weight_empty=true;
		$('.weightclass').each(function(index, element) {
            var $this=$(this);
			var this_val=$this.val();
			$('.selectclass').removeClass('red_border');
			$('.weightclass').removeClass('red_border');
			$('.error_p').remove();
			$this.parent().parent().parent().parent().parent().find('.error_p').remove();
			var this_sel_val=$this.parent().parent().find('.selectclass option:selected').val();
			if(this_sel_val=='' || this_val==''){
				if(this_sel_val==''){
					/*$('html, body').animate({
						scrollTop: $this.offset().top-200
					}, 400);*/
					$this.parent().parent().parent().parent().parent().append('<p class="error_p">Please Select A value from unselected Select options</p>');
					$this.parent().parent().find('.selectclass').addClass('red_border');
				}
				else{
					/*$('html, body').animate({
						scrollTop: $this.offset().top-200
					}, 400);*/
					$this.parent().parent().parent().parent().parent().append('<p class="error_p">Please Enter Weight</p>');
					$this.addClass('red_border');
				}
				check_weight_empty=false;
				return false;	
			}
        });
		
		check_formfactorselection();
		
		$('.actual_price').each(function(index, element) {
            var $this=$(this);
			var this_val=$this.val();
			var min_price=$this.parent().parent().find('.min_price').val();
			if(this_val=='')
			 	this_val=0;
			if(min_price=='')
			 	min_price=0;
			if(parseFloat(this_val)<parseFloat(min_price) || min_price==0)
				priceflag=false;
			else
				priceflag=true;
        });
	   }
	   else{
		$('.right_border select.selectclass.red_border, .weightclass.red_border').removeClass('red_border');
		$('.error_p').remove();	
		$('.right_border .selectclass').each(function(index, element) {
            var $this=$(this);
			var this_sel_val=$('option:selected',this).val();
			if(this_sel_val==''){
				emptyweight_checkfornonmiramix=true;	
			}
			else{
				if($this.parent().parent().find('.weightclass').val()==''){
					//alert($this.parent().parent().find('.weightclass').val());
					$this.parent().parent().find('.weightclass').addClass('red_border');
					$this.parent().parent().parent().parent().parent().append('<p class="error_p">Please  Enter A Weight</p>');
					/*$('html, body').animate({
						scrollTop: $this.offset().top-200
					}, 400);*/
					emptyweight_checkfornonmiramix=false;
					return false;	
				}
				else{
					emptyweight_checkfornonmiramix=true;
				}
			}
        });
		
		allselected_opt();
		checkDuplicateFormFactor();
		check_formfactorselection();
		serv_textblankcheck('serv_text');		
		if(serv_textflag==false){}
		else
		serv_textblankcheck('actual_price');
		blank_ingredientgroupname_check();
		
	}
	});
	
  });
  
  $(window).load(function(e) {
    //$('.right_border .selectclass').trigger("change");
	//setTimeout(checktable_val(),3000) ;
	var total_wt='';
	var total_wt_other=0;
	showSelectedOptions();
	check_addrow();
	total_wt=$('#showTotalPrice').text();
	total_wt=total_wt.replace("$", "");
	$('#TotalPrice').val(total_wt);
	$('.weightclass').each(function(index, element) {
        var $this=$(this);
		total_wt_other=parseFloat(total_wt_other)+parseFloat($this.val());
    });
	$('#TotalWeight').val(total_wt_other);
	//checktable_val();
  onlyLoadChecktable();
});


function showSelectedOptions(){
	$('.right_border .selectclass').each(function(){
		var prevValue = $(this).data('previous');
	$('.right_border select.selectclass').not(this).find('option[value="'+prevValue+'"]').show();    
	var value = $(this).val();
	$(this).data('previous',value);
	if(value==''){}
	else
	$('.right_border select.selectclass').not(this).find('option[value="'+value+'"]').hide();
	})
	
}

$(document).on('keyup','.serv_text,.actual_price',function(){
	  this.value = this.value.match(/[0-9]*\.?[0-9]*/);
  });
  
  $(document).on('blur','.serv_text',function(){
	 var $this=$(this);
	 var serv_val=$this.val();
	 var this_upval=$this.parent().parent().find('.upcharge').val();
	 
	 if(serv_val=='')
	 	serv_val=0;
	 else
	 	serv_val=$this.val();
	 
	 if(this_upval=='')
	 	 this_upval=0;
     else
	 	 this_upval=$this.parent().parent().find('.upcharge').val();
	
	 calc_minval(this_upval,serv_val,$this);
  });
  
  function calc_minval(upchargeval,servingval,thisobj){
	  var up_val=upchargeval,
	      serv_val=servingval,
	      this_obj=thisobj,
	      total_ingredient=parseInt($('.form_check_table table tr').length)-1,
	      total_ingcost=$('#TotalPrice').val(),
		  total_min_prc=0,
		  recom_price=0;

      if($('#TotalPrice').val()=='')
        total_ingcost=0;
		  //alert(total_ingredient);
		  
	  //alert('up_val:'+up_val+'//serv_val:'+serv_val+'//total_ingredient:'+total_ingredient+'//total_ingcost:'+total_ingcost)	  
		   
	  total_min_prc=(((parseFloat(total_ingredient))*parseFloat(up_val))+parseFloat(total_ingcost))*parseFloat(serv_val);
	  //alert(((parseFloat(total_ingredient))*parseFloat(up_val)));
	  recom_price=parseFloat(total_min_prc)*4; 	   	
	  this_obj.parent().parent().find('.min_price').val(total_min_prc.toFixed(2));
	  this_obj.parent().parent().find('.recom_text').val(recom_price.toFixed(2));   
  }
  
  $(document).on('blur','.actual_price',function(){
	  if(checkradiostate_var==true){
		  var actual_prc=0,
			  minimum_prc=0,
			  $this=$(this);
			  
			  $('.alert-danger').remove();
			  
			  minimum_prc=$this.parent().parent().find('.min_price').val();
			  //alert(minimum_prc);
			  if(minimum_prc=='')
					minimum_prc=0;
			  else
					minimum_prc=$this.parent().parent().find('.min_price').val();
					
					
			  
			  actual_prc=$this.val();
			  //alert(actual_prc);
			  
			  if(parseFloat(actual_prc)<parseFloat(minimum_prc)){
					priceflag=false;
					//alert();
			  }
			  else
					priceflag=true;
					
				//alert(priceflag);	
			   if(priceflag==false){ 		
			  $('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Oops!</strong> Please make sure that actual price is at least equal to minimum price.</div>');
			   }
			   else{
				$('.alert-danger').remove();   
			   }
	  }
	  else{}
  });


  $(document).ready(function(){
$(document).on('blur','.form_ingredient_group_panel .weightclass',function(){
	var $this=$(this);
    var tot_recv=calc_weight_ingred($this);
	$this.parent().parent().parent().parent().parent().parent().parent().find('.back_bg').val(tot_recv);
});
    
  onlyforradiostatecheck();
  });
function calc_weight_ingred(obj){
	var total=0;
	console.log(obj);
    var $this=obj;
	
	//alert($this.attr('class'));
	var class_check=$this.attr('class');
	if(class_check=='remove_row'){
	      $this.parent().parent().find('.weightclass').val('');
	}
    $this.parent().parent().parent().parent().find( ".weightclass" ).each(function( index ) {
          var this_val=$(this).val();
		  if(this_val=='')
		  this_val=0;
          total=parseFloat(total)+parseFloat(this_val);
      });
	  return total;
    //$this.parent().parent().parent().parent().parent().parent().parent().find('.back_bg').val(total);
   
 }
 
function check_radio_state(){
	onlyforradiostatecheck();	
	selec_option();
	$("#formfactortable").find("tr:gt(1)").remove();
 }
 function onlyforradiostatecheck(){
	if($('#radio-4').is(':checked')){
		checkradiostate_var=true;
	}
	else{
		checkradiostate_var=false;
	} 
	//alert(checkradiostate_var);	
 }
 
 $(document).on('change','input[name=own_product]',function(){
	  check_radio_state();
	  clearall();	  
	  //selec_option();
}); 
function clearall(){
	$('.recom_text,.upcharge,.min_price,.serv_text,.min_price,.recom_text,.actual_price').val('');
	$('#formfactortable').find('select').val('');
	$("#formfactortable").find("tr:gt(1)").remove();	
}
function allselected_opt(){
	sele_id=[];
	$('#formfactortable select.form-control').each(function(index, element) {
		var $this=$(this);
		var selec_val=$('option:selected',this).val();			
	    sele_id.push(selec_val);
	});
	//alert(sele_id.length);
}
//for duplicate form factor array creation
function checkDuplicateFormFactor(){
		arr_dupvals=[];
		var recipientsArray = sele_id.sort(); 

		
		for (var i = 0; i < recipientsArray.length - 1; i++) {
			if (recipientsArray[i + 1] == recipientsArray[i]) {
				arr_dupvals.push(recipientsArray[i]);
			}
		}
		//alert(sele_id.length);
			
}

function check_formfactorselection(){
		flag=false;
		$('#formfactortable select.form-control.red_border').removeClass('red_border');
		//alert($('#formfactortable select.form-control').length);
		if($('#formfactortable select.form-control').length>0){
		$('#formfactortable select.form-control').each(function(index, element) {
            var $this=$(this);
			var sel_optval=$('option:selected',this).val();
			if(sel_optval=='' || sel_optval==0){
				$this.addClass('red_border');
				/*$('html, body').animate({
					scrollTop: $this.offset().top-200
				}, 400);*/
				flag=false;
				return false;
			}
			else{
				flag=true;	
			}
        });
		}
		else{
			flag=false;	
		}	
}
//blank check for servings and actual price
function serv_textblankcheck(class_name){
	serv_textflag=true;
	msg=''
	var x=class_name;
	$('.serv_text,.actual_price').removeClass('red_border');
	$('.'+x).each(function(index, element) {
        var $this=$(this);
		var this_val=$this.val();
		if(this_val==''){
			$this.addClass('red_border');
			serv_textflag=false;
			/*$('html, body').animate({
					scrollTop: $this.offset().top-200
				}, 400);*/
			if(x=='actual_price')
				msg='Please Enter Actual Price';
			else
				msg='Please Enter Servings Value';
			return false;	
		}
		else{
			serv_textflag=true;
		}
    });	
}
//for blank ingredient name textbox check
function blank_ingredientgroupname_check(){
	groupnameflag=true;
	$('.top_panel').find('.error_p').remove();
	$('.grp_name_text').removeClass('red_border');
	$('.grp_name_text').each(function(index, element) {
        var $this=$(this);
		var this_val=$this.val();
		if(this_val==''){
			$this.addClass('red_border');
			groupnameflag=false;
			/*$('html, body').animate({
					scrollTop: $this.offset().top-200
				}, 400);*/
			$this.parent().parent().parent().parent().parent('.top_panel').append('<p class="error_p">Please Select A Group Name</p>');	
		}
		else{
			groupnameflag=true;
		}
    });
}


 </script>


 <script>
 
 
// CUSTOM TIMKE FRAME
var drowid = 0
function addDur(fid)
{
  //alert(fid)
    var ldiv = 'div#duration_block_'+fid+':last'

    drowid++;
    var cdiv = 'duration_block_'+fid

    var pd = 'div#duration_block_'+fid
    var len = $(pd).length
    //alert(len)
    var len2 = len+1
    var bcls = 'block_'+fid+'_'+len2;
    
    $(ldiv).after('<div class="'+bcls+'" id="'+cdiv+'"><div class="Cell"><input type="text" class="duration ffactor" placeholder="Duration" id="dur_'+fid+'_'+len+'" name="formfactor['+fid+'][duration][]" /></div><div class="Cell"><input type="text" class="min_price ffactor" id="min_'+fid+'_'+len+'" placeholder="Minimum Price" name="formfactor['+fid+'][min_price][]" /></div><div class="Cell"><input type="text" class="rec_price factor" placeholder="Recommended Price" id="rec_'+fid+'_'+len+'" name="formfactor['+fid+'][recommended_price][]" /></div><div class="Cell"><input type="text" class="actual_price ffactor" placeholder="Actual Price" id="act_'+fid+'_'+len+'" name="formfactor['+fid+'][actual_price][]"  /></div><span class="remove" onclick="removeDur('+fid+', '+len2+')"><i class="fa fa-minus-square-o"></i></span></div>');
    //drowid = nd
    //alert(drowid)
    
}
 
function removeDur(m, n)
{
  
    var md = 'div#duration_block_'+m
    var elem = '.block_'+m+'_'+n
    //var pp = $(elem).closest( "data-item" )
    $(elem).remove();

    
}
 

// Updated on 27 Jan 2016

  $(document).on('blur change','input.ffactor',function(){

  
  
  var fflag = false;
  $('.ffactor').each(function(){
    //alert("Inside form factor calculation")
    // First for every field get the id of the field
    var el = $(this).attr('id');
    //var t1 = '';
    //console.log("FF 0: "+el)
    
    // Explode the id value which is in the format with underscore such as dur_0_0
    var res = el.split("_"); // This results in array 
    
    // In case of Form Factor, Form Fee or Servings the length is 2
    if(res.length > 2)
    {
      
      var p1 = res[0]; // First part is first 3 lettters
      var p2 = res[1]; // Second part is sl no. of the form factor (such as 0, 1, 2)
      var p3 = res[2]; // Third part is sl no. of the duration within the form factor (such as 0, 1, 2)

      //console.log("FF 1: "+p1+" "+p2+" "+p3)

      // Get the total ingridient cost      
      var ingcost = 0; var totalingr = 0;

      
      if ($("#TotalPrice").val().trim()) {
        // is empty or whitespace
        ingcost = $("#TotalPrice").val().trim();
      }


      if ($("#TotalIngredients").val().trim()) {
        // is empty or whitespace
        totalingr = $("#TotalIngredients").val().trim();
      }



      console.log("FF 2: "+totalingr)
      
      var formfee = $("#upc_"+p2).val();
      var servday = $("#ser_"+p2).val();
      var duration = $("#dur_"+p2+"_"+p3).val();

      //console.log("FF 3: "+formfee)
      //console.log("FF 4: "+servday)
      //console.log("FF 5: "+duration)

      var ptype =  $('input[name=own_product]:checked').val();

      
      if(ingcost != '' && !isNaN(ingcost) && formfee != '' && !isNaN(formfee) && servday != '' && !isNaN(servday) && duration != '' && !isNaN(duration) && ptype == 0)
      {
        // Calculation: Minimum Price = ((Form Fee x Servings Per Day) + Ingridient Cost ) x Duration
        //var comp = ((parseFloat(formfee) *  parseInt(servday)) + parseFloat(ingcost)) * duration

        // Calculation: Minimum Price = ((Form Fee x Servings Per Day) + Ingridient Cost ) x Duration
        //var comp = ((parseFloat(formfee) *  parseInt(servday)) + parseFloat(ingcost)) * duration
        //console.log("FF 6: "+comp)

        // MP = Min. Price; RP = Recommended Price; FF = Form Fee; I = Num of Ingredient; TC = Total Ing. Cost;
        // S = Servings Per Day; DSC = Daily Serving Cost; D = Durations
        // DSC = ((FF * I) + TC) * S
        // MP = DSC * D
        // RP = MP * 4
        var dsc = ((parseFloat(formfee) * totalingr)  + parseFloat(ingcost)) * parseInt(servday)
        var comp =  dsc * duration

        //console.log("FF 6: "+comp)
        $("#min_"+p2+"_"+p3).val(comp.toFixed(2));
        
        // Calculation: Recommended Price = Minimum Price x 4         
        $("#rec_"+p2+"_"+p3).val((comp * 4).toFixed(2));
        
        //console.log("Value "+$(p).val());
        fflag = true;
      }
      //else
      //  alert("Please enter a number")
      
    }
    
    var actpr = $("#act_"+p2+"_"+p3).val();
    if(actpr != '' && isNaN(actpr))
    {
      fflag = false;
      //alert("Please enter a number for actual fee")
    }
    
     
  });
  
  //alert("aaaa")
  checkFormfactorDurationValues();
  
});




function checkFormfactorDurationValues() {

    mflag = true;

    var durations = $('.duration').map(function() { 
      return this.id; 
    }).get();


    var min_prices = $('.min_price').map(function() { 
      return this.id; 
    }).get();



    var rec_prices = $('.rec_price').map(function() { 
      return this.id; 
    }).get();


    var actual_prices = $('.actual_price').map(function() { 
      return this.id; 
    }).get();

    console.debug(min_prices)
    console.debug(actual_prices)


    
    
    var durval, minprice, recprice, actprice;
    for(i=0; i < min_prices.length; i++) {

      //var mflag=true;

       //var pid = items[i].attr('id');
       //var vid = values[i].attr('id');

     // use .eq() within a jQuery object to navigate it by Index
     var durid = "#"+durations[i];
     var minid = "#"+min_prices[i];
     var recid = "#"+rec_prices[i];
     var actid = "#"+actual_prices[i];

     
     
     var ptype =  $('input[name=own_product]:checked').val();

     //console.log(actid);

     durval = $(durid).val(); // I'm assuming you wanted -name-
     minprice = $(minid).val(); // I'm assuming you wanted -name-
     recprice = $(recid).val(); // I'm assuming you wanted -name-
     actprice = $(actid).val(); // I'm assuming you wanted -name-
     // otherwise it'd be .eq(i).val(); (if you wanted the text value)

     //console.log(durval + " " +minprice + " " +recprice + " " +actprice);
     
     //$(minid).addClass('red_border')



     if(durval != '' && minprice != '' && recprice != '' && actprice != '')
     {
        
       if(parseFloat(durval) < 0 || parseFloat(minprice) < 0 || parseFloat(recprice) < 0 || parseFloat(actprice) < 0 )
       {
          mflag = false; 
          $('.alert-danger').remove();
          $('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Opps!</strong> Please make sure that all prices are greater than zero.</div>');
       }

       else if(ptype == 0)
       {

          if(parseFloat(minprice) > parseFloat(actprice))
          {
            mflag = false; 
            $('.alert-danger').remove();
            $('.form_factore_panel .container-fluid').append('<div class="alert alert-danger" style="margin-top:20px;margin-bottom:0;"><strong>Opps!</strong> Please make sure that actual price is not less than minimum price.</div>');
          }
           
       }


       if(!mflag)
       {
        
        $(minid).addClass('red_border')
        $(actid).addClass('red_border')

        

        //$(minid).css('background-color', '#ffcccc')
        //$(actid).css('background-color', '#ffcccc')

      }
      else
      {
        
        var res = min_prices[i].split("_"); // This results in array 
        //var selite = (res[1])

        $(minid).removeClass('red_border')
        $(actid).removeClass('red_border')
        $('.alert-danger').remove();

        //$(minid).css('background-color', '#ffffff')
        //$(actid).css('background-color', '#ffffff')
      }

    }

  }
  priceflag = mflag;
  return mflag;


}


 
</script>

 @stop