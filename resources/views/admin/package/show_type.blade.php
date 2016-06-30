@extends('admin/layout/admin_template')
 
@section('content')



@if(Session::has('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{!! Session::get('success') !!}</strong>
    </div>
 @endif

<script type="text/javascript">
    function search(){
        if($('#search_package').val()=='')
            window.location.href = "{!!url('admin/package-type-list')!!}";
        else
            window.location.href = "{!!url('admin/package-type-list')!!}"+'/'+$('#search_package').val();
    }

$(function(){
 $("#search_package").keyup(function (e) {
  if (e.which == 13) {

    search();
  }
 });
});

/****** Auto complete ********/
$(document).ready(function(){
    $( "#search_package" ).autocomplete({
      source: "{!!url('admin/package-type-name')!!}"
    });
});
 
</script>

<div class="pull-left">
   <input type="text" name="search_package" id="search_package" value="{!! $param !!}" placeholder="Search By Package Type" class="span4"> 
   <a href="javascript:search()" class="btn btn-success marge">Search</a>
   <a href="{!!url('admin/package/type')!!}" class="btn btn-success marge">Clear</a>
</div>
 <a href="{!!url('admin/package/createtype')!!}" class="btn btn-success pull-right">Create Package Type</a>
 <hr>
 <input type="hidden" name="site_base_url" id="site_base_url" value="<?php echo url();?>" />
<div class="module">
    <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
    <thead>
        <tr>
            <th>Sl No.</th>
            <th>Name</th>
            <th>Image</th>                    
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
        
        
    <tbody>
        <?php $i=1;?>
        @foreach ($packages as $package)
        <tr class="odd gradeX">
            <td class=" sorting_1">

                <?php echo $i; ?>
            </td>

            <td class=" ">
                {!! $package->name !!}
            </td>

            <td class=" ">                
                @if($package->image != '')          
                    <img src="<?php echo url()?>/uploads/package/type/{!! $package->image !!}" width="80" />
                @else
                    <img src="<?php echo url()?>/uploads/no-image-found.jpg" width="80" />
                @endif
            </td>          
           
            <td>
                <a href="<?php echo url()?>/admin/package/edittype/{{ $package->id }}" class="btn btn-warning">Edit</a></td>

            <td>
                <a href="javascript:void(0);" onclick="delete_item({{ $package->id }})" class="btn btn btn-danger">Delete</a>
                
                
            </td>
        </tr>
        <?php $i++; ?>
            @endforeach
        </tbody>
        
    </table>
</div>
 <div><?php echo $packages->render(); ?></div>
@endsection
@section('scripts')
    <script>
        function delete_item(id)
        {
            if(confirm('Are you sure?'))
            {
                var site_base_url = $("#site_base_url").val();
                // alert(site_base_url);
                window.location.href=site_base_url+'/admin/package/deletetype/'+id;
            }
        }
    </script>
@stop
