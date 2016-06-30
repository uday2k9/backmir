@extends('frontend/layout/frontend_template')
@section('content')
<?php 
	if($all_sitesetting['payment_mode'] == 'test')
	{
		$paypal_url = $all_sitesetting['paypal_url_test'];
		$business_account = $all_sitesetting['business_account_test'];
	}
	else if($all_sitesetting['payment_mode'] == 'live')
	{
		$paypal_url = $all_sitesetting['paypal_url_live'];
		$business_account = $all_sitesetting['business_account_live'];
	}
?>
<div><img src="<?php echo url();?>/public/frontend/images/load_genearted.GIF"><div>
<?php
$custom_data =$order_list[0]->user_id.",".$order_list[0]->id;
//$custom_data_serialize = serialize($custom_data);


?>
<div id="paypal_form"> 
<form  action="<?php echo $paypal_url; ?>" method="post" name="_xcart" id="payment_form">

        <input type=hidden name=cmd value="_cart" />
		<input type=hidden name=upload value="1">
                
        <input type="hidden" name="rm" value="2">
        <input type="hidden" name="business" value="<?php echo $business_account;?>">    
        <input type="hidden" name="return" value="<?php echo url();?>/checkout-success">
        <input type="hidden" name="cancel_return" value="<?php echo url();?>/checkout-cancel">
        <input type="hidden" name="notify_url" value="<?php echo url();?>/paypal-notify">
        <input type="hidden" name="currency_code" value="USD" />

		<?php 
		$i =1;
		$total_price = ($order_list[0]->sub_total-$order_list[0]->total_discount);
		$subtotal = $order_list[0]->sub_total;
		foreach($order_list as $eachOrderlist)
		{
			
		?>
			<input type="hidden" name="item_name_<?php echo $i;?>" id="item_name_<?php echo $i;?>" value="{!! $eachOrderlist->product_name !!}">
	        <input type="hidden" name="quantity_<?php echo $i;?>" id="quantity_<?php echo $i;?>" value="{!! $eachOrderlist->quantity !!}">
	        <input type="hidden" name="amount_<?php echo $i;?>" id="amount_<?php echo $i;?>" value="{!! $eachOrderlist->price !!}">
		<?php 
		$i++;
		}
		?>
		<?php
			// Check Shipping rate
			if($subtotal <= $all_sitesetting['free_discount_rate']) 
			{
			  $shipping_rate = $order_list[0]->shipping_cost;
			}
			elseif($subtotal>$all_sitesetting['free_discount_rate'])
			{
				$shipping_rate = 0.00;
			}
		?>
        <input type="hidden" name="handling_cart" id="handling_cart" value="{!! $shipping_rate !!}">
        <?php 

        if($order_list[0]->total_discount!=0){
        	
			$discount=$order_list[0]->total_discount;
			if($order_list[0]->redeem_amount>0){
			$discount=$discount+$order_list[0]->redeem_amount;
			}
        ?>
         <input type="hidden" name="discount_amount_cart" id="discount_amount_cart" value="{!! $discount; !!}" >
         <?php }

         elseif($order_list[0]->redeem_amount>0){ ?>
	 <input type="hidden" name="discount_amount_cart" id="discount_amount_cart" value="{!! $order_list[0]->redeem_amount; !!}" >
	 <?php }?>

        <input type="hidden" name="custom" id="custom" value="{!! $custom_data !!}" >
        <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit">
</form>

</div>
<script>
	$( document ).ready(function() {
//alert('hi');
		$("#payment_form").submit();
	});
</script>
<style>
#paypal_form
{
	display:none;
}
</style>
@stop

