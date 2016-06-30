@extends('frontend/layout/frontend_template')
@section('content')


<!----Fb Script Start-->

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=<?php echo env('FB_CLIENT_ID')?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>


<script src="<?php echo url();?>/public/frontend/js/jquery.raty.js"></script> 
<script src="<?php echo url();?>/public/frontend/js/jquery.elevatezoom.js"></script>
<link href="<?php echo url();?>/public/frontend/css/jquery.raty.css" rel="stylesheet">

<!------------ Fb Script End ------------>

  <div class="inner_page_container">
    <div class="header_panel">
        <div class="container">
         <h2>Shopping Cart : </h2>
         
          </div>
    </div>   
    <!-- Start Products panel -->
    <div class="products_panel no_marg">
      <div class="container">
        <div class="product_list shop_cart">
          @if(Session::has('success'))
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <strong>{!! Session::get('success') !!}</strong>
            </div>
          @endif
          @if(Session::has('error'))
            <div class="alert alert-danger">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <strong>{!! Session::get('error') !!}</strong>
            </div>
          @endif
          <?php 
            if(!empty($cart_result)) {
          ?> 
              <div class="row"> 
                <div class="col-sm-9 clearfix hidden-xs visible-md visible-sm visible-lg">
                  <div class="table-responsive shad_tabres hidefromtabdown" id="order-detail-content">
                      <table class="table table-cart" id="cart_summary">
                        <thead>             
                        <tr>
                            <th class="cart_product">Product Image</th>
                            <th class="cart_description">Product Name</th>
                            <th class="text-center cart_brand">Brand</th>
                            <th class="text-center cart_quantity">Quantity</th>
                            <th class="cart_unit">Unit Price</th>
                            <th class="cart_total">Total</th>
                            <th class="cart_delete">&nbsp;</th>
                          </tr>              
                        </thead>
                      <tbody>

                     

                        <?php
                        $all_sub_total =0.00;
                        $all_total =0.00;
                        $coupon_amount = 0.00;
                        $social_discount = 0.00;
                        $provisional_wholesale_adjustment = 0;



                        if(!empty($cart_result))
                        { 
                          

                          $i=1;
                          foreach($cart_result as $eachcart)
                          {
                            $all_sub_total = $all_sub_total+$eachcart['subtotal'];
                           // $all_sub_total = number_format($all_sub_total,2);
                            //$share_discount = $share_discount + $eachcart['share_discount'];
                        ?>
                        <tr class="cart_item">
                          <td class="cart_product">
                          
                          <a href="<?php echo url();?>/product-details/{!! $eachcart['product_slug'] !!}"><img src="<?php echo url();?>/uploads/product/{!! $eachcart['product_image'] !!}" width="116" alt=""></a></td>
                          <td class="cart_description"><a href="<?php echo url();?>/product-details/{!! $eachcart['product_slug'] !!}">{!! ucwords($eachcart['product_name']) !!}</a><br />
                          {!! $eachcart['duration'] !!}<br />
                          {!! $eachcart['formfactor_name'] !!}<br />

                          <?php 
                          if(isset($eachcart['is_wholesale']) && $eachcart['is_wholesale'] == 1) { 
                            $iswh = 1;
                          ?>

                          <font color="#ff0000">Eligible for wholesale rate</font>
                          <?php 
                          }
                          else
                          {
                            $iswh = 0;
                          }
                          ?>

                          
                          </td>
                          <td class="cart_brand" data-title="Brand"><a href="<?php echo url();?>/brand-details/{!! $eachcart['brand_slug'] !!}">{!! $eachcart['brand_name'] !!}</a></td>
                          <td class="cart_quantity" data-title="Quantity"><div class="input-group bootstrap-touchspin pull-left"><span class="input-group-addon bootstrap-touchspin-prefix"></span><input type="text" value="<?php echo $eachcart['qty']; ?>" id="cart<?php echo $i;?>" name="demo1" class="form-control demo1">
                            <input type="hidden" id="iswholesale<?php echo $i;?>" value="<?php echo $iswh ?>" />
                          </div><a href="javascript:void(0);" class="refresh_btn" onclick="updateCart('<?php echo $eachcart['rowid'];?>','<?php echo $i;?>')"><i class="fa fa-refresh"></i></a></td>
                          <td class="cart_unit" data-title="Unit Price">${!! number_format($eachcart['price'],2) !!}</td>
                          <td class="cart_total" data-title="Total">${!! number_format($eachcart['subtotal'],2) !!}</td>
                          <td class="cart_delete"><a href="javascript:void(0);" onclick="deleteCart('<?php echo $eachcart['rowid'];?>','<?php echo $eachcart['brand_id'];?>')" class="btn-link del_link"><img src="<?php echo url();?>/public/frontend/images/proddetails/dele_cart.png" alt=""></a></td>

                        </tr>

                         
                        <?php 
                           $i++;
                          
                            //$share_discount = $share_discount+$eachcart['share_discount'];
                           }
                          
                           //echo "o= ".$share_discount;
                          /*---------------------*/
                          
                          // NOTE: 25-Jan-2015: Social Share discount percentage is stored inside $share_discount whereas
                          // the value of discount is stored inside $social_discount 

                          if(isset($is_eligible_for_wholesale_discount) && $is_eligible_for_wholesale_discount == 1 && isset($brand_tot_wholesale_amount) && $brand_tot_wholesale_amount > 0)
                          {
                              $all_sub_total -= $brand_tot_wholesale_amount;
                              //$all_total -= $brand_tot_wholesale_amount;
                          }
                          //dd($all_sub_total);
                          
                           if(Session::has('coupon_discount'))
                           {
                              if(($share_discount==0) || ($share_discount==''))
                              {
                                if(Session::get('coupon_type')=='P')
                                {
                                  $dis_percent = Session::get('coupon_discount');

                                  $dis_amnt = ($dis_percent/100) * $all_sub_total;
                                  $net_amnt = $all_sub_total - $dis_amnt;
                                  if($net_amnt<0)
                                    $all_total = $all_sub_total;
                                  else
                                    $all_total = $net_amnt;


                                  $coupon_amount = $dis_amnt;
                                }
                                else
                                {
                                  $all_total = $all_sub_total - Session::get('coupon_discount');
                                  $coupon_amount = Session::get('coupon_discount');
                                }
                              } // share coupon status if end
                              elseif(($share_discount>0) && (Session::get('share_coupon_status')==1))
                              {
                                // Show coupon+ social
                                if(Session::get('coupon_type')=='P')
                                {
                                  $dis_percent = Session::get('coupon_discount');

                                  $dis_amnt = ($dis_percent/100) * $all_sub_total;
                                  
                                  // Social Discount (25 Jan, 2016): Calculate social discount in percentage not in absolute amount
                                  $net_amnt1 = $all_sub_total - $dis_amnt;
                                  $social_discount = ($net_amnt1 * $share_discount)/100; // In absolute amount
                                  $net_amnt = $net_amnt1 - $social_discount;
                                  // End of modification for Social Discount (25 Jan, 2016)

                                  if($net_amnt<0)
                                    $all_total = $all_sub_total;
                                  else
                                    $all_total = $net_amnt;


                                  $coupon_amount = $dis_amnt;
                                  
                                }
                                else
                                {
                                  // Social Discount (25 Jan, 2016): Calculate social discount in percentage not in absolute amount
                                  
                                  $all_total = $all_sub_total - Session::get('coupon_discount');
                                  $social_discount = ($all_total * $share_discount) / 100;
                                  $all_total = $all_total - $social_discount;
                                  // End of modification for Social Discount (25 Jan, 2016)
                                  $coupon_amount = Session::get('coupon_discount');
                                 
                                }
                              } // else share coupon status end 
                              elseif(($share_discount>0) && (Session::get('share_coupon_status')==0))
                              {
                                // Show  social discount
                                // Social Discount (25 Jan, 2016): Calculate social discount in percentage not in absolute amount
                                $social_discount = ($all_sub_total * $share_discount)/100;
                                $all_total = $all_sub_total - $social_discount;
                                
                              } // else share coupon status end 
                            
                           }
                           else // If there is no Coupon Discount
                           {
                              if($share_discount>0)
                              {
                                $social_discount = ($all_sub_total * $share_discount)/100;
                                $all_total = $all_sub_total - $social_discount;
                                
                              }
                              else
                              {
                                $all_total = $all_sub_total;
                                //$social_discount = $share_discount;
                              }
                              
                           }
                           
                           //for redeemption
                           if(isset($cartcontent->redeem_amount) && $cartcontent->redeem_amount>0){
                              $all_total=$all_total-$cartcontent->redeem_amount;
                           }
                            
                          } // empty cart if end


                          // Whole Orders
                          // If the brand member is eligible for a discount then remove reduce the exacty same amount 
                          // from checkout, so now he will pay only


                        ?>
                        
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="7">
                            <a href="<?php echo url();?>" class="butt pull-left">Continue Shopping</a>
                          </td>
                        </tr>
                      </tfoot>
                    </table>


                  </div>
                  <?php if(isset($is_eligible_for_wholesale_discount) && $is_eligible_for_wholesale_discount == 1) { ?>
                  <h5><font style="color:#f00;">*</font> Adjustment is applicable only for wholesale products 
                  wherein you are not required to pay the wholesale amount and you will be receiving a customized 
                  rate from Miramix Admin in some time.</h5>

                  <?php } ?>
                </div>
                <!-- div for mobile view only starts here -->
                <div class="col-sm-9 clearfix hidden-lg hidden-md hidden-sm hidden-lg">
                  <div class="table-responsive shad_tabres hidefromtabdown" id="order-detail-content">
                    <table class="table table-cart" id="cart_summary">
                        <thead>
                          <?php
                            $all_sub_total =0.00;
                            $all_total =0.00;
                            $coupon_amount = 0.00;
                            $social_discount = 0.00;
                            $provisional_wholesale_adjustment = 0;



                            if(!empty($cart_result))
                            { 
                              $i=1;
                              $quantity = 0;
                              foreach($cart_result as $eachcart) {
                                $quantity = $eachcart['qty'];
                                $quan = $eachcart['qty'];
                                $all_sub_total = $all_sub_total+$eachcart['subtotal'];
                                // $all_sub_total = number_format($all_sub_total,2);
                                //$share_discount = $share_discount + $eachcart['share_discount'];
                          ?>              
                        </thead>
                        <tbody>
                      
                          <tr>
                           
                            <td class="cart_product">
                              <a href="<?php echo url();?>/product-details/{!! $eachcart['product_slug'] !!}"><img src="<?php echo url();?>/uploads/product/{!! $eachcart['product_image'] !!}" width="116" alt=""></a>
                            </td>
                          

                            <div class="row mobo_view">
                              <div class="col-xs-5">
                                <td class="order_descrip">
                                  <a href="<?php echo url();?>/product-details/{!! $eachcart['product_slug'] !!}">{!! ucwords($eachcart['product_name']) !!}</a><br />
                                  {!! $eachcart['duration'] !!}<br />
                                  {!! $eachcart['formfactor_name'] !!}<br />

                                  <?php 
                                    if(isset($eachcart['is_wholesale']) && $eachcart['is_wholesale'] == 1) { 
                                      $iswh = 1;
                                  ?>

                                      <font color="#ff0000">Eligible for wholesale rate</font>
                                  <?php 
                                    } else {
                                      $iswh = 0;
                                    }
                                  ?>
                                </td>
                              </div>

                              <div class="col-xs-5">
                                <td class="cart_bran" data-title="Brand">
                                  <a href="<?php echo url();?>/brand-details/{!! $eachcart['brand_slug'] !!}">{!! $eachcart['brand_name'] !!}</a>
                                </td>
                              </div>

                              <div class="col-xs-2">
                                <td class="delete_pro">
                                  <a href="javascript:void(0);" onclick="deleteCart('<?php echo $eachcart['rowid'];?>','<?php echo $eachcart['brand_id'];?>')" class="btn-link del_link">
                                    <img src="<?php echo url();?>/public/frontend/images/proddetails/dele_cart.png" alt="">
                                  </a>
                                </td>
                              </div>
                            </div>
                           
                            <td class="cart_quantity" data-title="Quantity" id="cartitemid<?php echo $i;?>"><?php echo $quantity; ?><button class="fa fa-refresh" id="refreshIt<?php echo $i;?>" onclick="showOrHideIt('#showUpdateDiv<?php echo $i;?>')"></button></td>
                            

                           
                            <td class="cart_unit" data-title="Unit Price">${!! number_format($eachcart['price'],2) !!}</td>
                           
                           
                            <td class="cart_total" data-title="Total">${!! number_format($eachcart['subtotal'],2) !!}</td>
                           
                          </tr>
                          <tr id="showUpdateDiv<?php echo $i;?>" style="display:none;">
                            <td class="cart_quantity" id="cart_quan">
                              <div class="input-group bootstrap-touchspin pull-left col-sm-8">
                                <input type="text" value="<?php echo $eachcart['qty']; ?>" id="newcart<?php echo $i;?>" name="demo1" class="form-control demo1">
                                <input type="hidden" id="iswholesale<?php echo $i;?>" value="<?php echo $iswh ?>" />
                              </div>
                              <a href="javascript:void(0);" class="refresh_btn" onclick="updateMobCart('<?php echo $eachcart['rowid'];?>','<?php echo $i;?>')">
                                <i class="fa fa-refresh"></i>
                              </a>
                            </td>
                          </tr>
                          <?php 
                              $i++;

                              //$share_discount = $share_discount+$eachcart['share_discount'];
                            }

                              //echo "o= ".$share_discount;
                              /*---------------------*/

                              // NOTE: 25-Jan-2015: Social Share discount percentage is stored inside $share_discount whereas
                              // the value of discount is stored inside $social_discount 

                            if(isset($is_eligible_for_wholesale_discount) && $is_eligible_for_wholesale_discount == 1 && isset($brand_tot_wholesale_amount) && $brand_tot_wholesale_amount > 0) {
                              $all_sub_total -= $brand_tot_wholesale_amount;
                              //$all_total -= $brand_tot_wholesale_amount;
                            }
                            //dd($all_sub_total);

                            if(Session::has('coupon_discount')) {
                              if(($share_discount==0) || ($share_discount=='')) {
                                if(Session::get('coupon_type')=='P') {
                                  $dis_percent = Session::get('coupon_discount');

                                  $dis_amnt = ($dis_percent/100) * $all_sub_total;
                                  $net_amnt = $all_sub_total - $dis_amnt;
                                  if($net_amnt<0)
                                    $all_total = $all_sub_total;
                                  else
                                    $all_total = $net_amnt;
                                    $coupon_amount = $dis_amnt;
                                } else {
                                  $all_total = $all_sub_total - Session::get('coupon_discount');
                                  $coupon_amount = Session::get('coupon_discount');
                                }
                              } elseif(($share_discount>0) && (Session::get('share_coupon_status')==1)) { // share coupon status if end
                                // Show coupon+ social
                                if(Session::get('coupon_type')=='P') {
                                  $dis_percent = Session::get('coupon_discount');

                                  $dis_amnt = ($dis_percent/100) * $all_sub_total;

                                  // Social Discount (25 Jan, 2016): Calculate social discount in percentage not in absolute amount
                                  $net_amnt1 = $all_sub_total - $dis_amnt;
                                  $social_discount = ($net_amnt1 * $share_discount)/100; // In absolute amount
                                  $net_amnt = $net_amnt1 - $social_discount;
                                  // End of modification for Social Discount (25 Jan, 2016)

                                  if($net_amnt<0)
                                    $all_total = $all_sub_total;
                                  else
                                    $all_total = $net_amnt;

                                  $coupon_amount = $dis_amnt;

                                } else {
                                // Social Discount (25 Jan, 2016): Calculate social discount in percentage not in absolute amount

                                  $all_total = $all_sub_total - Session::get('coupon_discount');
                                  $social_discount = ($all_total * $share_discount) / 100;
                                  $all_total = $all_total - $social_discount;
                                  // End of modification for Social Discount (25 Jan, 2016)
                                  $coupon_amount = Session::get('coupon_discount');

                                }
                              } elseif(($share_discount>0) && (Session::get('share_coupon_status')==0)) { // else share coupon status end
                                // Show  social discount
                                // Social Discount (25 Jan, 2016): Calculate social discount in percentage not in absolute amount
                                $social_discount = ($all_sub_total * $share_discount)/100;
                                $all_total = $all_sub_total - $social_discount;

                              } // else share coupon status end 
                            } else { // If there is no Coupon Discount
                                if($share_discount>0) {
                                  $social_discount = ($all_sub_total * $share_discount)/100;
                                  $all_total = $all_sub_total - $social_discount;
                                } else {
                                  $all_total = $all_sub_total;
                                  //$social_discount = $share_discount;
                                }
                              }

                              //for redeemption
                              if(isset($cartcontent->redeem_amount) && $cartcontent->redeem_amount>0){
                                $all_total=$all_total-$cartcontent->redeem_amount;
                              }

                            } // empty cart if end


                            // Whole Orders
                            // If the brand member is eligible for a discount then remove reduce the exacty same amount 
                            // from checkout, so now he will pay only
                          ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="7">
                              <a class="butt pull-left cont_btn" href="<?php echo url();?>">Continue Shopping</a>
                            </td>
                          </tr>
                        </tfoot>
                    </table>
                  </div><!--/End Edited for mobo view  -->
                </div>
                <!-- div for mobile view only end here -->
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="row">

                    <div class="right_table" id="mini_cart">
                      <div class="table-responsive">
                       {!! Form::open(['url' => 'coupon-cart','method'=>'POST', 'files'=>true, 'id'=>'coupon_form']) !!}
                        <table class="table" id="table_load">
                          <tbody>
                            <tr>
                              <td>Sub Total:</td>
                              <td style="text-align:right">{!! ($all_sub_total!='')?'$':'' !!}{!! number_format($all_sub_total,2); !!}</td>
                            </tr>



                            <?php if(isset($is_eligible_for_wholesale_discount) && $is_eligible_for_wholesale_discount == 1) { ?>

                            <tr>
                              <td>Adjustment <font color="red">*</font>:</td>
                              <td style="text-align:right">{!! ($all_sub_total!='')?'(-) $':'' !!}{!! number_format($brand_tot_wholesale_amount,2); !!}</td>
                            </tr>

                            <?php } ?>

                            

                            
                            <?php if(isset($cartcontent->redeem_amount) &&  $cartcontent->redeem_amount>0){ ?>
                            <tr>
                              <td>Redeem Discount:</td>
                              <td style="text-align:right"><?php echo '- $'.number_format($cartcontent->redeem_amount,2);?></td>
                            </tr>
                            <?php }?>
                            

                            <!--<?php if(isset($cartcontent->redeem_amount) &&  $cartcontent->redeem_amount>0){ ?>
                            <tr>
                              <td>Redeem Discount:</td>
                              <td><?php echo '- $'.number_format($cartcontent->redeem_amount,2);?></td>
                            </tr>
                            <?php }?>-->

                            <?php if($share_discount > 0 ){ ?>
                            <tr>
                              <td>Social Discount (@<?php echo $share_discount."%" ?>):</td>
                              <td style="text-align:right"><?php 
                              // Social Share Perc: Changing from absolute to percentage
                              echo '- $'.number_format($social_discount, 2) ?>
                              </td>
                            </tr>
                            <?php } ?>

                            <?php 
                            if($share_discount == 0)
                            {
                              if(Session::has('coupon_discount') && Cart::count() > 0 ){ ?>
                              <tr>
                                <td>Coupon Discount:</td>
                                <td style="text-align:right"><?php echo '- $'.number_format($coupon_amount,2);?></td>
                              </tr>
                              <?php } 
                            }
                            elseif(($share_discount > 0) && (Session::get('share_coupon_status')==1))
                            {
                            ?>
                              <tr>
                                <td>Coupon Discount:</td>
                                <td style="text-align:right"><?php echo '- $'.number_format($coupon_amount,2);?></td>
                              </tr>
                            <?php 
                            }
                            ?>

                            <tr>
                              <td>Total:</td>
                              <td style="text-align:right">{!! ($all_total!='')?'$':'' !!}{!! number_format($all_total,2); !!}</td>
                            </tr>

                            <tr>
                              <td colspan="2" class="special-pad" align="center">Apply Coupon: </td>
                            </tr>
                            <tr>
                              <td colspan="2" class="special-pad no-bord" align="center"><div class="couponcode_apply"><input type="text" name="coupon_code" id="coupon_code" class="coupon_code" value="<?php if(Session::has('coupon_code') && Cart::count() > 0) { echo Session::get('coupon_code'); } ?>"><button type="submit" name="sub" id="sub_coupon" class="sub_coupon">Apply</button></div></td>
                            </tr>
                          </tbody>
                        </table>

                        {!! Form::close() !!}
                        <?php 
                        if(isset($cartcontent->redeem_amount) && $cartcontent->redeem_amount>0){
                          $deducted_point=$cartcontent->redeem_amount * 100;
                       }
                       else
                       {
                          $deducted_point=0;
                       }
                       ?>

                        {!! Form::open(['url' => 'redeem-cart','method'=>'POST', 'files'=>true, 'id'=>'redeem_form']) !!}
                        <table class="table">
                          <tbody>
                               <?php if(Session::has('member_userid')){
                               if(isset($member->user_points) && $member->user_points>0){
                               ?> 
                              <tr>
                              
                              <td colspan="2" class="special-pad" align="center">Redeem Points: (You have: <?php echo $member->user_points - $deducted_point;?> Points) </td>
                            </tr>
                            <tr>
                              <td colspan="2" class="special-pad no-bord" align="center"><div class="couponcode_apply"><input type="number" name="user_points" id="user_points" class="coupon_code" value="" min="<?php echo $redemctrl['min']?>" max="<?php echo $redemctrl['max']?>" step="<?php echo $redemctrl['step']?>"><button type="submit" name="redeem" id="sub_coupon" class="sub_coupon">Redeem</button></div></td>
                            </tr>
                              
                              <?php }}?>
                        
                        </tbody>
                        </table>
                        {!! Form::close() !!}
                        
                        
                      </div>
                    </div>
                    <?php //echo (Session::get('force_social_share'));
                      if((($share_discount==0) || ($share_discount==''))&&(Session::get('force_social_share')=='')) {
                    ?>
                        <div class="social-share-checkout" id="social_share_hide" style="display:none;">
                          <p class="social_share"><strong>Thanks for sharing. Your discount has been applied to this purchase.</strong></p>
                        </div>
                        <div class="social-share-checkout" id="social_share_show" style="display:block;">
                          <?php if(isset($all_sitesetting['discount_share']) && $all_sitesetting['discount_share'] > 0) { ?>
                            <p class="social_share"><strong>Share for a {!! $all_sitesetting['discount_share'] !!}% credit on your entire order :</strong></p>
                          <?php } ?>
                            <ul class="social_plug_new new-cart-social">
                            <li class="fb_li"><a href="javascript:void(0);" onclick="fb_share('<?php echo ucwords($all_sitesetting['FromName']);?>','<?php echo url().'/social-content';?>','<?php echo "social_share";?>','<?php echo trim(preg_replace("/\s+/"," ",$all_sitesetting['share_content']));?>','<?php echo url();?>/uploads/share_image/{!! $all_sitesetting['share_image'] !!}')";><i class="fa fa-facebook"></i></a></li>
                          
                            <li><g:plusone size="medium" href="<?php echo url().'/social-content';?>" onendinteraction="onPlusDone"></g:plusone></li>
                            </ul>
                        </div>

                    <?php
                      }
                    ?> 

                    <?php if(Cart::count()>0){ ?>
                      <a class="butt full-disp" href="<?php echo url();?>/checkout" id="proceed_check_btn">Proceed to Checkout</a>
                    <?php } ?>
                  </div>
                </div>


               <?php } else {?>
               <div class="col-xs-12 text-center noprod_cart">
                 <img src="<?php echo url();?>/public/frontend/images/nocart_prod.png" alt="">
                 <p class="empt_shpcart">Your shopping cart is empty</p>
                 <a href="<?php echo url();?>" class="butt">Continue Shopping</a>
               </div>
               <?php } ?>
              </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="<?php echo url();?>/public/frontend/js/bootstrap.touchspin.js"></script>
  <script>
  $(document).ready(function(e) {

    $(document).on('click','.refresh_btn',function(){
     // $(this).parent().find(".demo1").val(0);
    });
    $(document).on('click','.del_link',function(){
      var $this=$(this);
      $this.closest('tr').remove();
    }); 
  
    $("input[name='demo1']").TouchSpin({
        min: 1,
        max: 10000,
        boostat: 5,
        maxboostedstep: 10
    });
    
    $("#user_points").ForceNumericOnly();
  });

  function showOrHideIt(divId){
    $(divId).css("display","block");
  }

