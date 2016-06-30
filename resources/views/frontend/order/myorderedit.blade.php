@extends('frontend/layout/frontend_template')
@section('content')

<script src="<?php echo url();?>/public/frontend/js/stacktable.js"></script>
<link href="<?php echo url();?>/public/frontend/css/stacktable.css" rel="stylesheet">

  <div class="inner_page_container nomar_bottom" ng-app="updateStatus">
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

                  {!! Form::open(['url' => 'orders/myorderedit','method'=>'POST', 'files'=>true,  'id'=>'myorder_edit']) !!}
                      {!! Form::hidden('order_id',$orders[0]->id,['id'=>'order_id','class'=>'span8'])!!}
                      <div class="bottom_dash special_bottom_pad clearfix">                      
                        <div class="row"> 
                          <div class="form-group col-sm-6" id="path" style="padding-top:10px;">
                            <select name="order_status" id="order_status" class="form-control valid">
                                <option value="">Select Status</option>                                
                                <option value="pending" <?php if($orders[0]->order_status=='pending'){ echo 'selected="selected"';}?>>Pending</option>
                                <option value="processing" <?php if($orders[0]->order_status=='processing'){ echo 'selected="selected"';}?>>Processing</option>  
                                <option value="fraud" <?php if($orders[0]->order_status=='fraud'){ echo 'selected="selected"';}?>>Fraud</option>
                                <option value="prepare shipment" <?php if($orders[0]->order_status=='prepare shipment'){ echo 'selected="selected"';}?>>Prepare Shipment</option>  
                                <option value="shipped" <?php if($orders[0]->order_status=='shipped'){ echo 'selected="selected"';}?>>Shipped</option>
                                <option value="completed" <?php if($orders[0]->order_status=='completed'){ echo 'selected="selected"';}?>>Completed</option>
                                <option value="cancel" <?php if($orders[0]->order_status=='cancel'){ echo 'selected="selected"';}?>>Cancel</option>  
                            </select>
                          </div> 
                          <?php 
                            $dis_status="none";
                            if($orders[0]->order_status=='shipped')
                            { 
                              $dis_status="block";
                            }
                          ?>
                          <div class="col-sm-12" style="display:<?php echo $dis_status; ?>" id="tracking"   >
                            <div class="row">
                              <div class="form-group col-sm-6">                                
                                <div class="upload_button_panel">
                                    <p class="upload_image">
                                    {!! Form::text('tracking_number',$orders[0]->tracking_number,['id'=>'tracking_number','placeholder'=>'Tracking Number','class'=>'form-control'])!!}
                                </div>
                              </div>
                            </div>  
                          </div> 

                          <div class="col-sm-12" style="display:<?php echo $dis_status; ?>" id="shipping_carrier" >
                            <div class="row">
                              <div class="form-group col-sm-6">                                
                                <div class="upload_button_panel">
                                    <p class="upload_image">
                                    {!! Form::text('shipping_carrier',$orders[0]->shipping_carrier,['id'=>'shipping_carrier','placeholder'=>'Shipping Carrier','class'=>'form-control'])!!}
                                </div>
                              </div>
                            </div>  
                          </div> 

                        </div>                        
                      </div>                    
                      <div class="form_bottom_panel">
                        <a href="<?php echo url();?>/brand-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>
                        {!! Form::submit('Update', ['class' => 'btn btn-default green_sub pull-right']) !!}
                      </div>
                    {!! Form::close() !!}
                     
                    
                           
                    </div>                    
                   
               </div>
               
               </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
 </div>  


 <script>
 $("#order_status").change(function(){
  var stat=$(this).val();  
  if (stat=='shipped'){
    $("#tracking").show();
    $("#shipping_carrier").show('500');
  }else{
     $("#tracking").hide();
     $("#shipping_carrier").hide('500');
  }
  
  });
      
</script>
 @stop