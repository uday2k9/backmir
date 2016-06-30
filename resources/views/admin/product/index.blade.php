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
            window.location.href = "{!! url('admin/product-list')!!}"+'/'+$('#pro_type').val();
        else
            window.location.href = "{!! url('admin/product-list')!!}"+'/'+$('#pro_type').val()+'/'+$('#search_name').val();
    }

    $(document).ready(function(){
      $('#pro_type').change(function(){
        search();

      })
    })

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
      source: "{!!url('admin/discontinue-product-search/'.$discountinue)!!}" 
    });
});
 
</script> 

    <div class="pull-left">
       <input type="text" name="search_name" id="search_name" value="{!! $param !!}"  placeholder="Search By Product Name" class="span3"> 
       <a href="javascript:search()" class="btn btn-success marge">Search</a>
       <a href="{!! url('admin/product-list/'.$discountinue);!!}" class="btn btn-success marge">Clear</a>
    </div>

     <a href="{!!url('admin/product/create')!!}" class="btn btn-success pull-right">Create Product</a>
    <select name="pro_type" id="pro_type" style="float:right">
      <option value="1" @if($discountinue=="1") selected="selected" @endif>Discontinue Product</option>
      <option value="0" @if($discountinue=="0") selected="selected" @endif>Continue Product</option>
    </select>
    <hr>                      
    <div class="module">
      <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
        <thead>
            <tr>
              <th>Image</th>
              <th>Label</th>
              <th>Name/SKU</th>
              <th>Brand</th>
              <th>Form Factors (Price)</th>
              <th>Related</th>
              <th>Edit</th>
              <th>Delete</th>                
            </tr>
        </thead>            
        <tbody>
            <?php $i=1;?>
            @foreach ($products as $product)
            <!-- {!! "<pre>"; print_r($product); !!} -->
            <tr class="odd gradeX">
               <td class=""><img src="{!! url();!!}/uploads/product/medium/{!! $product->image1 !!}"></td>
               <td class="">
                 <?php if($product->label!="" && file_exists('uploads/product/medium/'.$product->label)) {?>
                    <img src="<?php echo url();?>/uploads/product/medium/<?php echo $product->label;?>" alt="" width="125" />
                  <?php } else {?>
                    <img src="<?php echo url();?>/public/frontend/images/upload-image-btn.png" alt=""/>
                  <?php } ?>
               </td>
               <td class="">{!! $product->product_name !!}<br />({!! $product->sku !!})</td>
               <td class="">{!! $product->GetBrandDetails['business_name']; !!}</td>
               <td class="">{!! rtrim($product->formfactor_name,'<br/>'); !!}</td>
               <td class="">
               <?php
              
                if($product->related == 'yes'){
                  $make_related_status = 'no';
                  $tooltip = 'Make Related';
                }
                else{
                  $make_related_status = 'yes';
                  $tooltip = 'Make Non Related';
                }
               
                ?>

                  <a href="{!! url() !!}/admin/change_related_status/{!! $product->id.'/'.$make_related_status;!!}" data-toggle="tooltip" title="{!! $tooltip;!!}">{!! $product->related; !!}</a></td>
               
                <td>
                    <a href="{!!route('admin.product.edit',$product->id)!!}" class="btn btn-warning edit_table_btn">Edit</a>
                    <a href="{!! url() !!}/admin/ratings/{!! $product->id !!}" class="btn btn-warning rating_table_btn">Ratings</a>
                </td>
                <td>
                    {!! Form::open(['method' => 'DELETE', 'route'=>['admin.product.destroy', $product->id], 'onsubmit' => 'return ConfirmDelete()']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
            <?php $i++; ?>
            @endforeach
        </tbody>          
      </table>
    </div>

  <div>
    {!! str_replace('/?', '?', $products->render()) !!}
  </div>

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
