@extends('frontend/layout/frontend_template')
@section('content')




<div class="inner_page_container">
    	<div class="header_panel">
        	<div class="container">
        	  <h2>Payment Cancel</h2>
            
            </div>
        </div> 

    <div class="products_panel no_marg">
	<div class="container">
    <div class="product_list shop_cart">
    <div class="col-xs-12 text-center noprod_cart">
	  
       
       @if(Session::has('success'))
                    <div class="alert alert-success container-fluid">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! Session::get('success') !!}</strong>
                    </div>
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger container-fluid">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{!! Session::get('error') !!}</strong>
                </div>
              @endif
        
        <img src="<?php echo url();?>/public/frontend/images/CanceledContract.jpg" alt="">
        <p class="empt_shpcart">Payment Canceled.<br>
            Your Order Id #<a href="<?php echo url();?>/order-detail/{!! Session::get('order_id') !!}">{!! Session::get('order_number') !!}</a>  Is Canceled.
        </p>
     </div>
     </div>
	</div>
	</div>

</div>



@stop