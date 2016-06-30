@extends('frontend/layout/frontend_template')
@section('content')

<div class="header_section nomar_bottom">
    	   <!--my_acct_sec-->
           <div class="my_acct_sec">           
               <div class="container">
               <h3 class="text-center"><?php
	       if (is_object($brand_details)){
	       echo ($brand_details->business_name !='')?ucfirst($brand_details->business_name):$brand_details->username?> 's account
	       <?php }?>
	       </h3>
               <h5 class="text-center">WELCOME TO YOUR ACCOUNT</h5>
               @if($check_offer >0)
               <div class="col-sm-12">
                    <div class="alert alert-danger fade in">
                        <a href="#" class="close" data-dismiss="danger">&times;</a>
                        <strong>Please visit your <a href="orders/myorder" >Order History</a> to get new price offer.</strong> 
                    </div>
               </div>
               @endif
               <div class="col-sm-12">
               
                <div class="acct_box yellow_act">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/sold-products">
			<img src="<?php echo url();?>/public/frontend/images/account/sold_products.png" alt="">
			</a>
                        <a href="<?php echo url();?>/sold-products" class="link_wholediv">Sold Products History</a>
                        </div>                    	
                    </div>
                </div>
                
                <div class="acct_box red_acct">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/product/create"><img src="<?php echo url();?>/public/frontend/images/account/add_products.png" alt=""></a>
                        <a href="<?php echo url();?>/product/create" class="link_wholediv">Add Products</a>
                        </div>                    	
                    </div>
                </div>
                
                <div class="acct_box org_org_acct no_marg">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/my-products"><img src="<?php echo url();?>/public/frontend/images/account/productlist.png" alt=""></a>
                        <a href="<?php echo url();?>/my-products" class="link_wholediv">Product List</a>
                        </div>                    	
                    </div>
                </div>
                
                <div class="acct_box new_green_acct no_marg pull-right">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                         <a href="<?php echo url();?>/orders/list"><img src="<?php echo url();?>/public/frontend/images/account/order_hist.png" alt=""></a>
                        <a href="<?php echo url();?>/orders/list" class="link_wholediv">Order History</a>
                        </div>                    	
                    </div>
                </div>
                
                <div class="acct_box blue_acct front">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/brand-account"><img src="<?php echo url();?>/public/frontend/images/account/pers_info.png" alt=""></a>
                        <a href="<?php echo url();?>/brand-account" class="link_wholediv">Brand Information</a>
                        </div>                    	
                    </div>
                </div>
                
                <!--<div class="acct_box green_acct">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/change-password"><img src="<?php echo url();?>/public/frontend/images/account/changepassword.png" alt=""></a>
                        <a href="<?php echo url();?>/change-password">Change Password</a>
                        </div>                    	
                    </div>
                </div>-->
                
                <div class="acct_box violet_acct front">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/brand-shipping-address">
			<img src="<?php echo url();?>/public/frontend/images/account/address.png" alt="">
			</a>
                        <a href="<?php echo url();?>/brand-shipping-address" class="link_wholediv">My Address</a>
                        </div>                    	
                    </div>
                </div>
                
               <!-- <div class="acct_box orange_acct no_marg pull-right">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <img src="<?php echo url();?>/public/frontend/images/account/store.png" alt="">
                        <a href="javascript:void(0);">Store Font<span>Coming Soon</span></a>
                        </div>                    	
                    </div>
                </div>-->
		    
		    
		<div class="acct_box blue_acct front">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/brand-creditcards"><i class="fa fa-credit-card fa-2x"></i></a>
                        <a href="<?php echo url();?>/brand-creditcards" class="link_wholediv">Credit Card Details</a>
                        </div>                    	
                    </div>
                </div>
		    
		    
		<div class="acct_box blue_acct no_marg">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/brand-paydetails"><i class="fa fa-cc-paypal fa-2x"></i></a>
                        <a href="<?php echo url();?>/brand-paydetails" class="link_wholediv">Payment Details</a>
                        </div>                    	
                    </div>
                </div>
		    
		<div class="acct_box org_org_acct front">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/subscription-history"><img src="<?php echo url();?>/public/frontend/images/account/productlist.png" alt=""></a>
                        <a href="<?php echo url();?>/subscription-history" class="link_wholediv">Subscription History</a>
                        </div>                    	
                    </div>
                </div>
		    
		<div class="acct_box blue_acct front">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="#"><i class="fa fa-credit-card fa-2x"></i></a>
                        <a href="<?php echo url();?>/orders/myorder" class="link_wholediv">Wholesale <span>Coming Soon</span></a>
                        </div>                    	
                    </div>
                </div>
                
                <div class="acct_box red_acct">
                    <div class="acct_box_inn">
                        <div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/brandcoupons"><img src="<?php echo url();?>/public/frontend/images/account/add_products.png" alt=""></a>
                        <a href="<?php echo url();?>/brandcoupons" class="link_wholediv">Coupons</a>
                        </div>                      
                    </div>
                </div>
                <div class="acct_box new_green_acct no_marg pull-right">
                    <div class="acct_box_inn">
                        <div class="acct_box_inn_inn">
                         <a href="<?php echo url();?>/file"><img src="<?php echo url();?>/public/frontend/images/account/order_hist.png" alt=""></a>
                        <a href="<?php echo url();?>/file" class="link_wholediv">File Management</a>
                        </div>                      
                    </div>
                </div>
            
               </div>
               </div>           
           </div>
           <!--my_acct_sec ends-->
 </div>

 @stop