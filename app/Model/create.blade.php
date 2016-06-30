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
      <div class="acct_box yellow_act">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                        <img src="<?php echo url();?>/public/frontend/images/account/sold_products.png" alt="">
                        <a href="<?php echo url();?>/sold-products" class="link_wholediv">Sold Products History</a>
                        </div>                      
                    </div>
                </div>
                
                <div class="acct_box red_acct">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/product/create"><img src="<?php echo url();?>/public/frontend/images/account/add_products.png" alt=""></a>
                        <a href="<?php echo url();?>/product/create" class="link_wholediv">Add Products</a>
                        </div>                      
                    </div>
                </div>
                
                <div class="acct_box org_org_acct no_marg">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/my-products"><img src="<?php echo url();?>/public/frontend/images/account/productlist.png" alt=""></a>
                        <a href="<?php echo url();?>/my-products" class="link_wholediv">Product List</a>
                        </div>                      
                    </div>
                </div>
                
                <div class="acct_box new_green_acct no_marg">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                         <a href=""><img src="<?php echo url();?>/public/frontend/images/account/order_hist.png" alt=""></a>
                         <a href="javascript:void(0);" class="link_wholediv">Order History<span>Coming Soon</span></a>
                        </div>                      
                    </div>
                </div>
                
                <div class="acct_box blue_acct front">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/brand-account"><img src="<?php echo url();?>/public/frontend/images/account/pers_info.png" alt=""></a>
                        <a href="<?php echo url();?>/brand-account" class="link_wholediv">Brand Information</a>
                        </div>                      
                    </div>
                </div>
                
                <!--<div class="acct_box green_acct">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/change-password"><img src="<?php echo url();?>/public/frontend/images/account/changepassword.png" alt=""></a>
                        <a href="<?php echo url();?>/change-password">Change Password</a>
                        </div>                      
                    </div>
                </div>-->
                
                <div class="acct_box violet_acct front">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                        <img src="<?php echo url();?>/public/frontend/images/account/address.png" alt="">
                        <a href="<?php echo url();?>/brand-shipping-address" class="link_wholediv">My Address</a>
                        </div>                      
                    </div>
                </div>
                
               <!-- <div class="acct_box orange_acct no_marg pull-right">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                        <img src="<?php echo url();?>/public/frontend/images/account/store.png" alt="">
                        <a href="javascript:void(0);">Store Font<span>Coming Soon</span></a>
                        </div>                      
                    </div>
                </div>-->
        
        
    <div class="acct_box blue_acct front">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/brand-creditcards"><i class="fa fa-credit-card"></i></a>
                        <a href="<?php echo url();?>/brand-creditcards" class="link_wholediv">Credit Card Details</a>
                        </div>                      
                    </div>
                </div>
        
        
    <div class="acct_box blue_acct no_marg">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/brand-paydetails"><i class="fa fa-cc-paypal"></i></a>
                        <a href="<?php echo url();?>/brand-paydetails" class="link_wholediv">Payment Details</a>
                        </div>                      
                    </div>
                </div>
        
    <div class="acct_box org_org_acct front">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                        <a href="#"><img src="<?php echo url();?>/public/frontend/images/account/productlist.png" alt=""></a>
                        <a href="<?php echo url();?>/subscription-history" class="link_wholediv">Subscription History</a>
                        </div>                      
                    </div>
                </div>
        
    <div class="acct_box blue_acct front">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                        <a href="#"><i class="fa fa-credit-card"></i></a>
                        <a href="#" class="link_wholediv">Wholesale<span>Coming Soon</span></a>
                        </div>                      
                    </div>
                </div>

                <div class="acct_box red_acct">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/brandcoupons"><img src="<?php echo url();?>/public/frontend/images/account/add_products.png" alt=""></a>
                        <a href="<?php echo url();?>/brandcoupons" class="link_wholediv">Coupons</a>
                        </div>                      
                    </div>
                </div>
                <div class="acct_box new_green_acct no_marg">
                  <div class="acct_box_inn">
                      <div class="acct_box_inn_inn">
                         <a href="<?php echo url();?>/package"><img src="<?php echo url();?>/public/frontend/images/account/order_hist.png" alt=""></a>
                         <a href="<?php echo url();?>/package" class="link_wholediv">Package Management</a>
                        </div>                      
                    </div>
                </div>
    </div>
         <!--my_acct_sec-->
           <div class="my_acct_sec">           
               <div class="container">
               
               <div class="col-sm-10 col-sm-offset-1">
               
               <div class="row"><div class="form_dashboardacct">
                  <h3>{{ $title }}</h3>
          
                 @if(Session::has('error'))
                    <div class="alert alert-error container-fluid">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! Session::get('error') !!}</strong>
                    </div>
                  @endif
                  @if(Session::has('success'))
                    <div class="alert alert-success container-fluid">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{!! Session::get('success') !!}</strong>
                    </div>
                  @endif
      
                  {!! Form::open(['url' => 'package/store','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'form_factor']) !!}
                    <div class="bottom_dash special_bottom_pad clearfix">
                      
                        <div class="row">
                        
                        <div class="col-sm-12">
                        <div class="row">
                          <div class="form-group col-sm-12">
                              {!! Form::hidden('brandmember', $brand_user_id ,['class'=>'form-control','id'=>'brandmember','placeholder'=>'Name'])!!}
                              <div id="coupon_err"></div>
                          </div>
                          <div class="form-group col-sm-12">
                              <label class="control-label" for="basicinput">Package Type</label>
                              {!! Form::select('package_type', $package_types, '' ,['class'=>'multiselect dropdown-toggle btn btn-default','id'=>'example-package-type']) !!} 
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-12">
                              <label class="control-label" for="basicinput">Name *</label>
                              {!! Form::text('name',null,['class'=>'form-control','id'=>'name','placeholder'=>'Name'])!!}
                              <div id="coupon_err"></div>
                          </div>                      
                          
                          <div class="form-group col-sm-12">
                              <label class="control-label" for="basicinput">Form Factor</label>
                              {!! Form::select('formfactor[]', $formfactors, '' ,['multiple','id'=>'example-getting-started']) !!} 
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-4">
                              <label class="control-label" for="basicinput">Dimension</label>
                              {!! Form::text('maximum_depth',null,['class'=>'form-control','id'=>'maximum_weight', 'placeholder'=>'Length']) !!}
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-4">
                              <label class="control-label" for="basicinput">&nbsp;</label>                             
                              {!! Form::text('maximum_width',null,['class'=>'form-control','id'=>'maximum_weight', 'placeholder'=>'Width']) !!}                              
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-4">    
                              <label class="control-label" for="basicinput">&nbsp;</label>                           
                              {!! Form::text('maximum_height',null,['class'=>'form-control','id'=>'maximum_weight', 'placeholder'=>'Height']) !!}                              
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-6">
                              <label class="control-label" for="basicinput">Lower Bound</label>
                              {!! Form::text('minimum_unit',null,['class'=>'form-control','id'=>'minimum_unit', 'placeholder'=>'width']) !!}             
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-6">
                              <label class="control-label" for="basicinput">&nbsp;</label>
                              {!! Form::text('maximum_unit',null,['class'=>'form-control','id'=>'maximum_unit', 'placeholder'=>'Height']) !!}
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-6">
                              <label class="control-label" for="basicinput">Upper Bound</label>
                              {!! Form::text('minimum_bound_label',null,['class'=>'form-control','id'=>'minimum_bound_label', 'placeholder'=>'width']) !!}                 
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-6">
                              <label class="control-label" for="basicinput">&nbsp;</label>
                              {!! Form::text('maximum_bound_label',null,['class'=>'form-control','id'=>'maximum_bound_label', 'placeholder'=>'Height']) !!}
                              <div id="coupon_err"></div>
                          </div>                           
                        </div>  
                        </div>
                        </div>
                        
                    </div>
                    
                    <div class="form_bottom_panel">
                    <a href="<?php echo url();?>/package" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to List</a>
                    
                    {!! Form::submit('Save', ['class' => 'btn btn-default green_sub pull-right']) !!}
                    </div>
                     {!! Form::close() !!}
               </div>
               
               </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
     <script>
  
  // When the browser is ready...
  $(function() {   

    // Setup form validation  //
    


    $("#form_factor").validate({    
        // Specify the validation rules
        rules: {
            name: "required" ,           
            image: "required"       
        },
        
        // Specify the validation error messages
        messages: {
            name: "Please enter package name"            
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>  
  <script type="text/javascript">
    $(document).ready(function() {
        $('#example-getting-started').multiselect();       
       // $('#example-package-type').singleselect();     
    });
</script>
 </div>
 @stop