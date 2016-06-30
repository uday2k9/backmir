@extends('admin/layout/admin_template')

@section('content')
    <script type="text/javascript">

   // var dateToday = new Date();
   //  $(function() {
   //      $( "#dob" ).datepicker({
   //  startDate: dateToday,
   //      dateFormat: "yy-mm-dd",
   //   });

   //      $( "#utopia-dashboard-datepicker" ).datepicker().css({marginBottom:'20px'});

   //      $(".chzn-select").chosen(); $(".chzn-select-deselect").chosen({allow_single_deselect:true});

       
        
   //  });

</script>

<script>
  
  // When the browser is ready...
  $(function() {
 
    $("#form_brand").validate({
        
        ignore: [],
        // Specify the validation rules
        rules: {
            fname: "required",
            lname: "required",
            email: {
                      required: true,
                      email: true
                    },
            //gender: "required",
            //dob: "required",
           
            phone_no: 
                    {
                      phoneUS: true,
                      required: true
                    }
            
        },
        
        // Specify the validation error messages
        messages: {
            fname: "Please enter first name.",
            lname: "Please enter last name.",
            email: "Please enter valid email address.",
            //gender: "Please choose gender.",
            //dob: "Please enter date of birth.",
            phone_no: "Please enter valid phone number."
        },               

        submitHandler: function(form) {
            form.submit();
        }
    });

$( "#dob" ).datepicker({
                changeYear: true,
                yearRange: '1920:2015', 
                maxDate: <?php echo date('y/m/d')?>, 
                dateFormat: 'yy-mm-dd'
            });
  });
  
  </script>
    
   {!! Form::model($brand,array('method' => 'PATCH','id'=>'form_brand','files'=>true,'name'=>'form_brand','class'=>'form-horizontal row-fluid','route'=>array('admin.brand.update',$brand->id))) !!}
