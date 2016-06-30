<script type="text/javascript" src="<?php echo url();?>/public/backend/scripts/jquery-1.9.1.min.js"></script>     
<script type="text/javascript">
  $('.pack_typ').click(function() {
       var settr="weighttr"+$(this).attr("data-id");
       var idx=$(this).attr("data-id");
       
       var wttextval='<input type="text" style="width:64%!important;"name="weight" id= "weight'+idx+'" value= "" />';
       $("#weighttr"+idx).html(wttextval);


       var botxval='<a href="" id=aherf'+idx+' onClick = "return openSolution('+idx+');" data-id='+idx+' class="btn btn-success marge pull-left septopost">Done</a>';
       
       $("#bottr"+idx).html(botxval);
       
});  
  function openSolution(e){
    
    var prs=$('[name=mail'+e+']:checked').val();
    var pak=$('[name=packi'+e+']:checked').val();
    var wat=$('#weight'+e).val();
    // alert(prs);
    // alert(pak);
    // alert(wat);
    $.ajax({
                        url: "<?php echo url();?>/ajax/storetopostmasterqueue",
                        data: {prs:prs,pak:pak,wat:wat,oid:e,_token: '{!! csrf_token() !!}'},
                        type :"post",
                        success: function( data ) {
                        if(data){
                            $('#aherf'+e).html("Queued");
                            $('#cks').show();
                        }
                        }
                        });

    
    return false;
  }
</script>      

<?php foreach ($ShipmentPackage as $key => $value) { ?>
    <input type="radio" class="radio_clsx pack_typ" id="packi<?php echo $settr;?>" name="packi<?php echo $settr;?>" data-id="<?php echo $settr;?>" value="<?php echo $value->id;?>"><?php echo $value->name;?>(<?php echo $value->width;?>X<?php echo $value->height;?>X<?php echo $value->length;?>)
                         
<?php echo "<br>";} ?>
    