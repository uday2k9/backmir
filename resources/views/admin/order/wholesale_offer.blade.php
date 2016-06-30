@extends('admin/layout/admin_template')

@section('content')


<script>
  
  // When the browser is ready...

  $(function() {

   
 
    $("#wholesale_offer_form").validate({

        
        /*
        
        ignore: [],
        // Specify the validation rules
        rules: {
            order_status: "required",
           
        },
        
        // Specify the validation error messages
        messages: {
            order_status: "Please enter valid status.",
         
        }, */              

        submitHandler: function(form) {

            /*calculate();
            console.log("flag: "+eflag);
            

            if(eflag)
              return false;*/

            form.submit();

        }
    });

 
    $("input[class=orders]").on('blur, keyup' ,function(){

        calculate();
    })


  });

 function calculate( jQuery ) {
  // Code to run when the document is ready.
    var subtotal = 0;
    eflag = false;
    $("#offer_submit").show();
    $('.orders').each(function(){

        var el = $(this).attr('id');
        
        // Explode the id value which is in the format with underscore such as price_0_0
        var res = el.split("_"); // This results in array 

        var p1 = res[0]; // First part is first 3 lettters
        var p2 = res[1]; // Second part is sl no. of the product ordered
        
        var qty = $("#qty_"+p2).val();
        var price = $("#price_"+p2).val();
        var prow = $("#pricerow_"+p2).val();

        console.log("qty: "+qty);
        console.log("price: "+price);
        
        var total = 0;
        

        if(qty != 'undefined' && price != 'undefined')
        {
          if(price > 0)
          {
            total = qty * price;              
            $("#total_"+p2).html("$"+total.toFixed(2));
            subtotal += total;
          }
          else
          {
            eflag = true;
            
          }

        }

        console.log("eflag: "+eflag);

        if(eflag)
        {
          $("#errormsg").html("Please enter a value less than or equal to the checkout price.");
          $("#offer_submit").hide();
          
        }
        else
        {
          $("#errormsg").html("");
          $("#offer_submit").show();
        }

    });

    $("#sub_total").html("$"+subtotal.toFixed(2));

}

$( document ).ready( calculate );

</script>


{!! Form::open(['url' => 'admin/wholesale-offer/'.$order->id ,'method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'wholesale_offer_form']) !!}

@if($order->is_wholesale == 1)

    <div id="errormsg" class="alert-danger"></div><br />
    <div class="table-responsive spec_tab_resp">

      <table class="table table-information">
        <thead>
          <tr>
            <th>Image</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Min. Price</th>
            <th>Offered Price</th>
            <th>Duration</th>
            <th>Form Factor</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>

        <?php
        $i = 0;
        ?>

          @if(!empty($order_items_list))
            @foreach($order_items_list as $each_item)


             <?php  $pro_dtls = $obj->getProductDetails($each_item->product_id);
             
             ?>

              <tr>
                <td>
                  @if($each_item->product_image!="" && file_exists('./uploads/product/medium/'.$each_item->product_image))
                  <img src="{!! url(); !!}/uploads/product/medium/{!! $each_item->product_image !!}" alt=""  style="max-width:100px"">
                  @else
                  <img src="{!! url(); !!}/uploads/brandmember/noimage.png" alt=""  style="max-width:100px"">
                  @endif


                </td>
                <td><a href="{!! url().'/product-details/'.$pro_dtls->product_slug; !!}" target="_blank"> {!! $each_item->product_name; !!} </a></td>

                <td>{!! $each_item->quantity; !!}</td>
                <td class="text-right">${!! number_format($each_item->price, 2, '.', ''); !!}</td>  
                <td class="text-right">${!! number_format($each_item->min_price, 2, '.', ''); !!}</td>  
                <td id="pricerow_<?php echo $i ?>"><input type="text" class="orders" id="price_<?php echo $i ?>" value="<?php echo number_format($each_item->price, 2, '.', ''); ?>" name="orders[<?php echo $i ?>][price]" placeholder="Offered Price" style="width:50px;" /></td>  
                <td>{!! $each_item->duration; !!}
                <input type="hidden" id="item_<?php echo $i ?>" name="orders[<?php echo $i ?>][id]" value="<?php echo $each_item->id ?>" />
                <input type="hidden" id="qty_<?php echo $i ?>" name="orders[<?php echo $i ?>][quantity]" value="<?php echo $each_item->quantity ?>" />

                </td>
                <td>{!! $each_item->form_factor_name; !!}</td>
                <td class="text-right" id="total_<?php echo $i ?>">${!! number_format(($each_item->price * $each_item->quantity),2, '.', ''); !!}</td>
              </tr>



              <?php $i++ ?>
            @endforeach
          @else
              <tr>
                <td colspan="5">No records found</td>
              </tr>      
          @endif                       
          </tbody>    
          <tfoot>
            <tr>
                <td colspan="7">&nbsp;</td>
                <td class="text-right">Sub-Total</td><td class="text-right" id="sub_total">${!! number_format($order->sub_total,2, '.', ''); !!}</td>
            </tr>
            
          </tfoot>
      </table>
    </div>

    

    @endif

  <div class="form-group">
      {!! Form::submit('Update & Send Offer', ['id' => 'offer_submit', 'class' => 'btn btn-primary']) !!}
  </div>
  <input type="hidden" id="order_id" name="order_id" value="<?php echo $order->id  ?>" />


  {!! Form::close() !!}
  

@stop
