@extends('admin/layout/admin_template')
 
@section('content')
  
@if(Session::has('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{!! Session::get('success') !!}</strong>
    </div>
 @endif
 @if(Session::has('error'))
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{!! Session::get('error') !!}</strong>
    </div>
 @endif
 <?php 
    $orderstatus = Session::get('orderstatus');
    $filterdate = Session::get('filterdate');
?> 
<script type="text/javascript">
    function callPush(){
        if(confirm("Do you want to process these labels?")){
            var param = 1;
            //alert("yes");
            $.ajax({
                        url: "<?php echo url();?>/ajax/create_selected_order_counter",
                        data: {_token: '{!! csrf_token() !!}'},
                        type :"post",
                        success: function( data ) {
                            $("#tesval").html('');
                            $("#pagval").html('');
                            $("#setval").html(data);
                        
                        }
                        });
        }
        
        else{
            var param = 0;
            alert("no");
        }
           
        
        //window.location = '<?php echo url();?>/admin/push_order_process/'+param;
    }
    
    <?php
    if (Session::has("merge")) {
        echo 'window.open("'.url().'/uploads/pdf/'.Session::get("pdffile").'")';
        Session::forget("merge");
        Session::forget("pdffile");
    }
    
    ?>
</script>
    <div class="module" id="setval">
        
        <form method="post" id="filterform" action="<?php echo url();?>/admin/orders/filter">
          <input type="hidden" name="_token" value="{!! csrf_token() !!}"/>
          <div class="filter filt_css pull-left"><span>Filter by status</span>
          
          <select id="orderstatus" name="orderstatus">
            <option value="0">Select</option>
            <option value="pending" <?php if($orderstatus=='pending'){echo 'selected="selected"';}?>>Pending</option>
            <option value="processing" <?php if($orderstatus=='processing'){echo 'selected="selected"';}?>>Processing</option>
            <option value="completed" <?php if($orderstatus=='completed'){echo 'selected="selected"';}?>>Completed</option>
            <option value="shipped" <?php if($orderstatus=='shipped'){echo 'selected="selected"';}?>>Shipped</option>
            <option value="cancel" <?php if($orderstatus=='cancel'){echo 'selected="selected"';}?>>Cancel</option>
            <option value="fraud" <?php if($orderstatus=='fraud'){echo 'selected="selected"';}?>>Fraud</option>
          </select>
            
            
          </div>


          <div class="pull-right filt_css"><span>Filter by brand</span> <input type="text" name="brandemail" value="<?php echo $brandemail?>" id="brandemail" /></div>
            <div class="filter filt_css filter_right" style="clear:both;padding-left: 10px;">                
                <div class="pull-left"><span>Filter From date</span> <input type="text" name="filterdate" value="<?php echo $filterdate?>" id="filterdate" style="width:100px;" /></div><div class="pull-left" style="margin-right:10px; padding-left:10px;"><span>Filter To date</span> <input type="text" name="filtertodate" value="<?php echo $filtertodate?>" id="filtertodate" style="width:100px;"  /></div>               

                <div class="search_top pull-right"><input type="submit" class="btn btn-success marge" value="search" name="search"/></div>
                <div class="pull-right" style="margin-right:10px;"><span></span><input type="text" name="filterkeyword" value="<?php echo $filterkeyword?>" id="filterkeyword" placeholder="Filter by email or order no." style="width:200px;"  /></div>             
                
            </div>
        </form>
        
        
        <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped  display" width="100%">
            <thead>
                <tr>

                    <th></th>
                    <th>Order ID</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ordered By</th>
                    <th>Date</th>
                    <th>Label</th>
                    <th>Details</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
                
                
            <tbody>
                <?php 
                    $i=1;
                   //echo "<pre>";print_r($all_process_labels); 
                ?>
                @foreach ($order_list as $order)
               <?php  $serialize_address = unserialize($order->shiping_address_serialize);?>
                <tr class="odd gradeX">
                    @if($order->is_wholesale != 1)
                    <td>
                        @if($order->usps_label=='')
                        <?php
                            $checked_var = '';
                            if(!empty($all_process_labels)){
                                foreach ($all_process_labels as $each_label) {
                                    if($each_label->order_id==$order->id){
                                        $checked_var = 'checked=checked';
                                        break;
                                    }                       
                                }
                            }
                        ?>
                     <input type="checkbox" class="checkbox_cls" name="add_queue[]" id="add_queue<?php echo $order->id;?>" <?php echo $checked_var;?> value="<?php echo $order->id;?>">
                        @endif
                    </td>
                    @else
                        <td></td>
                    @endif
                    <td>{!! $order->order_number !!}</td>
                    <td>{!! '$'.number_format($order->order_total,2); !!}</td>
                    <td>
                    
                    
                    @if($order->is_wholesale == 1)
                        <a href="#" data-toggle="tooltip" title="Status" >{!! $order->wholesale_status !!}</a>
                    @else
                        <a href="#" data-toggle="tooltip" title="Status" >{!! $order->order_status !!}</a>
                    @endif
                    </td>

                    <td>{!! $serialize_address['first_name'].' '.$serialize_address['last_name'] !!}</td>
                    <td>{!! date('m/d/Y',strtotime($order->created_at)) !!}</td> 
                    <td style="word-break:break-all;word-wrap:break-word;"><?php if($order->usps_label!=""){ ?><a href="{!! url().'/uploads/pdf/'.$order->usps_label; !!}" target="_blank">{!! $order->usps_label; !!}</a><?php } ?></td>                                       
                     <td><a href="<?php echo url();?>/admin/order-details/<?php echo $order->id;?>" class="btn btn-success">Details</a></td>
                    <td>
                        @if($order->is_wholesale == 1)
                            @if($order->wholesale_status == 'offered')                            
                                {{ "Waiting" }}
                            @else
                                <a href="<?php echo url();?>/admin/wholesale-offer/<?php echo $order->id;?>" class="btn btn-warning">Offer</a>
                            
                            @endif
                        @else
                            <a href="{!!route('admin.orders.edit',$order->id)!!}" class="btn btn-warning">Edit</a>

                        @endif
                    </td>
                    <td>
                        {!! Form::open(['method' => 'DELETE', 'route'=>['admin.orders.destroy', $order->id]]) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                <?php $i++;?>
                @endforeach
                </tbody>
                
            </table>
    </div>
    <div>
  </div>

  <div id="tesval"><a href="javascript:callPush()"  class="btn btn-success marge pull-left">Print Label</a><div class="pull-right" id="pagval"><?php echo $order_list->render(); ?></div></div>

<script type="text/javascript">


    $('.radio_cls').on('change',function(){
        $this = $(this);
		var name_grp=$this.attr('name');
		//alert('#add_queue'+$this.attr('data-id'));

        $('#add_queue'+$this.attr('data-id')).prop("checked",true);
       
       //alert($('#add_queue'+$this.attr('data-id')+':checked').length);

        var str = $('input[name="'+name_grp+'"]:checked').val();
		var sel_id = $('input[name="'+name_grp+'"]:checked').attr('data-id');
        
		//alert(sel_id);
        
        call_ajax(sel_id,$this);
    });

    $('.checkbox_cls').on('click',function(){
        $this = $(this);
		//alert($this.val());
		var this_id=$this.val();
		if($this.is(':checked')){
			var check_length=$('input[name="mail'+this_id+'"]:checked').length;	
			
			call_ajax($this.val(),$this);		
			
		}
		else{
			$('input[name="mail'+this_id+'"]').attr('checked',false);	
        	call_ajax($this.val(),$this);
		}
    });


    function call_ajax(order_id,obj){
		//console.log(obj)
        if(obj.is(':checked'))
            var param = 'add';
        else
            var param = 'remove';
			
			//alert(param);


        //var mail_option = $('input[name="mail'+order_id+'"]:checked').val();
        //alert(mail_option);return false;

        

        $.ajax({
              url: '<?php echo url();?>/admin/add-process-queue',
              method: "POST",      
              data: { "order_id" : order_id,"param" :param ,_token: '{!! csrf_token() !!}'},
              success:function(data)
              {
                if(data!="1"){

                    $( "#add_queue"+order_id ).prop( "checked", false );
                    //alert(data);
                }
               
               
              }
        });
    }


</script>  
    
<script>
    
    $("#orderstatus").change(function(){
            
          //  $("#filterform").submit();
    });
    $(document).ready(function(){
        $( "#filterdate" ).datepicker({ dateFormat: 'yy-mm-dd' });
        $( "#filtertodate" ).datepicker({ dateFormat: 'yy-mm-dd' });
        
        $( "#brandemail" ).autocomplete({            
            source: "{!!url('admin/brand-search/')!!}",
             minLength:0 
          }).bind('focus', function(){ $(this).autocomplete('search'); } )
    });
    
</script>
@endsection
