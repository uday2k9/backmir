@extends('admin/layout/admin_template')
 
@section('content')

  
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif
 
    <div class="module">
    
    <form method="post" id="filterform" action="<?php echo url();?>/admin/brand">
          <input type="hidden" name="_token" value="{!! csrf_token() !!}"/>
         
            <div class="filter filt_css pull-right"><span>Search members</span> <input type="text" name="searchstring" value="<?php echo $searchstring?>" id="searchstring" /><div class="search_top"><input type="submit" class="btn btn-success marge" value="search" name="search"/></div></div>
                
        </form>
                               
        <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subscription Status</th>
                    <th>Slug</th>
                    
                    <th>Status</th>
                    <th>Admin Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
                
                
            <tbody>
                <?php $i=1;?>
                @foreach ($brands as $brand)
                <tr class="odd gradeX">
                    <td class=""><?php echo $i; ?></td>
                    <td class="">{!! $brand->business_name !!}</td> 
                    <td class="">{!! $brand->email !!}</td>
                    <td class="">{!! $brand->subscription_status !!}</td>
                    <td class="">{!! $brand->slug !!}</td>
                 
                    <td class="">
                        @if ($brand->status == 1)
                            Active 
                        @else
                            <a href="{{ URL::to('admin/brand/status/' . $brand->id) }}" data-toggle="tooltip" title="Make Active" >Inactive</a>
                        @endif
                    </td>
                    <td class="">
                        @if ($brand->admin_status == 1)
                           <a href="{{ URL::to('admin/brand/admin_inactive_status/' . $brand->id) }}" data-toggle="tooltip" title="Make Inactive" >Active</a>
                        @else
                           <a href="{{ URL::to('admin/brand/admin_active_status/' . $brand->id) }}" data-toggle="tooltip" title="Make Active" >Inactive</a>
                        @endif
                        
                       <a href="{{ URL::to('admin/brand-orders/' . $brand->id) }}" data-toggle="tooltip" title="Orders" >Orders</a>
                    </td>
                   
                    <td>
                        <a href="{!!route('admin.brand.edit',$brand->id)!!}" class="btn btn-warning">Edit</a>
                    </td>
                    <td>
                        {!! Form::open(['method' => 'DELETE', 'route'=>['admin.brand.destroy', $brand->id],'onsubmit' => 'return ConfirmDelete()']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                <?php $i++;?>
                @endforeach
                </tbody>
                
            </table>
    </div>

  <div><?php echo $brands->render(); ?></div>
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
