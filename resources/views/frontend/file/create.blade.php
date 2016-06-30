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
                  <h3>Add Directory</h3>
          
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
      
                    {!! Form::open(['url' => 'file/create','method'=>'POST', 'files'=>true,  'id'=>'directory_create']) !!}
                      <div class="bottom_dash special_bottom_pad clearfix">                      
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="row">
                              <div class="form-group col-sm-12">
                                {!! Form::text('directory_name',null,['class'=>'form-control','id'=>'code','placeholder'=>'Diectory Name'])!!}
                                <div id="coupon_err"></div>
                              </div>
                            </div>  
                          </div>

                         <!-- <div class="col-sm-12">
                            <p class="pull-left for_lineheight">Create Under</p>                            
                            <div class="check_box_tab marg_left pull-left">                            
                              <input type="radio" class="regular-checkbox" id="radio-4" name="create_under" value="1" checked="checked" >
                              <label for="radio-4">Root</label>
                            </div>
                            <div class="check_box_tab marg_left pull-left">                            
                              <input type="radio" class="regular-checkbox" id="radio-5" name="create_under" value="2">
                              <label for="radio-5">Other Directory</label>
                            </div>                       
                          </div>

                          <div class="form-group col-sm-6" id="path" style="padding-top:10px;">
                            <select name="type" id="type" class="form-control">
                                <option value="">Select Directory</option>
                                <option value="F">Fixed</option>
                                <option value="P">Percentage</option>
                            </select>
                          </div> -->

                        </div>                        
                      </div>                    
                      <div class="form_bottom_panel">
                        <a href="<?php echo url();?>/file" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to List</a>                    
                        {!! Form::submit('Save', ['class' => 'btn btn-default green_sub pull-right']) !!}
                      </div>
                    {!! Form::close() !!}
               </div>
               
               </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
    
 </div>
 <script type="text/javascript">   
 // When the browser is ready...
  $(function() {   

    // Setup form validation  //
    


    $("#directory_create").validate({    
        // Specify the validation rules
        rules: {
            directory_name: "required"            
                   
        },
        
        // Specify the validation error messages
        messages: {
            directory_name: "Please enter name"            
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
 </script>
 @stop 