/*------------ UPDATE CART THROUGH AJAX START -----------------*/
  function updateCart(rowid,fieldid)
  {
    console.log("rowid "+rowid+" fieldid "+fieldid);
    var rowid = rowid;
    var quantity = $("#cart"+fieldid).val();
    var is_wholesale = $("#iswholesale"+fieldid).val();

    //alert("Is Wholesale: "+is_wholesale)
    $.ajax({
        url: '<?php echo url();?>/updateCart',
        type: "post",
        data: { rowid : rowid , quantity : quantity , is_wholesale : is_wholesale ,_token: '{!! csrf_token() !!}'},
        success:function(data)
        {
       // alert(data);
          if(data !='' ) 
          {
            window.location.href = "<?php echo url()?>/show-cart";
          }
        }
      });
  }
/*------------ UPDATE CART THROUGH AJAX END -----------------*/

/*------------ UPDATE MOBILE VIEW CART THROUGH AJAX START -----------------*/
  function updateMobCart(rowid,fieldid)
  {
    console.log("rowid "+rowid+" fieldid "+fieldid);
    var rowid = rowid;
    var quantity = $("#newcart"+fieldid).val();
    var is_wholesale = $("#isWholeSale"+fieldid).val();
    console.log(quantity +" "+is_wholesale);
    
    $.ajax({
        url: '<?php echo url();?>/updateCart',
        type: "post",
        data: { rowid : rowid , quantity : quantity , is_wholesale : is_wholesale ,_token: '{!! csrf_token() !!}'},
        success:function(data)
        {
       // alert(data);
          if(data !='' ) 
          {
            window.location.href = "<?php echo url()?>/show-cart";
          }
        }
      });
  }
