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
               
               <div class="row"><div class="form_dashboardacct">
               		<h3>Edit Address </h3>
		     @if(Session::has('error'))
                    <div class="alert alert-error container-fluid">
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
		  {!! Form::open(['url' => 'edit-brand-shipping-address','method'=>'POST', 'files'=>true,  'id'=>'member_form']) !!}
                    <div class="bottom_dash special_bottom_pad clearfix">
                    	 
			 <input type="hidden" name="id" value="<?php echo Request::input('id')?>" />
                        <div class="row">
                        
                        <div class="col-sm-12">
                        <div class="row">
			<div class="form-group col-sm-12">
                            {!! Form::text('address_title',$address->address_title,['class'=>'form-control','id'=>'address_title','placeholder'=>'Address Title'])!!}
                          </div>
			    
                       
                          <div class="form-group col-sm-6">
                            {!! Form::text('first_name',$address->first_name,['class'=>'form-control','id'=>'first_name','placeholder'=>'First Name'])!!}
                          </div>
                          <div class="form-group col-sm-6">
                            {!! Form::text('last_name',$address->last_name,['class'=>'form-control','id'=>'last_name','placeholder'=>'Last Name'])!!}
                          </div>
                          
                          <div class="form-group col-sm-6">
                            {!! Form::text('address',$address->address,['class'=>'form-control','id'=>'address1','placeholder'=>'Address 1'])!!}
                          </div>
			     <div class="form-group col-sm-6">
                            {!! Form::text('address2',$address->address2,['class'=>'form-control','id'=>'address2','placeholder'=>'Address 2'])!!}
                          </div> 
			  
			    
			   <div class="form-group col-sm-6">
                             {!! Form::select('country', array('' => 'Please select country') +$alldata,$address->country_id, array('id' => 'country', 'class'=>"form-control",'onchange' => 'getState(this.value,"shipping")')); !!}
                          </div>
			    
			  <div class="form-group col-sm-6">
                             {!! Form::select('zone_id', array('' => 'Please select state') +$allstates,$address->zone_id, array('id' => 'state','class'=>"form-control")); !!}
                          </div>
			    
			 <div class="form-group col-sm-6">
                            {!! Form::text('city',$address->city,['class'=>'form-control','id'=>'city','placeholder'=>'City'])!!}
                          </div>
			    
			<div class="form-group col-sm-6">
                            {!! Form::text('postcode',$address->postcode,['class'=>'form-control','id'=>'postcode','placeholder'=>'Zip code'])!!}
                          </div>
			<div class="form-group col-sm-6">
                            {!! Form::text('phone',$address->phone,['class'=>'form-control','id'=>'phone','placeholder'=>'Phone'])!!}
                          </div>
			  
                          <?php
			 $checked1='';
			 $checked2='';
			  if($brand_details->address==$address->id ||  $countaddr==1) {
			  $checked1='checked="checked"';
			  }elseif($brand_details->address!=$address->id){
			   $checked2='checked="checked"';
			  }
			  
			  
			  ?>
			  
			  
                          <div class="col-sm-12">
                          <p class="pull-left for_lineheight">Default Address</p>
                         
			 
                          <div class="check_box_tab marg_left pull-left">                            
                              <input type="radio" class="regular-checkbox" id="radio-4" name="default_address" value="1" <?php echo  $checked1 ?> >
                              <label for="radio-4">Yes</label>
                          </div>
			   <?php if($countaddr>1){ ?> 
                          <div class="check_box_tab marg_left pull-left">                            
                              <input type="radio" class="regular-checkbox" id="radio-5" name="default_address" value="0" <?php echo  $checked2 ?> >
                              <label for="radio-5">No</label>
                          </div>
                           <?php }?>   
                          </div>
                                               
                        
                       
                        </div>  
                        </div>
                        </div>
                        
                    </div>
                    
                    <div class="form_bottom_panel">
                    <!--<a href="<?php echo url();?>/brand-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>-->
                    <button type="submit" form="member_form" class="btn btn-default green_sub pull-right">Save</button>
                    </div>
                     {!! Form::close() !!}
               </div>
               
               </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
 </div>
    
    <script>
	   function getState(country_id,param)
 {
    //alert("country= "+country_id);
    $.ajax({
      url: '<?php echo url();?>/getState',
      method: "POST",
      data: { countryId : country_id ,_token: '{!! csrf_token() !!}'},
      success:function(data)
      {
        
	        $("#state").html(data);
      }
    });

 } 
	    $(function() {

   

    // Setup form validation  //
    $("#member_form").validate({
    
        // Specify the validation rules
        rules: {
	
		
	    first_name: "required",
	    last_name: "required",
	    address_title: "required",
	    address: "required",
	    country: "required",
	    state: "required",
	    city: "required",
	    postcode: "required",
	    phone :
                {
                    required : true,
                    phoneUS: true
                },
      
      
            
     },
		
        submitHandler: function(form) {
            form.submit();
        }
    });


  });
	    
	   </script>
 @stop