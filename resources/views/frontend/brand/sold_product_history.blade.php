@extends('frontend/layout/frontend_template')
@section('content')
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
               <div class="container">
               
               <div class="col-sm-10 col-sm-offset-1">
               
               <div class="row">
               <div class="form_dashboardacct">
               		<h3>Sold Product History</h3>
                    
                    <div class="table-responsive">
                    <table class="table special_height">
                    <thead>
                      <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Sold Qty</th>
                        <th>Total Amount</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                     
                     <?php foreach($products as $product){?>
                      <tr>
                        <td>#<?php echo $product->id ?></td>
                        <td><?php echo $product->product_name ?></td>
                        <td><?php echo (int)$product->sale_qty ?></td>
                        <td>$<?php echo (float)$product->total_sale ?></td>
                       
                      </tr>
                     <?php }?> 
                      
                    </tbody>
                  </table>
                  </div>
                    <div><?php echo $products->render() ?></div>
                    <div class="form_bottom_panel">
                    <!--<a href="<?php echo url();?>/brand-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>-->
                   
                    </div>
                    
               </div>
               
               </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
 </div>


 @stop
 