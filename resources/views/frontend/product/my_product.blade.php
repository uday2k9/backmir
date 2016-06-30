@extends('frontend/layout/frontend_template')
@section('content')


<div class="inner_page_container nomar_bottom">

<div id="nav-icon2">
  <span></span>
  <span></span>
  <span></span>
  <span></span>
  <span></span>
  <span></span>
</div>
<div class="mob_topmenu_back"></div>

<div class="top_menu_port">
 	@include('frontend/includes/left_menu')
</div>


    	   <!--my_acct_sec-->
           <div class="my_acct_sec">           
               <div class="container">
               
               <div class="col-sm-12">
               
               
               <div class="row">
               <div class="form_dashboardacct">
               		<h3>Product List</h3>
                    <div class="bottom_dash clearfix">
                    	
                        <div class="row">
                        
                         <div class="col-sm-12">
                           @if(Session::has('error'))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{!! Session::get('error') !!}</strong>
                            </div>
                            @endif
                            @if(Session::has('success'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{!! Session::get('success') !!}</strong>
                            </div>
                            @endif
                         
                          <div class="product_list">
   	  <?php //echo count($product);exit;
        if((count($product[0]))>0) 
        {
          foreach($product as $each_product)
            {
      ?>
              <div class="product">
                	<div class="head_section">
                   	  <h2>{!! ucwords($each_product->product_name) !!}</h2>
                       <p class="price"><?php echo '$'.number_format($each_product->min_price, 2);?> </p>
                      </div>
                    <!--<div class="image_section">
                    @if($each_product->image1!="")
               	  		<img src="<?php echo url();?>/uploads/product/{!! $each_product->image1 !!}" alt=""/>
                    @else
                      <img src="<?php echo url();?>/public/frontend/images/noimage.png" alt=""/>
                    @endif
                      <div class="image_info">
                      <a href="{!! url() !!}/edit-product/{!! $each_product->product_slug !!}" class="butt butt-green"><img src="<?php echo url();?>/public/frontend/images/icon3.png" alt=""/> Edit</a>

                      <a href="{!! url() !!}/product-details/{!! $each_product->product_slug !!}" class="butt butt-green"><img src="<?php echo url();?>/public/frontend/images/icon3.png" alt=""/> View Details</a>
                  
                      <button class="btn btn-primary btn-lg button_hide" id="hit_modal1" data-toggle="modal" data-target="#showscript{!! $each_product->id !!}">Script</button>
                      </div>
                  </div>--> 

                  
                  @if($each_product->image1!="")
                  <div class="image_section" style="background:url(<?php echo url();?>/uploads/product/{!! $each_product->image1 !!}) no-repeat 0 0;background-size:cover;">
       
                      <div class="image_info">
                      <a href="{!! url() !!}/edit-product/{!! $each_product->product_slug !!}" class="butt butt-green butt-edit"><img src="<?php echo url();?>/public/frontend/images/icon3.png" alt=""/> Edit</a>

                      <a href="{!! url() !!}/product-details/{!! $each_product->product_slug !!}" class="butt butt-green butt-view"><img src="<?php echo url();?>/public/frontend/images/icon3.png" alt=""/> View Details </a>
                  
                      <?php if($subscription_status=='inactive'){ ?>
                      <button class="button_hide butt butt_script" id="inactive_modal" data-toggle="modal" data-target="#showinactive{!! $each_product->id !!}">Script</button>
                      <?php } else { ?>
                       <button class="button_hide butt butt_script" id="hit_modal1" data-toggle="modal" data-target="#showscript{!! $each_product->id !!}">Script</button>
                      <?php } ?>
                      <a href="{!! url() !!}/private/{!! $each_product->sku !!}" class="butt butt-green butt-link" data-toggle="tooltip" data-placement="bottom" title="Private URL"><i class="fa fa-link"></i></a>
                      
                      <!-- <a href="javascript:delete_pro({!! $each_product->id !!})" class="butt inline-butt danger-red butt-trash" data-toggle="tooltip" data-placement="bottom" title="Delete Product"><i class="fa fa-trash"></i></a> -->
                      <a href="javascript:delete_pro({!! $each_product->id !!})" class="butt inline-butt danger-red butt-trash"><i class="fa fa-trash"></i></a>
                      </div>

                  </div>
                  <?php if($each_product->visiblity == 1) { ?>
                  <!--<b>Private URL: <a href="<?php echo url()."/private/".$each_product->sku; ?>"><?php echo url()."/private/".$each_product->sku; ?></a></b><input type="text" id="privateURL1" value="<?php echo url()."/private/".$each_product->sku; ?>" onblur="copyText(this)" style="width:200px;">-->

                    <b>Private URL:</b> <a href="<?php echo url()."/private/".$each_product->sku; ?>" target="_blank" style="font-size:14px; color:#00f;"><?php echo url()."/private/".$each_product->sku; ?></a>
                  

                  <?php } ?>
                  @else
                  <div class="image_section" style="background:url(<?php echo url();?>/public/frontend/images/noimage.png) no-repeat 0 0;background-size:cover;">
                    <div class="image_info">
                      <a href="{!! url() !!}/edit-product/{!! $each_product->product_slug !!}" class="butt butt-green butt-edit"><img src="<?php echo url();?>/public/frontend/images/icon3.png" alt=""/> Edit</a>

                      <a href="{!! url() !!}/product-details/{!! $each_product->product_slug !!}" class="butt butt-green butt-view"><img src="<?php echo url();?>/public/frontend/images/icon3.png" alt=""/> View Details </a>
                      <?php if($subscription_status=='inactive'){ ?>
                      <button class="button_hide butt butt_script" id="inactive_modal" data-toggle="modal" data-target="#showinactive{!! $each_product->id !!}">Script</button>
                      <?php } else { ?>
                       <button class="button_hide butt butt_script" id="hit_modal1" data-toggle="modal" data-target="#showscript{!! $each_product->id !!}">Script</button>
                      <?php } ?>
                      <a href="{!! url() !!}/private/{!! $each_product->sku !!}" class="butt butt-green butt-link" data-toggle="tooltip" data-placement="bottom" title="Private URL" target="_blank"><i class="fa fa-link"></i></a>
                      
                      <!-- <a href="javascript:delete_pro({!! $each_product->id !!})" class="butt inline-butt danger-red butt-trash" data-toggle="tooltip" data-placement="bottom" title="Delete Product"><i class="fa fa-trash"></i></a> -->
                      <a href="javascript:delete_pro({!! $each_product->id !!})" class="butt inline-butt danger-red butt-trash"><i class="fa fa-trash"></i></a>

                    </div>
                  </div>
                  
                  <?php if($each_product->visiblity == 1) { ?>
                  <!--<b>Private URL: </b><input type="text" id="privateURL2" value="<?php echo url()."/private/".$each_product->sku; ?>" onblur="copyText(this)" style="width:200px;">-->

                  <b>Private URL:</b> <a href="<?php echo url()."/private/".$each_product->sku; ?>" target="_blank" style="font-size:14px; color:#00f;"><?php echo url()."/private/".$each_product->sku; ?></a>
                  
                  
                  <?php } ?>

                  @endif
              </div> 

           <!-- Modal -->
          <div class="modal fade" id="showinactive{!! $each_product->id !!}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel">Script Generated</h4>
                    </div>
                    <div class="modal-body clearfix">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group">
                                  <div class="input-group demo_table">
                                    Your subscription is over. Subscribe to add more products.
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>    
          </div>
          <!-- /.modal -->
          <!-- Modal -->
          <div class="modal fade" id="showscript{!! $each_product->id !!}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel">Script Generated</h4>
                    </div>
                    <div class="modal-body clearfix">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group">
                                  <div class="input-group demo_table"><textarea name="script_product" class="script_txtarea">{!! $each_product->script_generated !!} </textarea></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>    
          </div>
          <!-- /.modal -->
        <?php 
            }
        }
        else
        {
        ?>
            <div>No Record Found.</div>
        <?php 
        }
        ?>
      

    </div>
                         </div>

                        
                        </div>
                        
                    </div>
                    
                    <div class="form_bottom_panel">
                    <!--<a href="<?php echo url();?>/brand-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>-->
                    <!--<button type="submit" form="dashboardpersonal" class="btn btn-default green_sub pull-right">Save</button>-->
                    </div>
                    
               </div>
               
               </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
           
           <?php echo $product->render(); ?>
 </div>
<script type="text/javascript">
  function delete_pro(param){

    swal({
       title: 'Confirm',
       text: 'Are you sure to delete this product?',
       type: 'warning',
       showCancelButton: true,
       confirmButtonText: 'Yes, sir',
       cancelButtonText: 'Not at all'
    }, function() {
           window.location = '{!! url() !!}/delete-product/'+param;
        });

   

  }


function copyText(text) {
    //var text = $("#"+itm).val()

    //alert(text)
    //Copied = text.createTextRange();
    //Copied.execCommand("Copy");

    console.debug(text)
}
</script>

@stop