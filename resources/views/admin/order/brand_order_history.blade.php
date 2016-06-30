@extends('admin/layout/admin_template')
 
@section('content')

  
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif

    <div class="module">
          
          <form method="post" id="filterform" action="<?php echo url();?>/admin/orders/filter">
          <input type="hidden" name="_token" value="{!! csrf_token() !!}"/>
          <div class="filter filt_css pull-left"><span>Filter by status</span>
          
          
          <select id="orderstatus" name="orderstatus">
            <option value="">Select</option>
            <option value="pending" <?php if($orderstatus=='pending'){echo 'selected="selected"';}?>>Pending</option>
            <option value="processing" <?php if($orderstatus=='processing'){echo 'selected="selected"';}?>>Processing</option>
            <option value="completed" <?php if($orderstatus=='completed'){echo 'selected="selected"';}?>>Completed</option>
            <option value="shipped" <?php if($orderstatus=='shipped'){echo 'selected="selected"';}?>>Shipped</option>
            <option value="cancel" <?php if($orderstatus=='cancel'){echo 'selected="selected"';}?>>Cancel</option>
            <option value="fraud" <?php if($orderstatus=='fraud'){echo 'selected="selected"';}?>>Fraud</option>
          </select>
          
            
            
          </div>
            <div class="filter filt_css filter_right pull-right"><span>Filter by date</span> <input type="text" name="filterdate" value="<?php echo $filterdate?>" id="filterdate" /><div class="search_top"><input type="submit" class="btn btn-success marge" value="search" name="search"/>
                    </div></div>
                
        </form>
        <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Order ID</th>
                    <th>Order Total</th>
                    <th>Order Status</th>
                    <th>Ordered By</th>
                    <th>Order Date</th>
                    <th>View Details</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
                
                
            <tbody>
                <?php $i=1;?>
                @foreach ($order_list as $order)
               <?php  $serialize_address = unserialize($order->order->shiping_address_serialize);?>
                <tr class="odd gradeX">
                    <td class=""><?php echo $i; ?></td>
                    <td class="">{!! $order->order->order_number !!}</td>
                    <td class="">{!! '$'.number_format($order->order->order_total,2); !!}</td>
                    <td class=""><a href="#" data-toggle="tooltip" title="Status" >{!! $order->order->order_status !!}</a></td>
                   
                    <td class="">{!! $serialize_address['first_name'].' '.$serialize_address['last_name'] !!}</td>
                   
                    <td class="">{!! date('m/d/Y',strtotime($order->order->created_at)) !!}</td>
                    
                    <td>
                        <a href="<?php echo url();?>/admin/order-details/<?php echo $order->order->id;?>" class="btn btn-success">Details</a>
                    </td>
                    <td>
                        <a href="{!!route('admin.orders.edit',$order->order->id)!!}" class="btn btn-warning">Edit</a>
                    </td>
                    <td>
                        {!! Form::open(['method' => 'DELETE', 'route'=>['admin.orders.destroy', $order->order->id]]) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                <?php $i++;?>
                @endforeach
                </tbody>
                
            </table>
    </div>

  <div><?php //echo $order_list->render(); ?>
  
  {!! str_replace('/?', '?', $order_list->render()) !!}
 </div>
    
    <script>
        
        $("#orderstatus").change(function(){
              //  $("#filterform").submit();
        });
        $(document).ready(function(){
            $( "#filterdate" ).datepicker({ dateFormat: 'yy-mm-dd' });
        });
        
    </script>
@endsection
