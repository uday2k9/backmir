@extends('frontend/layout/frontend_template')
@section('content')

<script src="<?php echo url();?>/public/frontend/js/stacktable.js"></script>
<link href="<?php echo url();?>/public/frontend/css/stacktable.css" rel="stylesheet">

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
               
               <div class="row"><div class="form_dashboardacct">
                  <h3>Subscription History</h3>
                    
                    <div class="table-responsive">
                    <table class="table special_height" id="subscribe_table">
                    <thead>
                      <tr>
                        <th>Subscription ID</th>
                        <th>Pay Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Amount</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                     
                     <?php foreach($subscription as $sub){?>
                      <tr>
                        <td>#<?php echo $sub->subscription_id ?></td>
                        <td><?php echo $sub->payment_status ?></td>
                        <td><?php echo date('m/d/Y',strtotime($sub->start_date)); ?></td>
                        <td><?php echo date('m/d/Y',strtotime($sub->end_date)); ?></td>
      <td>$<?php echo $sub->subscription_fee+$sub->other_fee ?></td>
                      </tr>
                     <?php }?> 
                      
                    </tbody>
                  </table>
                  </div>
                    <div><?php echo $subscription->render() ?></div>
          <h5 class="subs_head">Subscription Status : <strong><?php echo $brand->subscription_status?></strong></h5>
                    <div class="form_bottom_panel">
                    <!--<a href="<?php echo url();?>/brand-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>-->
                   
                    </div>
                    
               </div>
               
               </div>
               
               </div>
               
               </div> 
               <div class="container">
                <div class="total_mirslider"><div id="slider" class="miramix-slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" style="margin:50px"><div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min" style="width: 0%;"></div><span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 0%;"><div class="tooltip"><div class="tooltip-header"></div><div class="tooltip-content"><h3 id="employee-amount-header">0 product(s)</h3><ul><li class="enterprise-pricing hidden">Contact us for enterprise pricing</li><li class="small-pricing undefined">$<span id="base-cost">19</span> base price</li><li class="small-pricing undefined">$<span id="person-cost">4</span>/product x <span id="base-employees">0 product(s)</span>=$<span id="totalval">19</span></li></ul></div></div></span></div></div>
            </div>          
           </div>
           <!--my_acct_sec ends-->
 </div>
<script>
$(document).ready(function(e) {
    $('#subscribe_table').stacktable({myClass:'brand-table-section'}); 
});
</script>

 @stop