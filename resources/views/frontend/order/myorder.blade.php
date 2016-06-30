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
                     
                    <div class="table-responsive">
                    <input type="hidden" name="site_base_url" id="site_base_url" value="<?php echo url();?>" />
                    <table class="table special_height">
                    <thead>
                      <tr>
                        <th>Order Id</th>
                        <th>Price($)</th>                    
                        <th>Status</th>                        
                        <th>Order Date</th>                        
                        <th>View</th>                 
                      </tr>
                    </thead>
                    <tbody>                      
                    @if($orders->count() >0 )
                    @foreach($orders as $order)                       
                      <tr>
                        <td>
                          {{ $order->order_number }}
                        </td>
                        <td>{{ number_format($order->sub_total,2) }}</td>                        
                        <td>{{ $order->order_status }}</td>  
                        <td>{{ Carbon\Carbon::parse($order->updated_at)->format('M/d/Y') }}</td>                              
                        <td><a href="<?php echo url();?>/orders/myorderdetails/{{ $order->id }}" class="btn btn-success">Details</a></td>                             
                      </tr>
                    @endforeach  
                    @else
                     <tr>
                        <td colspan="6">No data available in table</td>                        
                      </tr>
                     @endif
                      
                    </tbody>
                  </table>
                  </div>
                    <div><?php echo $orders->render() ?></div>
                    <div class="form_bottom_panel">
                    <a href="<?php echo url();?>/brand-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>                 
                    <a href="<?php echo url();?>/orders/list" class="green_sub text-center pull-right"><i class="fa fa-shopping-cart"></i>Customer Orders</a> 
                    </div>
               </div>
               
               </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
 </div>
 @stop