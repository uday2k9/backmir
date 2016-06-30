<script type="text/javascript" src="<?php echo url();?>/public/backend/scripts/jquery-1.9.1.min.js"></script>     
<script type="text/javascript">
  $('.proces_typ').click(function() {
       var settr="packtr"+$(this).attr("data-id");
       var piid=$(this).attr("data-id");
       var ptype=$(this).val();
       $.ajax({
                        url: "<?php echo url();?>/ajax/getshipmentpackage",
                        data: {ptype:ptype,settr:piid,_token: '{!! csrf_token() !!}'},
                        type :"post",
                        success: function( data ) {
                        if(data){
                            $("#"+settr).html(data);
                            
                            $("#weighttr"+piid).html('');
                            $("#bottr"+piid).html('');
                        }
                        }
                        });
       
});
function generatelabel(){
    $("#iid").hide();
    $("#ses").show();
    
                        $.ajax({
                            url: "<?php echo url();?>/ajax/generatelabel",
                            data: {_token: '{!! csrf_token() !!}'},
                            type :"post",
                            success: function( data ) {
                            if(data){
                                $("#ses").hide();
                                    window.location = '<?php echo url();?>/admin/orders';
                                
                            }
                            }
                        });
}  
</script>      
   <div id="ses" style="display:none; text-align: center; padding: 10px 0px; color: rgb(99, 206, 225) !important;"><i class="fa fa-cog fa-spin fa-5x"></i></div>     
<div id="iid">
        <div class="filter filt_css pull-left"><span> Selected Orders </span>
          
          
            
          </div>
        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper" role="grid"><div class="dataTables_length" id="DataTables_Table_0_length"></div><div id="DataTables_Table_0_filter" class="dataTables_filter"></div><table style="width: 100%;" aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="datatable-1 table table-bordered table-striped  display dataTable" width="100%" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr role="row">
                <th aria-label="Order ID: activate to sort column ascending" style="width: 2%;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" role="columnheader" class="sorting"></th>
                
                <th aria-label="Order ID: activate to sort column ascending" style="width: 10%;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" role="columnheader" class="sorting">Order ID</th>
                <th aria-label="Total: activate to sort column ascending" style="width: 8%;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" role="columnheader" class="sorting">Total</th>
                <th aria-label="Ordered By: activate to sort column ascending" style="width: 10%;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" role="columnheader" class="sorting">Ordered By</th>
                <th aria-label="Date: activate to sort column ascending" style="width: 15%;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" role="columnheader" class="sorting">Proccess</th>
                <th aria-label="Label: activate to sort column ascending" style="width: 30%;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" role="columnheader" class="sorting">Package</th>
                <th aria-label="Details: activate to sort column ascending" style="width: 10%;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" role="columnheader" class="sorting">Weight</th>
                <th aria-label="Edit: activate to sort column ascending" style="width: 30%;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" role="columnheader" class="sorting">Action</th>
                
            </thead>
                
                
            
                
                <tbody aria-relevant="all" aria-live="polite" role="alert">
                <?php $i=1;foreach ($Order as  $value) { $serialize_address = unserialize($value->shiping_address_serialize);?>
                    
                
                    <tr class="gradeX odd">
                        <td class="  sorting_1">
                        {!! $i !!}
                        </td>
                        <td class=" ">{!! $value->order_number!!}</td>
                        <td class=" ">{!! '$'.number_format($value->order_total,2); !!}</td>
                        <td class=" ">{!! $serialize_address['first_name'].' '.$serialize_address['last_name']!!}</td>
                        <td class=" ">
                        <label class="custom_input"><input type="radio" class="radio_clsx proces_typ" id="mail<?php echo $value->id;?>" name="mail<?php echo $value->id;?>" data-id="<?php echo $value->id;?>" value="Priority">Priority</label>
                        <label class="custom_input"><input type="radio" class="radio_clsx proces_typ" id="mail<?php echo $value->id;?>" name="mail<?php echo $value->id;?>" data-id="<?php echo $value->id;?>" value="1st Class">1st Class</label>
                        </td>
                        <td class=" " id="packtr<?php echo $value->id;?>"></td>
                        <td class=" " id="weighttr<?php echo $value->id;?>"></td>                      
                        <td class=" " id="bottr<?php echo $value->id;?>" style="word-break:break-all;word-wrap:break-word;"></td>                    
                        
                    </tr>
                    
                <?php $i++;} ?>    

                </tbody>
                </table>
                <div id="cks" style="display:none;"><a href="javascript:generatelabel()"  class="btn btn-success marge pull-left">Print Label</a></div>

    </div>
    