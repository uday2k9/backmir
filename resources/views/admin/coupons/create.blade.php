@extends('admin/layout/admin_template')

@section('content')

<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#coupon_form").validate({
        
        ignore: [],
       
        rules: {
            name: "required",
            code: "required",
            type: "required",
            discount: {
                "required" : true,
                "number": true
            }
            
        },
        
        // Specify the validation error messages
        messages: {
            name: "Please enter coupon name.",
            code: "Please enter coupon code.",
            type: "Please choose coupon type.",
            discount: {
                "required": "Please enter coupon discount.",
                "number": "Only Numbers allow"
            }
            
        },               

        submitHandler: function(form) {

            $.ajax({
              url: '<?php echo url();?>/admin/checkCouponCode',
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
              url: '<?php echo url();?>/admin/checkCouponCode',
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
        {!! Form::open(['url' => 'admin/coupon','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'coupon_form','autocomplete'=>'Off']) !!}
            <div class="control-group">
                <label class="control-label" for="basicinput">Coupon Name *</label>

                <div class="controls">
                     {!! Form::text('name',null,['class'=>'span8','id'=>'name']) !!}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="basicinput">Coupon Code *</label>

                <div class="controls">
                     {!! Form::text('code',null,['class'=>'span8','id'=>'code']) !!}
                     <div id="coupon_err"></div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="basicinput">Coupon Type *</label>

                <div class="controls">
                     <select name="type" id="type">
                         <option value="">Choose One</option>
                         <option value="F">Fixed</option>
                         <option value="P">Percentage</option>
                     </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="basicinput">Discount *</label>

                <div class="controls">
                     {!! Form::text('discount',null,['class'=>'span8','id'=>'discount']) !!}
                </div>
            </div>

            <div class="control-group">
                <div class="controls">
                    {!! Form::submit('Save', ['class' => 'btn']) !!}
                   
                     <a href="{!! url('admin/coupon')!!}" class="btn">Back</a>
                   
                </div>
            </div>
        
        {!! Form::close() !!}

    @stop