/*------------ UPDATE CART THROUGH AJAX END -----------------*/

/*-----------------  DALETE CART THROUGH AJAX START AND CACULATE COUPON PRODUCT --------------*/
  function deleteCart(rowid,brand_id)
  {
    var rowid = rowid;

    $.ajax({
        url: '<?php echo url();?>/deleteCart',
        type: "post",
        data: { rowid : rowid ,_token: '{!! csrf_token() !!}',brand_id:brand_id},
        success:function(data)
        {
       
          if(data !='' ) 
          {

            //alert(data);
            window.location.href = "<?php echo url()?>/show-cart";
          }
        }
      });
  }
/*-----------------  DALETE CART THROUGH AJAX END --------------*/
   
   jQuery.fn.ForceNumericOnly =
function()
{
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (
                key == 8 || 
                key == 9 ||
                key == 13 ||
                key == 46 ||
                key == 110 ||
                key == 190 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));
        });
    });
};

</script>

<!-------------------- for google Call back Start  ---------------------->

<script type="text/javascript">
     function onPlusDone(reponse) {
          //console.log("Responses: "+reponse);
          //alert("Google Plus "+reponse.id)
          //alert("Plus "+reponse.type)

          if(reponse.type == "confirm") {
            //alert("Inside process")            
            plusone_vote();
          }
          
      }
      

      function plusone_vote() {
          //alert("Inside plus one");

          $.ajax({
            url: '<?php echo url();?>/saveShare',
            type: "post",
            data: { product_id : "social_share" ,_token: '{!! csrf_token() !!}'},
            success : handleData
          
          });      

      }

      function handleData(data) {
       //alert(data);

        $("#mini_cart").load(location.href + " #table_load");
        $("#social_share_show").hide();
        $("#social_share_hide").show();
    }

