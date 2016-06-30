@extends('frontend/layout/frontend_template')
@section('content')

<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {

    $.validator.addMethod("email", function(value, element) 
    { 
    return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 
    }, "Please enter a valid email address.");

    // Setup form validation  //
    $("#member_form").validate({
    
        // Specify the validation rules
        rules: {
            email: 
            {
                required : true,
                email: true
            },
            user_name : 
            {
                required : true,
                alphanumeric: true
            },
            password: {
                            required: true,
                            minlength:6                            
                        },
            con_password: {
                        required :true,
                      equalTo: "#password",
                  },
            
            //agree: "required",
            
        },
        
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>

<!--for login page-->
    <div class="brand_login">
    	
        <!--login_cont-->
        <div class="login_cont">
            <div class="log_inner text-center">
                <h2 class="wow fadeInDown">Sign Up</h2>
                @if(Session::has('success'))
                    <div class="alert alert-success container">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! Session::get('success') !!}</strong>
                    </div>
                @endif
                <!-- <div class="log_btnblock md15">
                    <a href=""><img src="<?php echo url();?>/public/frontend/images/log_google.png" alt=""></a>
                    <a href=""><img src="<?php echo url();?>/public/frontend/images/log_fb.png" alt=""></a>
                </div>
                <img src="<?php echo url();?>/public/frontend/images/or.png" alt=""> -->
                {!! Form::open(['url' => 'register','method'=>'POST', 'files'=>true, 'id'=>'member_form', 'autocomplete'=>'off']) !!}
                <div class="row signup_form_panel">
                	<div class="col-sm-2 no_disp_mob">&nbsp;</div>
                	<div class="col-sm-4">
                    	
                        <div class="input-group wow slideInRight md15">
                            {!! Form::text('user_name',null,['class'=>'form-control','placeholder'=>'Username', 'tabindex'=>1, 'id'=>'user_name','aria-describedby'=>'basic-addon2','onblur' =>'usernameChecking(this.value)', 'autocomplete'=>'off'])!!}

                        <label id="user_name_error" class="error">username is already exist.</label>
                        </div>
                        
                        <div class="input-group wow slideInLeft md15">
                            {!! Form::password('password',['class'=>'form-control','placeholder'=>'Password', 'tabindex'=>3, 'aria-describedby'=>'basic-addon3', 'id'=>'password', 'autocomplete'=>'off'])!!}
                    	</div>
                        
                        
                    
                    </div>
                    <div class="col-sm-4">
                    	<div class="input-group wow slideInLeft md15">
                            {!! Form::text('email',null,array('class'=>'form-control','id'=>'email','placeholder'=>'Email', 'tabindex'=>2, 'aria-describedby'=>'basic-addon2','onblur' =>'emailChecking(this.value)', 'autocomplete'=>'off'))!!}
                            
                            <label id="email_error" class="error">Email-Id is already exist.</label>
                        </div>
                       
                        <div class="input-group wow slideInRight md15">
     {!! Form::password('con_password',array('class'=>'form-control','placeholder'=>'Confirm Password', 'aria-describedby'=>'basic-addon3', 'tabindex'=>4, 'autocomplete'=>'off'))!!}
                    	</div>
                        
                    </div>
                    <div class="col-sm-2 no_disp_mob">&nbsp;</div>
                    <div class="wow fadeInLeft checkbox col-sm-offset-2 col-sm-8">
                        <!-- <label><input type="checkbox">I agree to the terms and conditions of miramix.com</label> -->
                         <label style="padding-left:0"><strong style="color: red;margin-right: 5px;">*</strong>I agree to the <a href="<?php echo url();?>/terms-condition" target="_blank">terms and conditions</a> of miramix.com</label>
                    </div>
                </div>
                <div class="row  signup_form_panel text-center md30">
                	<div class="col-sm-4 no_disp_mob">&nbsp;</div>
                	<div class="col-sm-4 mu15"><button type="submit" class="wow fadeInUp btn btn-default sub_btn">Sign Up</button></div>
                    <div class="col-sm-4 no_disp_mob">&nbsp;</div>                    
                </div>
                {!! Form::close() !!}
                <p class="wow zoomInUp brand_p clearfix">Already have an Account? <a href="<?php url();?>memberLogin">Sign in now!</a></p>
            </div>
        </div>
        <!--login_cont-->
        
    </div>
    <!--for login page-->

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
        $("#state").html(data);
      }
    });

 }

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

/***** DUPLICATE USER NAME CHECK ****/

</script>
<style>
#email_error
{
    display: none;
}
#user_name_error
{
  display: none;  
}
</style>
    @stop