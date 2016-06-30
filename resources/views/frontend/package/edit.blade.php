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
      
                  {!! Form::open(['url' => 'package/update','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'form_factor']) !!}
                  {!! Form::hidden('package_id',$packages[0]->id,['class'=>'form-control','id'=>'package_id','placeholder'=>'id'])!!}
                    <div class="bottom_dash special_bottom_pad clearfix">
                      
                        <div class="row">
                        
                        <div class="col-sm-12">
                        <div class="row">

                          <div class="form-group col-sm-12">
                              <label class="control-label" for="basicinput">Package Type</label>
                              {!! Form::select('package_type', $package_types, $packages[0]->package_type ,['class'=>'multiselect dropdown-toggle btn btn-default','id'=>'example-package-type']) !!} 
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-12">
                              <label class="control-label" for="basicinput">Name *</label>
                              {!! Form::text('name',$packages[0]->name,['class'=>'form-control','id'=>'name','placeholder'=>'Name'])!!}
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-12">
                              {!! Form::hidden('brandmember', $brand_user_id ,['class'=>'form-control','id'=>'brandmember','placeholder'=>'Name'])!!}
                              <div id="coupon_err"></div>
                          </div>

                          
                          
                          <?php
                            $selected_str=$packages[0]->formfactor;
                            if($selected_str!='')
                            {
                              $selected_arr=explode(",",$selected_str);
                            }
                            else
                            {
                              $selected_arr=array(); 
                            }                 
                          ?>  
                          <div class="form-group col-sm-12">
                              <label class="control-label" for="basicinput">Form Factor</label>
                              {!! Form::select('formfactor[]', $formfactors, $selected_arr ,['multiple','id'=>'example-getting-started']) !!} 
                              <div id="coupon_err"></div>
                          </div>
                          <div class="form-group col-sm-4">
                              <label class="control-label" for="basicinput">Dimension</label>
                              {!! Form::text('maximum_depth',$packages[0]->maximum_depth,['class'=>'form-control','id'=>'maximum_weight', 'placeholder'=>'Length']) !!}
                              <div id="coupon_err"></div>
                          </div>
                          <div class="form-group col-sm-4">
                              <label class="control-label" for="basicinput">&nbsp;</label>
                              {!! Form::text('maximum_width',$packages[0]->maximum_width,['class'=>'form-control','id'=>'maximum_weight', 'placeholder'=>'Height']) !!}
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-4">
                              <label class="control-label" for="basicinput">&nbsp;</label>
                              {!! Form::text('maximum_height',$packages[0]->maximum_height,['class'=>'form-control','id'=>'maximum_weight', 'placeholder'=>'Width']) !!}
                              <div id="coupon_err"></div>
                          </div>                          

                          <div class="form-group col-sm-6">
                              <label class="control-label" for="basicinput">Lower Bound</label>
                              {!! Form::text('minimum_unit',$packages[0]->minimum_unit,['class'=>'form-control','id'=>'minimum_unit', 'placeholder'=>'width']) !!}             
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-6">
                              <label class="control-label" for="basicinput">&nbsp;</label>
                              {!! Form::text('maximum_unit',$packages[0]->maximum_unit,['class'=>'form-control','id'=>'maximum_unit', 'placeholder'=>'Height']) !!}
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-6">
                              <label class="control-label" for="basicinput">Upper Bound</label>
                              {!! Form::text('minimum_bound_label',$packages[0]->minimum_bound_label,['class'=>'form-control','id'=>'minimum_bound_label', 'placeholder'=>'width']) !!}                 
                              <div id="coupon_err"></div>
                          </div>

                          <div class="form-group col-sm-6">
                              <label class="control-label" for="basicinput">&nbsp;</label>
                              {!! Form::text('maximum_bound_label',$packages[0]->maximum_bound_label,['class'=>'form-control','id'=>'maximum_bound_label', 'placeholder'=>'Height']) !!}
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
                name: "required",
                price: 
                {               
                    number: true
                }
            },
            
            // Specify the validation error messages
            messages: {
                name: "Please enter package name",
                price: "Please enter valid price"
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
       // $('#brandmember').multiselect();
    });
</script>
 </div>
 @stop