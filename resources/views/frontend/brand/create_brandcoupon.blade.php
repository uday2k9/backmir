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
                  <h3>Add Coupon</h3>
          
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
      
      {!! Form::open(['url' => 'brandcoupons','method'=>'POST', 'files'=>true,  'id'=>'coupon_form']) !!}
                    <div class="bottom_dash special_bottom_pad clearfix">
                      
                        <div class="row">
                        
                        <div class="col-sm-12">
                        <div class="row">
                         <div class="form-group col-sm-12">
                            {!! Form::text('code',null,['class'=>'form-control','id'=>'code','placeholder'=>'Coupon Code'])!!}
                          <div id="coupon_err"></div>
                          </div>
                          <div class="form-group col-sm-6">
                            <select name="type" id="type" class="form-control">
                         <option value="">Choose One</option>
                         <option value="F">Fixed</option>
                         <option value="P">Percentage</option>
                     </select>
                          
                          </div>
                        
                           
                          <div class="form-group col-sm-6">
                            {!! Form::text('discount',null,['class'=>'form-control','id'=>'discount','placeholder'=>'Discount'])!!}
                          </div>
                         
                          
                        </div>  
                        </div>
                        </div>
                        
                    </div>
                    
                    <div class="form_bottom_panel">
                    <!--<a href="<?php echo url();?>/brand-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>-->
                    
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
  
    // Setup form validation on the #register-form element
    $("#coupon_form").validate({
        
        ignore: [],
       
        rules: {
            code: "required",
            type: "required",
            discount: {
                "required" : true,
                "number": true
            }
            
        },
        
        // Specify the validation error messages
        messages: {
            code: "Please enter coupon code.",
            type: "Please choose coupon type.",
            discount: {
                "required": "Please enter coupon discount.",
                "number": "Only Numbers allow"
            }
            
        },               

        submitHandler: function(form) {

            $.ajax({
              url: '<?php echo url();?>/checkCouponCode',
              method: "POST",
              data: { coupon_code : $('#code').val() ,_token: '{!! csrf_token() !!}'},
              success:function(data)
              {
                
                if(data==1){
                    $('#code').val('');
                    $("#coupon_err").html("Coupon code already exists");
                    //setTimeout('$("#coupon_err").html("")',2000);               
                }
                else{
                    form.submit();
                }
              }
            });
            
        }
    });

  });
$(document).ready(function(){
    $('#code').on('blur',function(){
        $('#coupon_err').html("");
        //alert($(this).val());
        if($(this).val()!="")
        {
             $.ajax({
              url: '<?php echo url();?>/checkCouponCode',
              method: "POST",
              data: { coupon_code : $(this).val() ,_token: '{!! csrf_token() !!}'},
              success:function(data)
              {
                
                if(data==1){
                    $('#code').val('');
                    $("#coupon_err").html("Coupon code already exists");
                    setTimeout('$("#coupon_err").html("")',2000);               
                }
              }
            });
        }
    });
});
 
  
  </script>
 </div>
 @stop