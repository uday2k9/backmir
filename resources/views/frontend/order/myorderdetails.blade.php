@extends('frontend/layout/frontend_template')
@section('content')

<script src="<?php echo url();?>/public/frontend/js/stacktable.js"></script>
<link href="<?php echo url();?>/public/frontend/css/stacktable.css" rel="stylesheet">
<style type="text/css">
.form-style-7{
    max-width:400px;
    margin:50px auto;
    background:#fff;
    border-radius:2px;
    padding:20px;
    font-family: Georgia, "Times New Roman", Times, serif;
}
.form-style-7 h1{
    display: block;
    text-align: center;
    padding: 0;
    margin: 0px 0px 20px 0px;
    color: #5C5C5C;
    font-size:x-large;
}
.form-style-7 ul{
    list-style:none;
    padding:0;
    margin:0;  
}
.form-style-7 li{
    display: block;
    padding: 9px;
    border:1px solid #DDDDDD;
    margin-bottom: 30px;
    border-radius: 3px;
}
.form-style-7 li:last-child{
    border:none;
    margin-bottom: 0px;
    text-align: center;
}
.form-style-7 li > label{
    display: block;
    float: left;
    margin-top: -19px;
    background: #FFFFFF;
    height: 14px;
    padding: 2px 5px 2px 5px;
    color: #B9B9B9;
    font-size: 14px;
    overflow: hidden;
    font-family: Arial, Helvetica, sans-serif;
}
.form-style-7 input[type="text"],
.form-style-7 input[type="date"],
.form-style-7 input[type="datetime"],
.form-style-7 input[type="email"],
.form-style-7 input[type="number"],
.form-style-7 input[type="search"],
.form-style-7 input[type="time"],
.form-style-7 input[type="url"],
.form-style-7 input[type="password"],
.form-style-7 textarea,
.form-style-7 select
{
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    width: 100%;
    display: block;
    outline: none;
    border: none;
    height: 25px;
    line-height: 25px;
    font-size: 16px;
    padding: 0;
    font-family: Georgia, "Times New Roman", Times, serif;
}
.form-style-7 input[type="text"]:focus,
.form-style-7 input[type="date"]:focus,
.form-style-7 input[type="datetime"]:focus,
.form-style-7 input[type="email"]:focus,
.form-style-7 input[type="number"]:focus,
.form-style-7 input[type="search"]:focus,
.form-style-7 input[type="time"]:focus,
.form-style-7 input[type="url"]:focus,
.form-style-7 input[type="password"]:focus,
.form-style-7 textarea:focus,
.form-style-7 select:focus
{
}
.form-style-7 li > span{
    background: #F3F3F3;
    display: block;
    padding: 3px;
    margin: 0 -9px -9px -9px;
    text-align: center;
    color: #C0C0C0;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px;
}
.form-style-7 textarea{
    resize:none;
}
.form-style-7 input[type="submit"],
.form-style-7 input[type="button"]{
    background: #2471FF;
    border: none;
    padding: 10px 20px 10px 20px;
    border-bottom: 3px solid #5994FF;
    border-radius: 3px;
    color: #D2E2FF;
}
.form-style-7 input[type="submit"]:hover,
.form-style-7 input[type="button"]:hover{
    background: #6B9FFF;
    color:#fff;
}
</style>
<script>
  
  // When the browser is ready...

  $(function() {

    $("#wholesale_offer_form").validate({
        submitHandler: function(form) {
          form.submit();
        }
    });

 
    $("#accept_offer").on('click' ,function(){
        //console.log("accept_offer")
        $("#payment_div").show();

    })

     $("#reject_offer").on('click' ,function(){
        console.log("reject_offer")

        if(!confirm("Click Ok to reject the offer"))
          return false;
        //$("#payment_div").show();

    })

    $("#creditcard").on('click' ,function(){
        //console.log("creditcard")
        $(".credit_div").show();
        $(".paypal_div").hide();
    })

 
    $("#paypal").on('click' ,function(){
        console.log("paypal")
        $(".paypal_div").show();
        $(".credit_div").hide();
    })

  });


$( document ).ready();

</script>


<style>
.products-table td {
  vertical-align:top;
  text-align:center;
}

.products-table > td.right {
  vertical-align:top;
  text-align:right;
}

.redfont {
  color:#f00;
}

