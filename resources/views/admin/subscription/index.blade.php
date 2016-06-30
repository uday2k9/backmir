@extends('admin/layout/admin_template')
 
@section('content')

  
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif
 
    <div class="module">
                               
        <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Brand Name</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>Start Date</th>
                    <th>End Date</th>                    
                    <th>Payment Status</th>                  
                    <th>Edit</th>
                   
                </tr>
            </thead>
                
                
            <tbody>
                <?php $i=1;?>
                @foreach ($subscriptions as $subscription)
                <?php
                    $color_code="";
                    if($subscription->getSubMembers->subscription_status=='expired')
                    {
                        $color_code="#ff0000";
                    }
                ?>
                <tr class="odd gradeX" style="color:<?php echo $color_code;?>">
                    <td class=""><?php echo $i; ?></td>
                    <td class="">{!! $subscription->getSubMembers->fname.' '.$subscription->getSubMembers->lname ." - ". $subscription->getSubMembers->business_name!!}</td>
                    <td class="">{!! $subscription->getSubMembers->subscription_status !!}</td>
                    <td class="">${!! $subscription->subscription_fee !!}</td>
                    <td class="">{!! $subscription->start_date !!}</td>
                    <td class="">{!! $subscription->end_date !!}</td>                 
                    <td class="">
                       {!! $subscription->payment_status !!}
                    </td>                  
                    <td>
                        <a href="{!!route('admin.subscription.edit',$subscription->subscription_id)!!}" class="btn btn-warning">Edit</a>
                    </td>
                 
                </tr>
                <?php $i++;?>
                @endforeach
                </tbody>
                
            </table>
    </div>

  <div><?php echo $subscriptions->render(); ?></div>
@endsection
