@extends('frontend/layout/frontend_template')
@section('content')

<script>
  
  // When the browser is ready...
  $(function() {
  
   $.validator.addMethod("email", function(value, element) 
      { 
      return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 
      }, "Please enter a valid email address.");

    // Setup form validation  //

    $("#member_login").validate({
        // Specify the validation rules
        rules: {            
            email: 
            {
                required : true,
                email: true
            },
            password: "required"            
        },
        
        // Specify the validation error messages
        messages: {
            email: {
               required: "Please enter email id",
               email : "Please enter a valid email address."
            },
            price: "Please enter password"
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
</script>

<div class="inner_page_container">
    	<div class="header_panel">
        	<div class="container">
        	 <h2>Brands</h2>
             <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Brands</a></li>
                <li>Health Takes Guts</li>
             </ul>
            </div>
        </div>   
<!-- Start Products panel -->
<div class="products_panel">
	<div class="container">
    
    <!--steps_main-->
    <div class="steps_main text-center">
    <ul>
    <li class="active"><span>1</span><h6>Checkout Option</h6></li>
    <li><span>2</span><h6>Payment Method</h6></li>
    <li><span>3</span><h6>Shipping Details</h6></li>
    <li><span>4</span><h6>Confirm Order</h6></li>
    </ul>
    </div>
    <!--steps_main-->
    
    <div class="col-sm-12">
    <div class="row">
    <div class="checkout_cont">
    <h5>Step 1 :  Checkout option</h5>
    
    <div class="row">
    <div class="col-sm-6 spec_padright">
    <h4>New Customer</h4>
    
    <div class="clearfix"><p class="specil_p pull-left mr20">Checkout option :</p>
    <div class="check_box_tab green_version pull-left">                            
         <input type="radio" class="regular-checkbox" id="radio-1" name="RadioGroup1">
         <label for="radio-1">Register Account</label>
    </div></div>
    
    <p>By creating an account you will be able to shop faster, be up to date on an order's status, and keep track of the orders you have previously made.</p>
    
    <button class="full_green_btn pull-left text-uppercase"  id="go_register">Continue</button>
    <label style="display:none" class="error" id="button_err">Please choose the register option.</label>
    
    </div>
    <div class="col-sm-6 spec_padleft">
    <h4>Returning Customer</h4>
    <p class="specil_p">I am a returning customer</p>
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
    <form class="form-horizontal" role="form" id="member_login" name="member_login" method="POST" action="{{ url('checkout-member-login') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group">
    <input type="email" name="email" id="email" class="form-control" placeholder="Email">
    </div>
    <div class="form-group">
    <input type="password" name="password" id="password"  class="form-control" placeholder="Password">
    </div>
    <a href="{!! url() !!}/member-forgot-password" class="btn-link">Forgot Password?</a>
    <input type="submit" class="full_green_btn text-uppercase" value="Login">
    </form>
    
    <p class="specil_p clearfix">OR</p>
    <a href="{!!URL::to('facebook')!!}" class="pull-left"><img src="<?php url();?>public/frontend/images/shopping-checkout/face_btn.png" alt=""></a>
    <a href="{!!URL::to('google')!!}" class="pull-left"><img src="<?php url();?>public/frontend/images/shopping-checkout/goog_btn.png" alt=""></a>
    
    
    </div>
    </div>
    
    </div>
    </div>
    </div>
    
    </div>
</div>
<!-- End Products panel --> 
 </div>
<script>
    $( document ).ready(function(){
        $('#radio-1').on('change',function(){
            checkRedayState();
        });
        
        $("#go_register").click(function(){
            
            if($('#radio-1').is(':checked'))
            {
               
                $("#button_err").hide();
                window.location = "<?php echo url();?>/register";
            }
            else
            {
                $("#button_err").show();
            }
        });
    });

    function checkRedayState()
    {
         if($('#radio-1').is(':checked'))
            {
                $("#button_err").hide();
            }
            else
            {
                $("#button_err").show();
            }
    }
</script>

@stop