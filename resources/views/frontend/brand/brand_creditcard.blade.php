 @extends('frontend/layout/frontend_template')
@section('content')

<div class="inner_page_container nomar_bottom">
<div id="nav-icon2">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
  </div>
  <div class="mob_topmenu_back"></div>
<div class="top_menu_port">
	@include('frontend/includes/left_menu')
</div>
    	   <!--my_acct_sec-->
           <div class="my_acct_sec">           
               <div class="container">
               
               <div class="col-sm-10 col-sm-offset-1">
               
               <div class="row">
	      
	        {!! Form::open(['url' => 'brand-creditcards','method'=>'POST', 'files'=>true,  'id'=>'member_form']) !!}
		<input type="hidden" name="customerProfileId" value="<?php echo $carddetails['profile_id']?>" />
               <div class="form_dashboardacct">
               		<h3>Credit Card Information</h3>
                    <div class="bottom_dash clearfix">
                    	<div class="row">
			 @if(Session::has('error'))
			    <div class="alert alert-danger container-fluid">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{!! Session::get('error') !!}</strong>
			    </div>
			  @endif
			  @if(Session::has('success'))
			    <div class="alert alert-success container-fluid">
			    <button type="button" class="close" data-dismiss="alert">×</button>
			    <strong>{!! Session::get('success') !!}</strong>
			    </div>
			  @endif
			 </div>
                        <div class="row">
                        
                       
                        <div class="col-sm-12">
                        
			 <div class="row">
                        <div class="form-group col-sm-6">
			{!! Form::text('card_holder_fname',$carddetails['first_name'],['class'=>'form-control','id'=>'card_holder_fname','placeholder'=>'Card Holder’s First Name']) !!}
                          
                        </div>
			 <div class="form-group col-sm-6">
			{!! Form::text('card_holder_lname',$carddetails['last_name'],['class'=>'form-control','id'=>'card_holder_lname','placeholder'=>'Card Holder’s Last Name']) !!}
                            
                        </div>   
			    
                        </div>
			
			
			<div class="form-group">
                            {!! Form::text('card_number',$carddetails['card_number'],['class'=>'form-control','id'=>'card_number','placeholder'=>'Debit/Credit Card Number']) !!}
                        </div>
                        <div class="row">
                        <div class="form-group col-sm-6">
                           <select class="form-control col-sm-2" name="expiry_month" id="expiry_month">
                                    <option value="">Month</option>
                                    <option value="01" <?php if($carddetails['expiry_month']=="01"){ echo 'selected="selected"';} ?>>Jan (01)</option>
                                    <option value="02" <?php if($carddetails['expiry_month']=="02"){ echo 'selected="selected"';} ?>>Feb (02)</option>
                                    <option value="03" <?php if($carddetails['expiry_month']=="03"){ echo 'selected="selected"';} ?>>Mar (03)</option>
                                    <option value="04" <?php if($carddetails['expiry_month']=="04"){ echo 'selected="selected"';} ?>>Apr (04)</option>
                                    <option value="05" <?php if($carddetails['expiry_month']=="05"){ echo 'selected="selected"';} ?>>May (05)</option>
                                    <option value="06" <?php if($carddetails['expiry_month']=="06"){ echo 'selected="selected"';} ?>>June (06)</option>
                                    <option value="07" <?php if($carddetails['expiry_month']=="07"){ echo 'selected="selected"';} ?>>July (07)</option>
                                    <option value="08" <?php if($carddetails['expiry_month']=="08"){ echo 'selected="selected"';} ?>>Aug (08)</option>
                                    <option value="09" <?php if($carddetails['expiry_month']=="09"){ echo 'selected="selected"';} ?>>Sep (09)</option>
                                    <option value="10" <?php if($carddetails['expiry_month']=="10"){ echo 'selected="selected"';} ?>>Oct (10)</option>
                                    <option value="11" <?php if($carddetails['expiry_month']=="11"){ echo 'selected="selected"';} ?>>Nov (11)</option>
                                    <option value="12" <?php if($carddetails['expiry_month']=="12"){ echo 'selected="selected"';} ?>>Dec (12)</option>
                                  </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <select class="form-control" name="expiry_year" id="expiry_year">
                                  <?php 
									$current_yr = date("Y");
								  	for($i=1;$i<30;$i++){
										$yr  = $current_yr+$i;
								   ?>
                                   	<option value="<?php echo $yr;?>" <?php if($carddetails['expiry_year']==$yr){ echo 'selected="selected"';} ?>><?php echo $yr;?></option>
                                   <?php		
									}
								  ?>
                                    
                                  </select>
                        </div>
                        
                        <div class="form-group col-sm-6">
                            {!! Form::text('company_name',$carddetails['company_name'],['class'=>'form-control','id'=>'company_name','placeholder'=>'Company Name']) !!}
                        </div>
			 <div class="form-group col-sm-6">
                            {!! Form::text('card_shiping_address',$carddetails['card_shiping_address'],['class'=>'form-control','id'=>'card_shiping_address','placeholder'=>'Address ']) !!}
                        </div>   
			<div class="form-group col-sm-6">
                           {!! Form::select('card_country_id', array('' => 'Please select country') +$alldata,$carddetails['card_country_id'], array('id' => 'card_country_id','class'=>'form-control','onchange' => 'getState(this.value,"card")')); !!}
                        </div>    
			<div class="form-group col-sm-6">
                           {!! Form::select('card_state', array('' => 'Please select state') +$allstates,$carddetails['card_state'], array('id' => 'card_state','class'=>'form-control')); !!}
                        </div>      
			 <div class="form-group col-sm-6">
                           {!! Form::text('card_shiping_city',$carddetails['card_shiping_city'],['class'=>'form-control','id'=>'card_shiping_city','placeholder'=>'City']) !!}
                        </div> 
			<div class="form-group col-sm-6">
                          {!! Form::text('card_shipping_postcode',$carddetails['card_shipping_postcode'],['class'=>'form-control','id'=>'card_shipping_postcode','placeholder'=>'Post Code']) !!}
                        </div>
			    
			<div class="form-group col-sm-6">
                          {!! Form::text('card_shipping_phone_no',$carddetails['card_shipping_phone_no'],['class'=>'form-control','id'=>'card_shipping_phone_no','placeholder'=>'Phone']) !!}
                        </div>
			    
			<div class="form-group col-sm-6">
                          {!! Form::text('card_shipping_fax',$carddetails['card_shipping_fax'],['class'=>'form-control','id'=>'card_shipping_fax','placeholder'=>'Fax']) !!}
                        </div>
			    
                       
                        </div>
                        
                        </div>
                       
                        
                        </div>
                        
                    </div>
                    
                    <div class="form_bottom_panel">
                    <a href="<?php echo url();?>/brand-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>
                    <button type="submit" form="member_form" class="btn btn-default green_sub pull-right">Update</button>
                    </div>
                    
               </div>
               
                {!! Form::close() !!} 
	       </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
 </div>
<script>

  
  // When the browser is ready...
  $(function() {

   

    // Setup form validation  //
    $("#member_form").validate({
    
        // Specify the validation rules
        rules: {
      company_name:"required",
      card_holder_fname:"required",
      card_holder_lname: "required",
      card_number: "required",
      expiry_month: "required",
      expiry_year: "required",
      card_shiping_address: "required",
      card_country_id: "required",
      card_state: "required",
      card_shiping_city: "required",
      card_shipping_postcode: "required",
      card_shipping_phone_no: "required",
            
     },
		
        submitHandler: function(form) {
            form.submit();
        }
    });


  });
  
   function getState(country_id,param)
 {
    //alert("country= "+country_id);
    $.ajax({
      url: '<?php echo url();?>/getState',
      method: "POST",
      data: { countryId : country_id ,_token: '{!! csrf_token() !!}'},
      success:function(data)
      {
        //alert(data);
		if(param=="card")
	        $("#card_state").html(data);
		else if (param=='mail') {
		    $("#mailing_state").html(data)
		}else
	        $("#state").html(data);
      }
    });

 }
    
</script>
@stop