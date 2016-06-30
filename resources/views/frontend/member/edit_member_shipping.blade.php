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
                    <div class="alert alert-error container">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! Session::get('error') !!}</strong>
                    </div>
                  @endif
                  @if(Session::has('success'))
                    <div class="alert alert-success container">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{!! Session::get('success') !!}</strong>
                    </div>
                  @endif
		  {!! Form::open(['url' => 'edit-member-shipping-address','method'=>'POST', 'files'=>true,  'id'=>'member_form']) !!}
                    <div class="bottom_dash special_bottom_pad clearfix">
                    	 
			 <input type="hidden" name="id" value="<?php echo Request::input('id')?>" />
                        <div class="row">
                        
                        <div class="col-sm-12">
                        <div class="row">
                       
                          <div class="form-group col-sm-6">
                            {!! Form::text('first_name',$address->first_name,['class'=>'form-control','id'=>'first_name','placeholder'=>'First Name'])!!}
                          </div>
                          <div class="form-group col-sm-6">
                            {!! Form::text('last_name',$address->last_name,['class'=>'form-control','id'=>'last_name','placeholder'=>'Last Name'])!!}
                          </div>
                          <div class="form-group col-sm-6">
                            {!! Form::text('email',$address->email,['class'=>'form-control','id'=>'email','placeholder'=>'Email'])!!}
                          </div>
                          <div class="form-group col-sm-6">
                            {!! Form::text('address',$address->address,['class'=>'form-control','id'=>'address1','placeholder'=>'Address 1'])!!}
                          </div>
			    
			  
			    
			   <div class="form-group col-sm-6">
                             {!! Form::select('country', array('' => 'Please select country') +$alldata,$address->country_id, array('id' => 'country', 'class'=>"form-control",'onchange' => 'getState(this.value,"shipping")')); !!}
                          </div>
			    
			  <div class="form-group col-sm-6">
                             {!! Form::select('zone_id', array('' => 'Please select state') +$allstates,$address->zone_id, array('id' => 'administrative_area_level_1','class'=>"form-control")); !!}
                          </div>
			    
			 <div class="form-group col-sm-6">
                            {!! Form::text('city',$address->city,['class'=>'form-control','id'=>'locality','placeholder'=>'City'])!!}
                          </div>
			    
			<div class="form-group col-sm-6">
                            {!! Form::text('postcode',$address->postcode,['class'=>'form-control','id'=>'postal_code','placeholder'=>'Zip code'])!!}
                          </div>
			<div class="form-group col-sm-6">
                            {!! Form::text('phone',$address->phone,['class'=>'form-control','id'=>'phone','placeholder'=>'Phone'])!!}
                          </div>
			    <div class="form-group col-sm-6">
                            {!! Form::text('address2',$address->address2,['class'=>'form-control','id'=>'address2','placeholder'=>'Address 2'])!!}
                          </div>
                          
                          <?php if($total_add==0){?>
                              <div class="col-sm-12">
                                <p class="pull-left">Default Address</p>
                                
                                <div class="check_box_tab marg_left pull-left">                            
                                    <input type="radio" class="regular-checkbox" id="radio-4" name="default_address" value="1" checked="checked">
                                    <label for="radio-4">Yes</label>
                                </div>
                              </div>
                          <?php } else { ?>
                              <div class="col-sm-12">
                                  <p class="pull-left for_lineheight">Default Address</p>
                                  
                                  <div class="check_box_tab marg_left pull-left">                            
                                      <input type="radio" class="regular-checkbox" id="radio-4" name="default_address" value="1" <?php if($member_details->address==$address->id) {echo  'checked="checked"';} ?>>
                                      <label for="radio-4">Yes</label>
                                  </div>
                                  <!-- <div class="check_box_tab marg_left pull-left">                            
                                      <input type="radio" class="regular-checkbox" id="radio-5" name="default_address" value="0" <?php if($member_details->address!=$address->id) {echo  'checked="checked"';} ?>>
                                      <label for="radio-5">No</label>
                                  </div> -->
                              </div>
                          <?php } ?>
                         
                                                   
                        
                       
                        </div>  
                        </div>
                        </div>
                        
                    </div>
                    
                    <div class="form_bottom_panel">
                    <!--<a href="<?php echo url();?>/member-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>-->
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
        
	        $("#administrative_area_level_1").html(data);
		if (state!='') {
		    $("#administrative_area_level_1 option").filter(function() {
		    return this.text == state; 
		}).attr('selected', true);
		}
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
	    email: "required",
	    address: "required",
	    country: "required",
	    administrative_area_level_1: "required",
	    city: "required",
	    postal_code: "required",
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
      <script>
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var placeSearch, autocomplete,state;
var componentForm = {
 // street_number: 'short_name',
  //route: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'long_name',
  country: 'long_name',
  postal_code: 'short_name'
};

function initAutocomplete() {
  // Create the autocomplete object, restricting the search to geographical
  // location types.
  autocomplete = new google.maps.places.Autocomplete(
      /** @type {!HTMLInputElement} */(document.getElementById('address1')),
      {types: ['geocode']});

  // When the user selects an address from the dropdown, populate the address
  // fields in the form.
  autocomplete.addListener('place_changed', fillInAddress);
}

// [START region_fillform]
function fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();

  for (var component in componentForm) {
    document.getElementById(component).value = '';
    document.getElementById(component).disabled = false;
  }
document.getElementById("address1").value = place.address_components[0]['long_name'] +" "+ place.address_components[1]['long_name'];
  // Get each component of the address from the place details
  // and fill the corresponding field on the form.
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm[addressType]) {
      var val = place.address_components[i][componentForm[addressType]];
      document.getElementById(addressType).value = val;
      
      if(addressType=='country'){
	
	$("#country option").filter(function() {
	    
	    return this.text == val; 
	}).prop('selected', true);
	$( "#country" ).change();
	
	}
      
      if(addressType=='administrative_area_level_1'){
	state=val;
      } 
      
    }
  }
}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      var circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy
      });
      autocomplete.setBounds(circle.getBounds());
    });
  }
}
// [END region_geolocation]

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo env('GOOGLE_CLIENT_SECRET')?>&signed_in=true&libraries=places&callback=initAutocomplete"
        async defer></script>
 @stop