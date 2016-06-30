@extends('frontend/layout/frontend_template')
@section('content')

<div class="header_section nomar_bottom">
    	   <!--my_acct_sec-->
           <div class="my_acct_sec mydash_two">           
               <div class="container">
               <h3 class="text-center">
               <?php 
               if (is_object($member_details))
               {
                echo ($member_details->fname !='')?ucfirst($member_details->fname):$member_details->username?> 's account
               <?php } ?>
                </h3>
               <h5 class="text-center">WELCOME TO YOUR ACCOUNT</h5>
               
               <div class="col-sm-12">
               
                <div class="row">
                
                <div class="col-sm-8">
                <div class="acct_box blue_acct front">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/member-account"><img src="<?php echo url();?>/public/frontend/images/account/pers_info.png" alt=""></a>
                        <a href="<?php echo url();?>/member-account" class="link_wholediv">Personal Information</a>
                        </div>                    	
                    </div>
                </div>
                
                
               
                <div class="acct_box green_acct no_marg  pull-right">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/member-changepass"><img src="<?php echo url();?>/public/frontend/images/account/changepassword.png" alt=""></a>
                     
			<a href="<?php echo url();?>/member-changepass" class="link_wholediv">Change Password</a>
			
                        </div>                    	
                    </div>
                </div>
		
                
                <div class="acct_box violet_acct">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/member-shipping-address"><img src="<?php echo url();?>/public/frontend/images/account/address.png" alt=""></a>
                        <a href="<?php echo url();?>/member-shipping-address" class="link_wholediv">My Address</a>
                        </div>                    	
                    </div>
                </div>
                
                <div class="acct_box orange_acct wish_acct no_marg pull-right">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <img src="<?php echo url();?>/public/frontend/images/account/wishlist_icn.png" alt="">
                        <a href="">My Wishlist<span>Coming Soon</span></a>
                        </div>                    	
                    </div>
                </div>
                </div>
                
                <div class="col-sm-4 right_acct_box">
                <div class="acct_box new_green_acct no_marg pull-right">
                	<div class="acct_box_inn">
                    	<div class="acct_box_inn_inn">
                        <a href="<?php echo url();?>/order-history"><img src="<?php echo url();?>/public/frontend/images/account/order_hist.png" alt=""></a>
                        <a href="<?php echo url();?>/order-history" class="link_wholediv">Order History</a>
                      </div>                    	
                    </div>
                </div>
                </div>
                
                </div>
                
                
                
                
                
                
                
                
                
                <!-- <div class="btn-block text-center"><a href="" class="btn btn-white">Subscription History</a></div> -->
               </div>
               </div>           
           </div>
           <!--my_acct_sec ends-->
 </div>

 @stop