</style>


  <?php $wholesale_sub_total = 0; $wholesale_item_total = 0; ?>

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
               <div class="container" style="width:970px;">
               
               <div class="col-sm-10 col-sm-offset-1">
               
               <div class="row"><div class="form_dashboardacct">
                  <h3>{{ $title }}</h3>


                  @if(Session::has('error'))
                    <div class="alert alert-danger container-fluid">
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
                     
                    <div class="table-responsive" style="color:#fff">
                    <input type="hidden" name="site_base_url" id="site_base_url" value="<?php echo url();?>" />
                      <table width="100%" class="order-details">
                        <tr>
                          <td colspan="2"><h2>Order History</h2><?php if($order['is_wholesale'] == 1) echo " <h5>Wholesale Order - ".strtoupper($order['wholesale_status']) ?>
                          </td>
                        </tr>
                        <?php                  
                          $serialize_address = unserialize($order['shiping_address_serialize']);                                    
                        ?>
                        <tr>
                          <td width="50%" valign="top">
                            <table width="100%">
                              <tr>
                                <td><strong>Order Information</strong></td>
                              </tr>
                              <tr>
                                <td>Order ID: {{ $order['order_number'] }}</td>
                              </tr>
                              <tr>
                                <td>Account Email: {{ $serialize_address['email'] }}</td>
                              </tr>
                              <tr>
                                <td>Date Added: {{ Carbon\Carbon::parse($order['updated_at'])->format('M/d/Y') }}</td>
                              </tr>
                              <tr>
                                <td>Payment Method: {{ $order['payment_method'] }}</td>
                              </tr>
                              <tr>
                                <td>Shipping Type: {{ $order['shipping_type'] }}</td>
                              </tr>
                              <tr>
                                <td>Shipping Cost($): {{ number_format($order['shipping_cost'],2) }}</td>
                              </tr>
                            </table>
                          </td>
                          <?php 
                            if((isset($serialize_address['zone_id'])))
                            {
                              if(is_numeric($serialize_address['zone_id']))
                              {
                                $state = $obj->get_state($serialize_address['zone_id']);
                              }
                              else
                              {
                                $state = $obj->get_state_name($serialize_address['zone_id'],$serialize_address['country_id']);
                              }
                            }

                            if((isset($serialize_address['country_id'])))
                            {
                              if(is_numeric($serialize_address['country_id']))
                              {
                                $country = $obj->get_country($serialize_address['country_id']);
                              }
                              else
                              {
                                $country = $obj->get_country_name($serialize_address['country_id']);
                              }
                            }
                          ?>
                          <td width="50%" valign="top">                            
                            <table width="100%">
                              <tr>
                                <td><strong>Shipping Address</strong></td>
                              </tr>
                              <tr>
                                <td>{!! isset($serialize_address['first_name'])?($serialize_address['first_name'].' '.$serialize_address['last_name']):'' !!}</td>
                              </tr>
                              <tr>
                                <td>{!! isset($serialize_address['email'])?$serialize_address['email']:''; !!}</td>
                              </tr>
                              <tr>
                                <td>
                                    {!! isset($serialize_address['address'])?$serialize_address['address']:''; !!}<br>
                                    {!! ($serialize_address['address2']!='')?$serialize_address['address2'].'<br>':''; !!}
                                </td>
                              </tr>
                              <tr>
                                <td>{!! isset($serialize_address['city'])?"City: ".$serialize_address['city']:''; !!}</td>
                              </tr>
                              <tr>
                                <td>{!! (isset($serialize_address['zone_id']) && ($serialize_address['zone_id']!=''))?"State: ".$state :'' !!}</td>
                              </tr>
                              <tr>
                                <td>{!! (isset($serialize_address['country_id']) && ($serialize_address['country_id']!=''))? "Country : " .$country:'' !!}</td>
                              </tr>
                              <tr>
                                <td>{!! isset($serialize_address['postcode'])?"Post Code: ".$serialize_address['postcode']:'';!!}</td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <hr />
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <table width="100%" cellpadding="5" cellspacing="5" border="0" class="products-table">
                              <tr>
                                <td>Product Image</td>
                                <td>Product Name</td>
                                <td>Quantity</td>
                                <td>Current Price($)</td>
                                <td>Offered Price($)</td>
                                <td>Duration</td>
                                <td>Form Factor</td>                                
                                <td>Current Total($)</td>   
                                <td>Offered Total($)</td>                             
                              </tr>
                              <tr>
                                <td colspan="10">
                                  <hr />
                                </td>
                              </tr>
                              @foreach($order_items as $order_item)

                              <tr>
                                <td><img src="<?php echo url();?>/uploads/product/medium/{{ $order_item->product_image }}" width="70" /></td>
                                <td>{{ $order_item->product_name }}</td>
                                <td>{{ $order_item->quantity }}</td>
                                <td class="right">{{ number_format($order_item->price,2) }}</td>
                                <td class="redfont right">
                                @if(floatval($order_item->wholesale_offer_price) == 0.00)
                                  {{"Waiting"}}
                                @else
                                  {{ number_format($order_item->wholesale_offer_price,2) }}
                                @endif
                                </td>
                                <td>{{ $order_item->duration }}</td>
                                <td>{{ \App\Model\FormFactor::where('id',$order_item->form_factor_id)->first()->name }}</td>                                                              
                                <td>{{ number_format(($order_item->price * $order_item->quantity),2) }}</td>
                                <td class="redfont right">
                                <?php $wholesale_item_total = $order_item->wholesale_offer_price * $order_item->quantity; ?>

                                @if(floatval($order_item->wholesale_offer_price) == 0.00)
                                  {{"-"}}
                                @else
                                  
                                  {{ number_format(($wholesale_item_total),2) }}
                                @endif
                                </td>
                              </tr>
                               <tr>
                                <td class="space" colspan="7">

                                <?php
                                $wholesale_sub_total += $wholesale_item_total;
                                ?>
                                  
                                </td>
                              </tr>


                              @endforeach

                              <?php $wholesale_grand_total = $wholesale_sub_total - $order['shipping_cost'] ?>
                              <tr>  
                                <td colspan="6" class="text-right">&nbsp;</td>
                                <td>Sub-Total($)</td>                                                          
                                <td class="text-right">{{ number_format($order['order_total'],2) }}</td>  
                                <td class="text-right fontred"><?php if($order['wholesale_status'] != "pending") echo number_format($wholesale_sub_total, 2) ?></td>                               
                              </tr>
                              <tr>  
                                <td colspan="6" class="text-right">&nbsp;</td>
                                <td>Shipping Rate($)</td>                                                          
                                <td class="text-right">{{ number_format($order['shipping_cost'],2) }}</td>   
                                <td class="text-right fontred"><?php if($order['wholesale_status'] != "pending") echo number_format($order['shipping_cost'],2); ?></td>                            
                              </tr>
                              <tr>  
                                <td colspan="6" class="text-right">&nbsp;</td>
                                <td>Total($)</td>                                                          
                                <td class="text-right">{{ number_format($order['sub_total'],2) }}</td>  
                                <td class="text-right fontred"><?php if($order['wholesale_status'] != "pending") echo number_format($wholesale_grand_total,2); ?> </td>                              
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <hr />
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2"><strong>Order History</strong></td>
                        </tr>
                        <tr>
                          <td width="40%">Date Updated</td>
                          <td width="40%">Order Status</td>
                          <td width="30%">&nbsp</td>
                        </tr>
                        <tr>
                          <td colspan="2" class="space" style="border-bottom:1px solid #ccc">
                            &nbsp;
                          </td>
                        </tr>
                        <tr>
                          <td width="40%">{{ Carbon\Carbon::parse($order['updated_at'])->format('M/d/Y') }}</td>
                          <td width="40%">{{ $order['order_status'] }}</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                      <br />

                      @if($order['wholesale_status'] == "offered")

                      <div style="text-align:center">
                      Miramix Admin has offered you a new price per item displayed above in red. Please select from the following option.<br />
                      <!--<br /><a href="<?php echo url();?>/orders/wholesale-status/{{ $order->id }}/accept" class="btn btn-success">Accept</a>&nbsp;<a href="<?php echo url();?>/orders/wholesale-status/{{ $order->id }}/accept" class="btn btn-success">Reject</a></div><br />-->

                      <br /><a href="javascript:void(0);" id="accept_offer" class="btn btn-success">Accept</a>&nbsp;<a href="<?php echo url();?>/wholesale-status/{{ $order->id }}/reject" id="reject_offer"  class="btn btn-success">Reject</a></div><br />



                      {!! Form::open(['url' => '/wholesale-checkout','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'checkout_form2','autocomplete'=>'off']) !!}
                      <div class="formarea-bottom clearfix <?php if(Session::has('member_userid') || Session::has('brand_userid')) {?>no_botpad<?php } else { ?>bot_padreq<?php } ?>" id="payment_div" style="display:none;">

                          
                        <div id="collapseThree">
                          <div class="panel-body">
                                
                          <cite>Please select the preferred payment method to use on this order.</cite>
              
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
                          <cite>Please select the preferred communication method to use on this order.</cite>
                          
                          <div class="check_box_tab green_version">                            
                               <input type="radio" class="regular-checkbox communication_type" id="emailaddress" name="preffered_communication" value="0"
                                <?php echo (Session::get('preffered_communication') =='0')? "checked=checked":''  ?>>
                               <label for="emailaddress">Email</label>
                          </div> 
                          <div class="check_box_tab green_version">                            
                               <input type="radio" class="regular-checkbox communication_type" id="sms" name="preffered_communication" value="1" 
                               <?php echo (Session::get('preffered_communication') =='1')? "checked=checked":''  ?>>
                               <label for="sms">SMS</label>
                          </div>
                          <!--<input type="button" id="payment_btn" class="full_green_btn text-uppercase" value="Continue">-->
                          
                              
                          </div>
                            
                        </div>
                      


                        
                        <div class="credit_div" style="display:none;">
                         
                          <div class="form-group col-sm-10 col-md-6">
                            <label class="col-sm-4">Card Number:*</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control ccjs-number" placeholder="Card Number" name="card_number"  id="card_number" value="">
                            </div>
                          </div>


                          <div class="form-group col-sm-10 col-md-6">
                            <label class="col-sm-4 col-md-5">Card Expiry Date:*</label>
                            <div class="col-sm-8 col-md-7">
                            <div class="row custom_row">
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

                          <div class="form-group col-sm-10 col-md-6">
                            <label class="col-sm-4">Name on Card:</label>
                            <div class="col-sm-8"><input type="text" class="form-control" placeholder="Name on Card" name="name_card" id="name_card"  value=""></div>
                          </div>

                          <div class="form-group col-sm-12 col-md-6">
                            <label class="col-sm-4 col-md-5">Card Security Code:*</label>
                            <div class="col-sm-3 col-md-3 no_sidepad">
                            <input type="password" class="form-control" placeholder="C V V" name="cvv" id="cvv"  value="">
                            </div>
                          </div>
                          <div class="col-sm-12 col-md-5">
                          </div>
                          <div class="col-sm-12 col-md-2">
                            <input type="submit" class="btn btn-success <?php if(Session::has('member_userid') || Session::has('brand_userid')) {?>logged_inbtn<?php } else { ?>logged_outbtn<?php } ?>" id="checkout_submit" value="Checkout">              
                          </div>
                          <div class="col-sm-12 col-md-5">
                          </div>
                        </div>
                        

                        <div class="paypal_div" style="display:none;">
                        <div class="col-sm-12 col-md-6">
                          <img src="<?php echo url();?>/public/frontend/images/shopping-checkout/paypal_shp.png" alt="">
                          <input type="submit" class="btn btn-success text-uppercase pull-right <?php if(Session::has('member_userid') || Session::has('brand_userid')) {?>logged_inbtn<?php } else { ?>logged_outbtn<?php } ?>" id="paypal_checkout" value="Checkout">              
                        </div>
                        <!--###################### HIDDEN FIELD TO INSERT ORDER TABLE START ###############################-->

                        <!--##################### HIDDEN FIELD TO INSERT ORDER TABLE END ##################################-->
                        </div>

                        @elseif($order['wholesale_status'] == "pending")
                          <b>Waiting for special price offer from Miramix Admin.</b>
                        
                        @elseif($order['wholesale_status'] == "accepted")
                          <b>You have accepted the offer.</b>
                        
                        @elseif($order['wholesale_status'] == "rejected")
                          <b>You have rejected the offer.</b>
                        
                        @endif

                      </div>

                      <input type="hidden" name="order_id" value="<?php echo $order['id'] ?>" />
                      <input type="hidden" name="is_wholesale" value="1" />
                      <input type="hidden" name="is_wholesale_accept" value="1" />

                      {!! Form::close() !!}     
                      
                      <div class="form_bottom_panel">
                      <a href="<?php echo url();?>/brand-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>
                      
                   
                    </div>


               </div>
               
               </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->



 </div>

 @stop