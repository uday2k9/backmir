@extends('admin/layout/admin_template')

@section('content')


<script>
  
  // When the browser is ready...
  $(function() {
 
    $("#form_order").validate({
        
        ignore: [],
        // Specify the validation rules
        rules: {
            order_status: "required",
           
        },
        
        // Specify the validation error messages
        messages: {
            order_status: "Please enter valid status.",
         
        },               

        submitHandler: function(form) {
            form.submit();
        }
    });

 
    $("input[class=orders]").on('blur, keyup' ,function(){

        calculate();
    })

    $("#order_status").on('change' ,function(){

        if($("#order_status").val() == "shipped")
          $("#tracking").show();
        else
          $("#tracking").hide();

    })


  });


</script>
   
   {!! Form::model($orders,array('method' => 'PATCH','id'=>'form_order','name'=>'form_order','class'=>'form-horizontal row-fluid','route'=>array('admin.orders.update',$orders->id))) !!}

    <div class="control-group">
      <label class="control-label" for="basicinput">Order Status: *</label>
        <div class="controls">               
      	  <select name="order_status" id="order_status">
      		  <option value="">Select</option>
      		  <option value="pending" <?php if($orders->order_status=='pending'){ echo 'selected="selected"';}?>>Pending</option>
      		  <option value="processing" <?php if($orders->order_status=='processing'){ echo 'selected="selected"';}?>>Processing</option>	
      		  <option value="fraud" <?php if($orders->order_status=='fraud'){ echo 'selected="selected"';}?>>Fraud</option>
            <option value="prepare shipment" <?php if($orders->order_status=='prepare shipment'){ echo 'selected="selected"';}?>>Prepare Shipment</option>  
      		  <option value="shipped" <?php if($orders->order_status=='shipped'){ echo 'selected="selected"';}?>>Shipped</option>
      		  <option value="completed" <?php if($orders->order_status=='completed'){ echo 'selected="selected"';}?>>Completed</option>
      		  <option value="cancel" <?php if($orders->order_status=='cancel'){ echo 'selected="selected"';}?>>Cancel</option>	
      		</select>
        </div>
    </div>

    <div class="control-group" id="tracking" <?php if($orders->order_status!='shipped'){ ?>style="display:none;" <?php }?>>
        <label class="control-label" for="basicinput">Tracking Number:</label>
        <div class="controls">
          {!! Form::text('tracking_number',$orders->tracking_number,['id'=>'tracking_number','placeholder'=>'Tracking Number','class'=>'span8'])!!}
        </div>
    </div> 

    


    <div class="form-group">
        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
    

@stop