</script>

<!--INNER PAGE CONTENT END -->

<script>
function fb_share(product_name,url,product_id,product_des,product_image) {
  FB.ui(
  {
  method: 'feed',
  name: product_name,
  href: url,
  link:url,
  product_id: product_id,
  picture: product_image,
  description:product_des
  },
  
  function(response) {

    //if (response && !response.error_code) 
    //if (response && response.post_id)
    if(typeof(response) !== "undefined" && typeof(response) !== undefined && response)
    {
      $.ajax({
        url: '<?php echo url();?>/saveShare',
        type: "post",
        data: { product_id : product_id ,_token: '{!! csrf_token() !!}'},
        success:function(data)
        {
          // hide and show share button
          $("#mini_cart").load(location.href + " #table_load");

          $("#social_share_show").hide();

          $("#social_share_hide").show();
        }
      
      });
    
    }
  });

}
</script>

<!--------Google Share -------->

<!--<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script> --->

<!--------Google Share -------->
<script >
  window.___gcfg = {
  
    parsetags: 'onload'
  };
</script>
<script src="https://apis.google.com/js/api:client.js"></script>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<meta name="google-signin-client_id" content="<?php echo env('GOOGLE_CLIENT_ID')?>">

<script>

  function attachSignin(element) {
      
       auth2.attachClickHandler(element, {},
     function(googleUser) {
       
    $.post( "<?php echo url();?>/saveShare",{_token:'{!! csrf_token() !!}',email:googleUser.getBasicProfile().getEmail(),product_id:18} , function(response) {
      //window.location.href=response;
      //console.log("Response: "+response);
           // $("#sharegoogle").css('opacity', '1');
           renderPlusone();
           // hide share button and load mini_cart div
            $("#signin").hide();

            $("#mini_cart").load(location.href + " #table_load");

            $("#social_share_show").hide();

            $("#social_share_hide").show();
     });
     }, function(error) {
      
     });
     }
     
var googleUser = {};
  var startApp = function() {
    gapi.load('auth2', function(){
      // Retrieve the singleton for the GoogleAuth library and set up the client.
      auth2 = gapi.auth2.init({
        client_id: '<?php echo env('GOOGLE_CLIENT_ID')?>',
        cookiepolicy: 'single_host_origin',
         
      });
      attachSignin(document.getElementById('signin'));
      
      
     /* gapi.auth.checkSessionState({session_state: null}, function(isUserNotLoggedIn){
                  if (isUserNotLoggedIn) {
                       
                        $("#signin").show();
                  }else{
                     renderPlusone();    
                    $("#signin").hide();    
                  }
              });
      */
    });
  };
startApp();


  function renderPlusone() {
        gapi.plusone.render("plusone-div");
      }
</script>


<!--------Google Share -------->



<!--------Google Share -------->

 @stop
