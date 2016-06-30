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
                  <h3>Coupon List</h3>

                   @if(Session::has('error'))
                    <div class="alert alert-error container-fluid">
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
                    
                    <table class="table special_height" id="coupon_table">
                    <thead>
                      <tr>
                        <th>SL No.</th>
                        <th>Coupon Code</th>
                        <th>Discount</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                     
                     <?php 
                      $k=1;
                     foreach($coupons as $coupon){
                      
                      ?>
                      <tr>
                        <td>#<?php echo $k++ ?></td>
                        <td><?php echo $coupon->code ?></td>
                        <td><?php echo $coupon->discount ?></td>
                        <td><?php echo $coupon->type ?></td>
                      <td class="">
                     <?php
              
                        if($coupon->status == 0){
                          $sta = 1;
                          $tooltip = 'Make Active';
                        }
                        else{
                          $sta = 0;
                          $tooltip = 'Make Inactive';
                        }
                    ?>
                        <a href="{!! url() !!}/change_status/{!! $coupon->id.'/'.$sta;!!}" data-toggle="tooltip" title="{!! $tooltip;!!}">{!! ($coupon->status==1)?'Active':'Inactive'; !!}</a>
                    </td>
                      <td><a href="{!!route('brandcoupons.edit',$coupon->id)!!}" class="btn btn-warning">Edit</a></td>
                      <td>
                        {!! Form::open(['method' => 'DELETE', 'route'=>['brandcoupons.destroy', $coupon->id], 'onsubmit' => 'return ConfirmDelete()']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                      </tr>
                     <?php }?> 
                      
                    </tbody>
                  </table>
                  </div>
              
                    <div class="form_bottom_panel">
                    <!--<a href="<?php echo url();?>/brand-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>-->
                    <a href="<?php echo url();?>/brandcoupons/create" class="green_sub text-center pull-right"><i class="fa fa-plus-circle"></i>Create Coupon</a> 
                    </div>
               </div>
               
               </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
 </div>
 <script>
// Delete Coupon from brand //

  function ConfirmDelete()
  {
  var x = confirm("Are you sure you want to delete?");
  if (x)
    return true;
  else
    return false;
  }

</script>

 @stop