@extends('frontend/layout/frontend_template')
@section('content')

<!-- jQuery Form Validation code -->
  <script>
  // When the browser is ready...
  $(function() {
    // Setup form validation on the #register-form element
    $("#checkout_form").validate({
        errorPlacement: function(error, element) 
        {
          error.insertBefore(element);
        },
        // Specify the validation rules
        rules: {
            	payment_type: "required",
			    //privacy:"required"
        },
        // Specify the validation error messages
        messages: {
            payment_type: "Please select preffered payment method"            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
  });
  
  </script>
  <!--------- jQuery Form Validation code End--------->
  
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
    <li class="done"><span>&#10003;</span><h6>Checkout Option</h6></li>
    <li class="active"><span>2</span><h6>Payment Method</h6></li>
    <li><span>3</span><h6>Shipping Details</h6></li>
    <li><span>4</span><h6>Confirm Order</h6></li>
    </ul>
    </div>
    <!--steps_main-->
    
    <div class="col-sm-12">
    <div class="row">
    <div class="checkout_cont check_fix clearfix">
    <h5>Step 2 :  Payment Method</h5>
    
    <cite>Please select the preferred payment method to use on this order.</cite>
    
  {!! Form::open(['url' => 'checkout-step2','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'checkout_form']) !!}
    <div class="check_box_tab green_version">                            
         <input type="radio" class="regular-checkbox payment_type" id="creditcard" name="payment_type" value="creditcard"
          <?php echo (Session::get('payment_method') =='creditcard')? "checked=checked":''  ?>>
         <label for="creditcard">Credit or Debit Card</label>
    </div> 
    <div class="check_box_tab green_version">                            
         <input type="radio" class="regular-checkbox payment_type" id="paypal" name="payment_type" value="paypal" 
         <?php echo (Session::get('payment_method') =='paypal')? "checked=checked":''  ?>>
         <label for="paypal">Paypal</label>
    </div>
    <!-- <div class="normal_label"><input type="checkbox" id="privacy"  name="privacy"><label for="check-1">I have read and agree to the Privacy Policy</label></div> -->
    <input type="submit" class="full_green_btn text-uppercase" value="Continue">
	{!! Form::close() !!}   
    
    
    </div>
    </div>
    </div>
    
    </div>
</div>
<!-- End Products panel --> 
 </div>
@stop

