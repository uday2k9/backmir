@extends('frontend/layout/frontend_template')
@section('content')

<script>
  
  // When the browser is ready...
  $(function() {
    // Setup form validation  //

    $("#checkout_form").validate({
        // Specify the validation rules
        rules: {            
            card_number: {
                          required: true,
                          creditcard: true
                         },
            card_exp_month: "required",
            card_exp_year: "required",
            cvv: {
                  required: true,
                  minlength: 3
                }   
        },
        
        // Specify the validation error messages
        messages: {
            card_number: {
               required: "Please enter valid card number."
            }
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
        	 <h2>Checkout</h2>
             <!-- <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Brands</a></li>
                <li>Health Takes Guts</li>
             </ul> -->
            </div>
        </div>   
<!-- Start Products panel -->
<div class="products_panel">
	<div class="container">
    
    <!--steps_main-->
    <div class="steps_main text-center">
    <ul>
    <li class="done"><span>&#10003;</span><h6>Checkout Option</h6></li>
    <li class="done"><span>&#10003;</span><h6>Payment Method</h6></li>
    <li class="done"><span>&#10003;</span><h6>Shipping Details</h6></li>
    <li class="active"><span>4</span><h6>Confirm Order</h6></li>
    </ul>
    </div>
    <!--steps_main-->
    
    <div class="col-sm-12">
    <div class="row">
    {!! Form::open(['url' => 'checkout-step4','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'checkout_form']) !!}
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="checkout_cont clearfix nopad_bot">
    <h5>Step 4 :  Confirm Order</h5>
    
    
    <div class="table-responsive table_ship_checkout">
    <table class="table">
    <thead>
    <tr>
    <th>Product Image</th>
    <th>Product Name</th>
    <th>Brand</th>
    <th>Form Factor Name</th>
    <th>Duration</th>
    <th>Quantity</th>
    <th>Unit Price</th>
    <th>Total</th>
  </tr>
    </thead>
      <tbody>
        <?php
          $all_sub_total =0.00;
          $all_total =0.00;
          $total =0.00;
          $grand_total=0.00;
          $total_discount = 0.00;


          if(!empty($cart_result))
          { 
            $i=1;
            foreach($cart_result as $eachcart)
            {
              $all_sub_total = $all_sub_total+$eachcart['subtotal'];
              $all_total = number_format((float)$all_sub_total,2);
              $total = (float)$all_sub_total;                     // Without Adding Shipping Price
        ?>
          
          <tr>
          <td><a href="<?php echo url();?>/product-details/{!! $eachcart['product_slug'] !!}"><img src="<?php echo url();?>/uploads/product/{!! $eachcart['product_image'] !!}" width="116" alt=""></a></td>
            <td><a href="<?php echo url();?>/product-details/{!! $eachcart['product_slug'] !!}">{!! ucwords($eachcart['product_name']) !!}</a></td>
            <td>{!! $eachcart['brand_name'] !!}</td>
            <td>{!! $eachcart['formfactor_name'] !!}</td>
            <td>{!! $eachcart['duration'] !!}</td>
            <td><input type="text" class="form-control spec_width" value="<?php echo $eachcart['qty']; ?>" readonly></td>
            <td>$ {!! number_format($eachcart['price'],2) !!}</td>
            <td>$ {!! number_format($eachcart['subtotal'],2) !!}</td>
          </tr>
        <?php 
         $i++;
         }
        }

        if(Session::has('coupon_type') && Session::has('coupon_discount'))
        {
          $coupon_type = Session::get('coupon_type');
          $coupon_discount = Session::get('coupon_discount');
            if($coupon_type == 'F')
            {
              $grand_total = ($total - $coupon_discount);
              $total_discount = $coupon_discount;
            }
            elseif($coupon_type == 'P')
            {
              $grand_total = ($total - (($total) * ($coupon_discount)/100));
              $total_discount = (($total)* ($coupon_discount)/100);
            }
          
        }
        else
        {
          $grand_total = $total;
        }

        ?>
        <?php 
        if($grand_total<=$all_sitesetting['free_discount_rate']) 
            {
              $shipping_rate = $all_sitesetting['shipping_rate'];
            }
        elseif($grand_total>$all_sitesetting['free_discount_rate'])
        {
          $shipping_rate = 0.00;
        }
        ?>

      <tr>
      <td colspan="6"></td>
      <td class="text-left">
      <span>Sub-Total:</span>
      </td>
      <td class="text-right">
      <span>{!! ($all_total!='')?'$':'' !!}{!!  $all_total !!}</span>
      </td>
      </tr>
      <?php 
      if(Session::has('coupon_type') && Session::has('coupon_discount'))
        {
      ?>

      <tr>
      <td colspan="6"></td>
      <td class="text-left">
      <span>Discount(coupon code  {!! Session::get('coupon_code') !!}):</span>
      </td>
      <td class="text-right">
      <span> -${!! number_format($total_discount,2) !!}</span>
      </td>
      </tr>

      <?php 
        }
      ?>
      <tr>
      <td colspan="6"></td>
      <td class="text-left">
      <span>Shipping Rate:</span>
      </td>
      <td class="text-right">
      <span>{!! (isset($shipping_rate))?'$':'' !!}{!! number_format($shipping_rate,2) !!}</span>
      </td>
      </tr>
      <tr>
      <td colspan="6"></td>
      <td class="text-left">
      <span>Total:</span>
      </td>
      <td class="text-right">
      <span>{!! ($grand_total!='')?'$':'' !!}{!! number_format(($grand_total+$shipping_rate),2) !!}</span>
      </td>
      </tr>
    </tbody>
  </table>
  </div>
    
    
    </div>
    
    <div class="form_bottom_part clearfix">
    <?php if(Session::get('payment_method') =='creditcard')
    {
    ?>
    <h4>Card Details</h4>
    
    <div class="row">
      <div class="row">
      <div class="form-group col-sm-6">
        <label class="col-sm-4">Card Number:*</label>
        <div class="col-sm-8">
        <input type="text" class="form-control ccjs-number" placeholder="Card Number" name="card_number"  id="card_number" value="">
        </div>
      </div>
      <div class="form-group col-sm-6">
         <label class="col-sm-4">Card Expiry Date:*</label>
        <div class="col-sm-8">
        <div class="row">
        <div class="col-sm-6">
        <select class="form-control" name="card_exp_month"  id="card_exp_month">
          
          <option value="">Month</option>
          <?php for($i=1;$i<=12;$i++) {?>
          <option value="<?php echo sprintf('%02d', $i)?>"><?php echo sprintf('%02d', $i);?></option>
          <?php } ?>
     
        </select>
        </div>
        
        <div class="col-sm-6">
          <select class="form-control" name="card_exp_year"  id="card_exp_year">
          <option value="">Year</option>
          <?php for($i=15;$i<50;$i++) {?>
          <option value="<?php echo $i;?>"><?php echo $i;?></option>
          <?php } ?>
          </select>
        </div>
        </div>
        </div>
      </div>

      <div class="form-group col-sm-6">
        <label class="col-sm-4">Name on Card:</label>
        <div class="col-sm-8"><input type="text" class="form-control" placeholder="Name on Card" name="name_card" id="name_card"  value=""></div>
      </div>

      <div class="form-group col-sm-6">
        <label class="col-sm-4">Card Security Code:*</label>
        <div class="col-sm-8">
        <input type="password" class="form-control" placeholder="Card Security Code (CVV)" name="cvv" id="cvv"  value="">
        </div>
      </div>
      </div>
      </div>
      
      <input type="submit" class="full_green_btn text-uppercase pull-right" value="Continue">
    <?php 
    } 
    elseif(Session::get('payment_method') =='paypal')
    {
    ?>
        <img src="<?php echo url();?>/public/frontend/images/shopping-checkout/paypal_shp.png" alt="">
        <input type="submit" class="full_green_btn text-uppercase no_topmarg pull-right" value="Continue">
    <?php 
    }
    ?>
    <!--###################### HIDDEN FIELD TO INSERT ORDER TABLE START ###############################-->
    <input name="grand_total" type="hidden" value="{!! ($grand_total+$shipping_rate) !!}">
    <input name="sub_total" type="hidden" value="{!! ($total) !!}">
    <input name="discount" type="hidden" value="{!! ($total_discount) !!}">
    <!--##################### HIDDEN FIELD TO INSERT ORDER TABLE END ##################################-->
    </div>

    {!! Form::close() !!} 
    
    </div>
    </div>
    
    </div>
</div>
<!-- End Products panel --> 
 </div>
 @stop