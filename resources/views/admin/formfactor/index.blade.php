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
        if($('#search_fromfactor').val()=='')
            window.location.href = "{!!url('admin/form-factor')!!}";
        else
            window.location.href = "{!!url('admin/form-factor')!!}"+'/'+$('#search_fromfactor').val();
    }

$(function(){
 $("#search_fromfactor").keyup(function (e) {
  if (e.which == 13) {
    search();
  }
 });
});

/****** Auto complete ********/
$(document).ready(function(){
    $( "#search_fromfactor" ).autocomplete({
      source: "{!!url('admin/form-factor-name')!!}"
    });
});
 
</script>

<div class="pull-left">
   <input type="text" name="search_fromfactor" id="search_fromfactor" value="{!! $param !!}" placeholder="Search By From Factor" class="span4"> 
   <a href="javascript:search()" class="btn btn-success marge">Search</a>
   <a href="{!!url('admin/form-factor')!!}" class="btn btn-success marge">Clear</a>
</div>
 <a href="{!!url('admin/formfactor/create')!!}" class="btn btn-success pull-right">Create Form Factor</a>
 <hr>
 
<div class="module">
    <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
    <thead>
        <tr>
            <th>Sl No.</th>
            <th>Name</th>
            <th>Price</th>
            <th>Minimum Weight</th>
            <th>Maximum Weight</th>
            <th>Unit</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
        
        
    <tbody>
        <?php $i=1;?>
        @foreach ($formfactors as $formfactor)
        <tr class="odd gradeX">
            <td class=" sorting_1">

                <?php echo $i; ?>
            </td>

            <td class=" ">
                {!! $formfactor->name !!}
            </td>

            <td class=" ">
                {!! $formfactor->price !!}
            </td>

            <td class=" ">
                {!! $formfactor->minimum_weight !!}
            </td>

            <td class=" ">
                {!! $formfactor->maximum_weight !!}
            </td>
            <?php
                if($formfactor->count_unit==1)
                {
                    $unit="Units";
                }
                else if($formfactor->count_unit==2)
                {
                    $unit="Grams";
                }
                else
                {
                    $unit="N/A";  
                }
            ?>
            <td class=" ">
                {!! $unit !!}
            </td>
           
            <td>
                <a href="{!!route('admin.formfactor.edit',$formfactor->id)!!}" class="btn btn-warning">Edit</a>                                            </td>

            <td>
                {!! Form::open(['method' => 'DELETE', 'route'=>['admin.formfactor.destroy', $formfactor->id]]) !!}
                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            </td>
        </tr>
        <?php $i++; ?>
            @endforeach
        </tbody>
        
    </table>
</div>

  <div><?php echo $formfactors->render(); ?></div>
@endsection
