@extends('frontend/layout/frontend_template')
@section('content')

<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {
    //alert($('select[name="existing_address"] option:selected').val());
    $.validator.addMethod("email", function(value, element) 
    { 
    return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 
    }, "Please enter a valid email address.");
    $.validator.addMethod("existing_address", function(value, element) 
    { 
        if($('#radio-1').is(':checked')){
            if($('select[name="existing_address"] option').length<=0){
                return false;
            }
            else if($('select[name="existing_address"] option:selected').val()==''){
                return false;
            }
            else{
                return true;
            }
        }
        else{
            return true;
        }
    }, "Please enter a valid shipping address.");


    $.validator.addMethod("new_address", function(value, element) 
    { 
        if($('#radio-2').is(':checked')){
            if($('#fname').val()==''){
                return false;
            }
            else{
                return true;
            }
        }
        else{
            return true;
        }
    }, "Please Enter First Name.");
	
	$.validator.addMethod("new_address_name", function(value, element) 
    { 
        if($('#radio-2').is(':checked')){
            if($('#lname').val()==''){
                return false;
            }
            else{
                return true;
            }
        }
        else{
            return true;
        }
    }, "Please Enter Last Name.");
	
	$.validator.addMethod("new_address_email", function(value, element) 
    { 
        if($('#radio-2').is(':checked')){
            if($('#email').val()==''){
                return false;
            }
            else{
                return true;
            }
        }
        else{
            return true;
        }
    }, "Please Enter Email Address.");
	
	$.validator.addMethod("new_address_address", function(value, element) 
    { 
        if($('#radio-2').is(':checked')){
            if($('#address').val()==''){
                return false;
            }
            else{
                return true;
            }
        }
        else{
            return true;
        }
    }, "Please Enter Address.");
	
	$.validator.addMethod("new_address_phn", function(value, element) 
    { 
        if($('#radio-2').is(':checked')){
            if($('#phone').val()==''){
                return false;
            }
            else{
                return true;
            }
        }
        else{
            return true;
        }
    }, "Please Enter Phone Number.");
	
	$.validator.addMethod("new_address_country", function(value, element) 
    { 
        if($('#radio-2').is(':checked')){
            if($('#country_id').val()==''){
                return false;
            }
            else{
                return true;
            }
        }
        else{
            return true;
        }
    }, "Please Enter Country Name.");
	
	$.validator.addMethod("new_address_city", function(value, element) 
    { 
        if($('#radio-2').is(':checked')){
            if($('#city').val()==''){
                return false;
            }
            else{
                return true;
            }
        }
        else{
            return true;
        }
    }, "Please Enter City.");
	
	$.validator.addMethod("new_address_zip", function(value, element) 
    { 
        if($('#radio-2').is(':checked')){
            if($('#zip_code').val()==''){
                return false;
            }
            else{
                return true;
            }
        }
        else{
            return true;
        }
    }, "Please Enter Zip Code.");


    // Setup form validation  //
    $("#checkout_form3").validate({
    
        // Specify the validation rules
        rules: {
            fname: {new_address:true},
            lname: {new_address_name:true},
            email: 
            {
                new_address_email:true,
                email: true
            },
            phone :
            {
                new_address_phn:true,
                phoneUS: true
            },
            existing_address:{existing_address:true},
            address:{new_address_address:true},
            country_id: {new_address_country:true},
            city:{new_address_city:true},
            zip_code: {new_address_zip:true}
            
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>

 <div class="inner_page_container">
        <div class="header_panel">
            <div class="container">
             <h2>Brands</h2>
             <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Brands</a></li>
                <li>Health Takes Guts</li>
             </ul>
            </div>
        </div>   
<!-- Start Products panel -->
<div class="products_panel">
    <div class="container">
    
    <!--steps_main-->
    <div class="steps_main text-center">
    <ul>
    <li class="done"><span>&#10003;</span><h6>Checkout Option</h6></li>
    <li class="done"><span>&#10003;</span><h6>Payment Method</h6></li>
    <li class="active"><span>3</span><h6>Shipping Details</h6></li>
    <li><span>4</span><h6>Confirm Order</h6></li>
    </ul>
    </div>
    <!--steps_main-->
    
    <div class="col-sm-12">
    <div class="row">
    <div class="checkout_cont check_3 clearfix">
    <h5>Step 3 :  Shipping Detail</h5>
   <?php // echo "w= ".Session::get('selected_address_id'); ?>
    <cite>Please select the mailing address you would like to have your item(s) delivered to.</cite>
    <?php echo $selected_address_id=Session::get('selected_address_id');
	$select_address=Session::get('select_address');
	?>
    {!! Form::open(['url' => 'checkout-step3','method'=>'POST', 'files'=>true,'class'=>'form row-fluid','id'=>'checkout_form3']) !!}

    <div class="check_box_tab selectionbasedshow green_version">                            
         <input type="radio" class="regular-checkbox" id="radio-1" <?php echo !empty($selected_address_id)? "checked=checked":"" ?> name="RadioGroup1">
         <label for="radio-1">I want to use an existing address</label>
    </div>
    <div class="col-sm-12 clearfix show_hide" id="old_address" <?php if(empty($selected_address_id)){ echo 'style="display:none"';}?>>
    
    <div class="form-group">
    <select class="form-control" name="existing_address">
    <?php foreach($shipAddress as $eachAddress){
    $ship_fname = (($eachAddress->first_name) =='')?$eachAddress->fname:$eachAddress->first_name;
    $ship_lname = (($eachAddress->last_name) =='')?$eachAddress->lname:$eachAddress->last_name;
    //echo $ship_fname.$ship_lname; exit;
    $selected_address_id = 0;
    if(Session::has('selected_address_id'))
    {
        $selected_address_id = Session::get('selected_address_id');
    }
    else 
    {
        if(($eachAddress->default_address)!='')
        {
            $selected_address_id = $eachAddress->default_address;
        }
    }
    ?>
    <option value="<?php echo $eachAddress->id;?>" <?php echo (($selected_address_id==$eachAddress->id)?"selected=selected":'')?>><?php echo $ship_fname.' '.$ship_lname.', '.$eachAddress->address.', '.$eachAddress->address2.', '.$eachAddress->country_name.', '.$eachAddress->zone_name ?>
    </option>
    <?php } ?>
      
    </select>
    </div>
    
    </div>
    <div class="check_box_tab selectionbasedshow green_version bot_clear">                            
         <input type="radio" class="regular-checkbox" id="radio-2" name="RadioGroup1" <?php echo (!Session::has('selected_address_id'))?"checked=checked":"" ?>>
         <label for="radio-2">I want to use a new shipping address</label>
    </div>
    <div class="col-sm-12 clearfix show_hide">
   
    <div class="row" id="new_address" <?php if(!Session::has('selected_address_id')){ echo 'style="display:block"';}?> >
    <div class="form-group col-sm-6">
    <input type="text" class="form-control" placeholder="First Name" name="fname"  id="fname">
    </div>
    <div class="form-group col-sm-6">
    <input type="text" class="form-control" placeholder="Last Name" name="lname"  id="lname">
    </div>
    <div class="form-group col-sm-6">
    <input type="email" class="form-control" placeholder="Email" name="email"  id="email">
    </div>
    <div class="form-group col-sm-6">
    <input type="text" class="form-control" placeholder="Phone" name="phone"  id="phone">
    </div>
    <div class="form-group col-sm-6">
    <input type="text" class="form-control" placeholder="Address 1" name="address"  id="address">
    </div>
    <div class="form-group col-sm-6">
    <input type="text" class="form-control" placeholder="Address 2" name="address2"  id="address2">
    </div>
    <div class="form-group col-sm-6">
    <select  class="form-control" name="country_id" onchange ="getState(this.value)" id="country_id">
        <option value="">Please select country</option>
        <?php foreach($allcountry as $eachCountry)
        {
        ?>
        <option value="{!! $eachCountry->country_id !!}" <?php if($eachCountry->country_id==223){echo 'selected=selected';}?>>{!! $eachCountry->name !!}</option>
        <?php   
        }  
        ?>
    </select>

    </div>
    <div class="form-group col-sm-6">
  
      {!! Form::select('state', array('' => 'Please select state') +$allstates,'default', array('id' => 'state',"class"=>"form-control")); !!}
    </div>
    <div class="form-group col-sm-6">
    <input type="text" class="form-control" placeholder="City" name="city" id="city" >
    </div>
    <div class="form-group col-sm-6">
    <input type="text" class="form-control" placeholder="Post Code"  name="zip_code"  id="zip_code">
    </div>
    </div>
    <input type="hidden" name="select_address" id="select_address" value="<?php echo !empty($selected_address_id)? "existing":"newaddress" ?>">
    
    <input type="submit" class="full_green_btn text-uppercase pull-right" value="Continue">

    </div>
    {!! Form::close() !!}


    </div>
    </div>
    </div>
    
    </div>
</div>
<!-- End Products panel --> 
 </div>
 
 <script type="text/javascript">
 function getState(country_id)
 {
    //alert("country= "+country_id);
    $.ajax({
      url: '<?php echo url();?>/getState',
      method: "POST",
      data: { countryId : country_id ,_token: '{!! csrf_token() !!}'},
      success:function(data)
      {
        //alert(data);
        if(data!='')
        {
			$("#state").attr('disabled',false);
            $("#state").html(data);
			
        }
		else{
			$("#state").html('<option>Please Select Country First</option>');
			$("#state").attr('disabled','disabled');	
		}
      }
    });

 }

</script>


 <script> 
  /* For Toggle the Shipping address  */
  $(document).ready(function(){

    $('#radio-1').click(function(){
    
        if ($(this).is(':checked'))
        {
          $('#radio-2').attr('checked',false);  
          $("#select_address").val('existing');
          $("#new_address").slideUp("slow");
          $("#old_address").slideDown("slow");
        }
    });
    $('#radio-2').click(function(){
        if ($(this).is(':checked'))
        {
          $('#radio-1').attr('checked',false);  
          $("#select_address").val('newaddress');
          $("#new_address").slideDown("slow");
          $("#old_address").slideUp("slow");
        }
    });
    
  });
</script>
<style>
    #new_address{display:none;}
</style>
@stop