<input type="hidden" name="address" value="<?php echo $brand->address ?>"/>
    <div class="control-group">
          <label class="control-label" for="basicinput">Business Name </label>
          <div class="controls">
               {!! Form::text('business_name',null,['class'=>'span8','id'=>'business_name']) !!}
          </div>
        </div>
    
    <div class="control-group">
          <label class="control-label" for="basicinput">First Name *</label>
          <div class="controls">
               {!! Form::text('fname',null,['class'=>'span8','id'=>'fname']) !!}
          </div>
        </div>
    
    
        <div class="control-group">
          <label class="control-label" for="basicinput">Last Name *</label>
          <div class="controls">
               {!! Form::text('lname',null,['class'=>'span8','id'=>'lname']) !!}
          </div>
        </div>
      <div class="control-group">
            <label class="control-label" for="basicinput">User Name *</label>
            <div class="controls">
                 {!! Form::text('username',null,['class'=>'span8','id'=>'username']) !!}
            </div>
        </div>
    
        <div class="control-group">
            <label class="control-label" for="basicinput">Email *</label>
            <div class="controls">
                 {!! Form::text('email',null,['class'=>'span8','id'=>'email']) !!}
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="basicinput">Password</label>
            <div class="controls">
                 {!! Form::password('password',null,['class'=>'span8','id'=>'password']) !!}
            </div>
        </div>
        <!--<div class="control-group">
            <label class="control-label" for="basicinput">Gender</label>
            <div class="controls">
             {!! Form::radio('gender','Male') !!} Male
             {!! Form::radio('gender','Female') !!} Female
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="basicinput">Date of Birth</label>
            <div class="controls">
             {!! Form::text('dob',null,['class'=>'span8','id'=>'dob']) !!}
            </div>
        </div>-->
        
        <div class="control-group">
            <label class="control-label" for="basicinput">Slug *</label>
            <div class="controls">
                 {!! Form::text('slug',null,['class'=>'span8','id'=>'slug']) !!}
                 
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="basicinput">Phone</label>
            <div class="controls">
             {!! Form::text('phone_no',null,['class'=>'span8','id'=>'phone_no']) !!}
            </div>
        </div>
      <div class="control-group">
            <label class="control-label" for="basicinput">Profile Image</label>
            <div class="controls">
             {!! Form::file('pro_image',null,['class'=>'btn','id'=>'pro_image','placeholder'=>'Profile Image'])!!}
              <?php if(!empty($brand->pro_image)){?>
		<img src="<?php echo url();?>/uploads/brandmember/<?php echo $brand->pro_image?>" class="img-responsive" alt="" width="150">
              <?php }?>
            </div>
        </div>
      
      <div class="control-group">
            <label class="control-label" for="basicinput">Brand Website</label>
            <div class="controls">
               {!! Form::text('brand_sitelink',null,['class'=>'span8','id'=>'brand_sitelink','placeholder'=>'Website Url', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
        </div>
      
    <div class="control-group">
            <label class="control-label" for="basicinput">Facebook Url</label>
            <div class="controls">
               {!! Form::text('facebook_url',null,['class'=>'span8','id'=>'facebook_url','placeholder'=>'Facebook Url', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
        </div>
    
    <div class="control-group">
            <label class="control-label" for="basicinput">Twitter Url</label>
            <div class="controls">
               {!! Form::text('twitter_url',null,['class'=>'span8','id'=>'twitter_url','placeholder'=>'Twitter Url', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
    </div>
    
    <div class="control-group">
            <label class="control-label" for="basicinput">Linkedin Url</label>
            <div class="controls">
               {!! Form::text('linkedin_url',null,['class'=>'span8','id'=>'linkedin_url','placeholder'=>'Linkedin Url', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
    </div>
    
    <div class="control-group">
            <label class="control-label" for="basicinput">Youtube Url</label>
            <div class="controls">
               {!! Form::text('youtube_link',null,['class'=>'span8','id'=>'youtube_link','placeholder'=>'Youtube Url', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
    </div>
      
      <div class="control-group">
            <label class="control-label" for="basicinput">About Brand</label>
            <div class="controls">
              {!! Form::textarea('brand_details',null,['class'=>'span8', 'rows'=>5, 'cols'=>150,'id'=>'brand_details','placeholder'=>'Short Description About Brand', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
        </div>
      
    <div class="control-group">
            <label class="control-label" for="basicinput">AuthorizeNet Profile ID</label>
            <div class="controls">
               {!! Form::text('auth_profile_id',null,['class'=>'span8','id'=>'auth_profile_id','placeholder'=>'AuthorizeNet Profile ID', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
    </div>
    
    <div class="control-group">
            <label class="control-label" for="basicinput">AuthorizeNet Payment Profile ID</label>
            <div class="controls">
               {!! Form::text('auth_payment_profile_id',null,['class'=>'span8','id'=>'auth_payment_profile_id','placeholder'=>'AuthorizeNet Payment Profile ID', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
    </div>
    
    <div class="control-group">
            <label class="control-label" for="basicinput">AuthorizeNet Address ID</label>
            <div class="controls">
               {!! Form::text('auth_address_id',null,['class'=>'span8','id'=>'auth_address_id','placeholder'=>'AuthorizeNet Address ID', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
    </div>
    
    <div class="control-group">
            <label class="control-label" for="basicinput">Verification Documents</label>
            <div class="controls">
             {!! Form::file('government_issue',null,['class'=>'btn','id'=>'government_issue','placeholder'=>'Issue Id'])!!}
              <?php if(!empty($brand->government_issue)){?>
		<a href="<?php echo url();?>/uploads/brand_government_issue_id/<?php echo $brand->government_issue?>">Download</a>
              <?php }?>
            </div>
        </div>
    
    
    <div class="control-group">
            <label class="control-label" for="basicinput">Articles of Incorporation / Any other document proving business ownership</label>
            <div class="controls">
             {!! Form::file('business_doc',null,['class'=>'btn','id'=>'business_doc','placeholder'=>'Business Doc'])!!}
              <?php if(!empty($brand->business_doc)){?>
		<a href="<?php echo url();?>/uploads/brandmember/business_doc/<?php echo $brand->business_doc?>">Download</a>
              <?php }?>
            </div>
        </div>
    
    <div class="control-group">
            <label class="control-label" for="basicinput">Call DateTime</label>
            <div class="controls">
               {!! Form::text('call_datetime',null,['class'=>'span8','id'=>'call_datetime','placeholder'=>'Call Date Time', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
    </div>
    
    
 
    
    
    <div class="control-group">
            <label class="control-label" for="basicinput">Shipping Firstname</label>
            <div class="controls">
               {!! Form::text('first_name',$baddress->first_name,['class'=>'span8','id'=>'first_name','placeholder'=>'Shipping Firstname', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
    </div>
    
    <div class="control-group">
            <label class="control-label" for="basicinput">Shipping Lastname</label>
            <div class="controls">
               {!! Form::text('last_name',$baddress->last_name,['class'=>'span8','id'=>'first_name','placeholder'=>'Shipping Lastname', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
    </div>
    
     <div class="control-group">
            <label class="control-label" for="basicinput">Address1</label>
            <div class="controls">
               {!! Form::text('address1',$baddress->address,['class'=>'span8','id'=>'address1','placeholder'=>'Address1', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
    </div>
     
     <div class="control-group">
            <label class="control-label" for="basicinput">Address2</label>
            <div class="controls">
               {!! Form::text('address2',$baddress->address2,['class'=>'span8','id'=>'address2','placeholder'=>'Address2', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
    </div>
     <div class="control-group">
            <label class="control-label" for="basicinput">Country</label>
            <div class="controls">
               {!! Form::select('country_id', array('' => 'Please select country') +$alldata,$baddress->country_id, array('id' => 'country_id','onchange' => 'getState(this.value,"shipping")')); !!}
            </div>
    </div>
    <div class="control-group">
            <label class="control-label" for="basicinput">State</label>
            <div class="controls">
               {!! Form::select('zone_id', array('' => 'Please select state') +$allstates,$baddress->zone_id, array('id' => 'state')); !!}
            </div>
    </div>
    
    <div class="control-group">
            <label class="control-label" for="basicinput">City</label>
            <div class="controls">
                {!! Form::text('city',$baddress->city,['class'=>'span8','id'=>'city','placeholder'=>'City', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
    </div>
    
    <div class="control-group">
            <label class="control-label" for="basicinput">Post Code</label>
            <div class="controls">
                {!! Form::text('postcode',$baddress->postcode,['class'=>'span8','id'=>'postcode','placeholder'=>'Post Code', 'aria-describedby'=>'basic-addon2'])!!}
            </div>
    </div>
    
    
    <div class="form-group">
        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
    
    <script type="text/javascript">

 
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


