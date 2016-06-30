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
      
                    {!! Form::open(['url' => 'file/rename','method'=>'POST', 'files'=>true,  'id'=>'directory_create']) !!}
                      {!! Form::hidden('id',$id,['class'=>'form-control','id'=>'id','placeholder'=>'Diectory Name'])!!}
                      <div class="bottom_dash special_bottom_pad clearfix">                      
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="row">
                              <div class="form-group col-sm-6">
                                  <label class="control-label" for="basicinput">Name *</label>
                                  {!! Form::text('name',$only_file_name,['class'=>'form-control span','id'=>'name','placeholder'=>'Name'])!!}
                                  <div id="coupon_err"></div>
                              </div>

                              <div class="form-group col-sm-2">
                                  <label class="control-label" for="basicinput">&nbsp;</label>
                                  {!! Form::text('ccc',$file_ext,['class'=>'form-control span','id'=>'cc','placeholder'=>'Extention', 'readonly'=>true])!!}
                                  {!! Form::hidden('file_ext',$file_ext,['class'=>'form-control','id'=>'file_ext','placeholder'=>'Name'])!!}
                                  <div id="coupon_err"></div>
                              </div>
                                
                          </div>                    

                         

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
            name: "required"            
                   
        },
        
        // Specify the validation error messages
        messages: {
            name: "Please enter name"            
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
 </script>
 @stop 