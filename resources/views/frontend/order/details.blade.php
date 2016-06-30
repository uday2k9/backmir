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
                     
                    <div class="table-responsive" style="color:#fff">
                    <input type="hidden" name="site_base_url" id="site_base_url" value="<?php echo url();?>" />
                      <table width="100%" class="order-details">
                        <tr>
                          <td colspan="2"><h2>Order History</h2></td>
                        </tr>
                        <?php                  
                          $serialize_address = unserialize($orders[0]['shiping_address_serialize']);                                    
                        ?>
                        <tr>
                          <td width="50%" valign="top">
                            <table width="100%">
                              <tr>
                                <td><strong>Order Information</strong></td>
                              </tr>
                              <tr>
                                <td>Order ID: {{ $orders[0]['order_number'] }}</td>
                              </tr>
                              <tr>
                                <td>Account Email: {{ $serialize_address['email'] }}</td>
                              </tr>
                              <tr>
                                <td>Date Added: {{ Carbon\Carbon::parse($orders[0]['updated_at'])->format('M/d/Y') }}</td>
                              </tr>
                              <tr>
                                <td>Payment Method: {{ $orders[0]['payment_method'] }}</td>
                              </tr>
                              <tr>
                                <td>Shipping Type: {{ $orders[0]['shipping_type'] }}</td>
                              </tr>
                              <tr>
                                <td>Shipping Cost($): {{ number_format($orders[0]['shipping_cost'],2) }}</td>
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
                            <table width="100%"  cellpadding="5"  cellspacing="5" border="0">
                              <tr>
                                <td>Product Image</td>
                                <td>Product Name</td>
                                <td>Quantity</td>
                                <td>Price($)</td>
                                <td>Duration</td>
                                <td>Form Facto</td>                                
                              </tr>
                              <tr>
                                <td colspan="7">
                                  <hr />
                                </td>
                              </tr>
                              @foreach($order_items as $order_item)
                              <tr>
                                <td><img src="<?php echo url();?>/uploads/product/medium/{{ $order_item->product_image }}" width="70" /></td>
                                <td>{{ $order_item->product_name }}</td>
                                <td>{{ $order_item->quantity }}</td>
                                <td>{{ number_format($order_item->price,2) }}</td>
                                <td>{{ $order_item->duration }}</td>
                                <td>{{ \App\Model\FormFactor::where('id',$order_item->form_factor_id)->first()->name }}</td>                               
                              </tr>
                               <tr>
                                <td class="space">
                                  
                                </td>
                              </tr>
                              @endforeach
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
                          <td width="50%">Date Updated</td>
                          <td width="50%">Order Status</td>
                        </tr>
                        <tr>
                          <td colspan="2" class="space" style="border-bottom:1px solid #ccc">
                            &nbsp;
                          </td>
                        </tr>
                        <tr>
                          <td width="50%">{{ Carbon\Carbon::parse($orders[0]['updated_at'])->format('M/d/Y') }}</td>
                          <td width="50%">{{ $orders[0]['order_status'] }}</td>
                        </tr>
                      </table>
                    </div>
                    <div><?php //echo $orders->render() ?></div>
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