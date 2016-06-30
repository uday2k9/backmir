@extends('admin/layout/admin_template')
 
@section('content')

  
@if(Session::has('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{!! Session::get('success') !!}</strong>
    </div>
 @endif
 
    
   <a class="btn btn-success pull-right" href="<?php echo url();?>/admin/coupon/create">Create Coupon</a><hr>
   
    <div class="module">
                               
        <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Coupon Name</th>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1;?>
                @foreach ($coupons as $coupons)
                <tr class="odd gradeX">
                    <td class=""><?php echo $i; ?></td>
                    <td class="">{!! $coupons->name !!}</td>
                    <td class="">{!! $coupons->code !!}</td>
                    <td class="">{!! $coupons->discount !!}</td>
                    <td class="">{!! $coupons->type !!}</td>
                   
                    <td class="">
                     <?php
              
                        if($coupons->status == 0){
                          $sta = 1;
                          $tooltip = 'Make Active';
                        }
                        else{
                          $sta = 0;
                          $tooltip = 'Make Inactive';
                        }
                    ?>
                        <a href="{!! url() !!}/admin/change_status/{!! $coupons->id.'/'.$sta;!!}" data-toggle="tooltip" title="{!! $tooltip;!!}">{!! ($coupons->status==1)?'Active':'Inactive'; !!}</a>
                    </td>
                    <td>
                        <a href="{!!route('admin.coupon.edit',$coupons->id)!!}" class="btn btn-warning">Edit</a>
                    </td>
                    <td>
                        {!! Form::open(['method' => 'DELETE', 'route'=>['admin.coupon.destroy', $coupons->id], 'onsubmit' => 'return ConfirmDelete()']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                <?php $i++;?>
                @endforeach
                </tbody>
                
            </table>
    </div>

  <div><?php //echo $coupons->render(); ?></div>


  <script>

  function ConfirmDelete()
  {
  var x = confirm("Are you sure you want to delete?");
  if (x)
    return true;
  else
    return false;
  }

</script>
@endsection
