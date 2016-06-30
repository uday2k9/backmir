@extends('admin/layout/admin_template')
 
@section('content')
<?php //print_r($ingredients);exit; ?>
  
@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif
 

<script type="text/javascript">
    function search(){
        if($('#search_name').val()=='')
            window.location.href = "{!!url('admin/ingredient-list')!!}";
        else
            window.location.href = "{!!url('admin/ingredient-list')!!}"+'/'+$('#search_name').val();
    }

$(function(){
 $("#search_name").keyup(function (e) {
  if (e.which == 13) {
    search();
  }
 });
});

/****** Auto complete ********/
$(document).ready(function(){  
//alert('r');  
    $( "#search_name" ).autocomplete({
      source: "{!!url('admin/ingredient-search')!!}"
    });
});
 
</script>

<div class="pull-left">
     <input type="text" name="search_name" id="search_name" value="{!! $param !!}"  placeholder="Search By Ingredient Name or Chemical Name" class="span4"> 
     <a href="javascript:search()" class="btn btn-success marge">Search</a>
     <a href="{!!url('admin/ingredient-list')!!}" class="btn btn-success marge">Clear</a>
</div>
<a href="{!!url('admin/ingredient/create')!!}" class="btn btn-success pull-right">Create Ingredient</a>
<hr>                        
      <div class="module">
      <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered  display" width="100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Chemical Name</th>
                    <th>Price/gm</th>
                    <th>Type</th>
                    <th>Organic</th>
                    <th>Antibiotic</th>
                    <!-- <th>GMS</th> -->
                    <th>Form Factors</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                    
                </tr>
            </thead>
                
                
            <tbody>
                <?php $i=1;?>
                @foreach ($ingredients as $ingredient)
                
                <tr class="odd gradeX"  @if($ingredient->status==0) style="background-color:#F08080; color:#fff;" @endif>
                    <td class="">{!! $ingredient->name !!}</td>
                    <td class="">{!! $ingredient->chemical_name !!}</td>
                    <td class="">{!! $ingredient->price_per_gram !!}</td>
                    <!-- <td class="">{!! $ingredient->list_manufacture !!}</td> -->
                    <td class="">{!! $ingredient->type !!}</td>
                    <td class="">{!! $ingredient->organic !!}</td>
                    <td class="">{!! $ingredient->antibiotic_free !!}</td>
                    <!-- <td class="">{!! $ingredient->gmo !!}</td> -->
                    <td class="">{!! rtrim($ingredient->formfactorname,',') !!}</td>
                    <td class="">@if($ingredient->status==0)
                     Require Attention
                      @elseif($ingredient->status==1)
                      Active
                       @else
                       Inactive
                        @endif</td>
                   
                    <td>
                        <a href="{!!route('admin.ingredient.edit',$ingredient->id)!!}" class="btn btn-warning">Edit</a>
                    </td>
                    <td>
                        {!! Form::open(['method' => 'DELETE', 'route'=>['admin.ingredient.destroy', $ingredient->id], 'onsubmit' => 'return ConfirmDelete()']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                <?php $i++; ?>
                @endforeach
                </tbody>
                
            </table>
    </div>

  <div><?php echo $ingredients->render(); ?></div>

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
