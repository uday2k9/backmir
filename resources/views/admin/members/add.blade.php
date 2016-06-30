@extends('admin/layout/admin_template')

@section('content')


<script>
  
  // When the browser is ready...
  $(function() {
 
    $("#form_member").validate({
        
        ignore: [],
        // Specify the validation rules
        rules: {
            fname: "required",
            lname: "required",
	    username: "required",
            email: {
                      required: true,
                      email: true
                    },
       
            password: {
                            required: true,
                            minlength:6                            
                        },
            con_password: {
                        required :true,
                      equalTo: "#password",
                  },
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
  
  
  $(document).ready(function(){
   $("#email_error").hide();
    $("#user_name_error").hide();
  });
  
  
 /***** DUPLICATE E-MAIL CHECK ****/

 function emailChecking(email_id)
 {
    if(email_id !='')
    {
        $.ajax({
          url: '<?php echo url();?>/emailChecking',
          method: "POST",
          data: { email : email_id ,_token: '{!! csrf_token() !!}'},
          success:function(data)
          {
            //alert(data);
            if(data == 1 ) // email exist already
            {
                $("#email").val('');
                $("#email_error").show();
            }
            else
            {
                $("#email_error").hide();
            }
          }
        });
    }
 }

/***** DUPLICATE E-MAIL CHECK ****/

 /***** DUPLICATE USER NAME CHECK ****/

 function usernameChecking(user_name)
 {
    if(user_name !='')
    {
        $.ajax({
          url: '<?php echo url();?>/usernameChecking',
          method: "POST",
          data: { user_name : user_name ,_token: '{!! csrf_token() !!}'},
          success:function(data)
          {
            //alert(data);
            if(data == 1 ) // username exist already
            {
                $("#user_name").val('');
                $("#user_name_error").show();
            }
            else
            {
                $("#user_name_error").hide();
            }
          }
        });
    }
 }

  </script>
    
  
 {!! Form::open(['url' => 'admin/add-member','method'=>'POST', 'files'=>true, 'id'=>'form_member', 'autocomplete'=>'off']) !!}
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
                 {!! Form::text('username',null,['class'=>'span8','id'=>'username','onblur' =>'usernameChecking(this.value)']) !!}
		  <label id="user_name_error" class="error">username is already exist.</label>
            </div>
        </div>
	    
      <div class="control-group">
            <label class="control-label" for="basicinput">Password *</label>
            <div class="controls">
                 {!! Form::password('password',['class'=>'span8','id'=>'password']) !!}
                 
            </div>
        </div>
	  
	  <div class="control-group">
            <label class="control-label" for="basicinput">Password *</label>
            <div class="controls">
                 {!! Form::password('con_password',['class'=>'span8','id'=>'con_password']) !!}
                 
            </div>
        </div>
	     
        <div class="control-group">
            <label class="control-label" for="basicinput">Email *</label>
            <div class="controls">
                 {!! Form::text('email',null,['class'=>'span8','id'=>'email' ,'onblur' =>'emailChecking(this.value)']) !!}
		
		  <label id="email_error" class="error">Email-Id is already exist.</label>
            </div>
        </div>
	    
      <!-- <div class="control-group">
            <label class="control-label" for="basicinput">Slug *</label>
            <div class="controls">
               //  {!! Form::text('slug',null,['class'=>'span8','id'=>'slug']) !!}
            </div>
        </div> -->

       <!-- <div class="control-group">
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
            <label class="control-label" for="basicinput">Phone</label>
            <div class="controls">
             {!! Form::text('phone_no',null,['class'=>'span8','id'=>'phone_no']) !!}
            </div>
        </div>

<div class="control-group">
            <label class="control-label" for="basicinput">Profile Image</label>
            <div class="controls">
             {!! Form::file('pro_image',null,['class'=>'btn','id'=>'pro_image','placeholder'=>'Profile Image'])!!}
            
            </div>
        </div>
    
    <div class="form-group">
        {!! Form::submit('Add', ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
@stop


