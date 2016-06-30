@extends('frontend.layout.frontend_template')

@section('content')
<script src="https://connect.facebook.net/en_US/all.js"></script>
 
 <script src="https://apis.google.com/js/api:client.js"></script>

<meta name="google-signin-client_id" content="<?php echo env('GOOGLE_CLIENT_ID')?>">
<script>




      var clientId = '<?php echo env('GOOGLE_CLIENT_ID')?>';
      
      var apiKey = '<?php echo env('GOOGLE_CLIENT_SECRET')?>';
      var response=[];
       function attachSignin(element) {
      
       auth2.attachClickHandler(element, {},
	   function(googleUser) {
	     console.log(googleUser.getBasicProfile());
	     
	     //response['_token']='{!! csrf_token() !!}';
	     //response['name']=googleUser.getBasicProfile().getName();
	    // response['email']=googleUser.getBasicProfile().getEmail();
	   
	       $.post( "<?php echo url();?>/account/google",{_token:'{!! csrf_token() !!}',name:googleUser.getBasicProfile().getName(),email:googleUser.getBasicProfile().getEmail(),id:googleUser.getBasicProfile().getId()} , function(response) {
	    window.location.href=response;
	    //console.log("Response: "+response);
	    });
	 
	   }, function(error) {
	    // alert(JSON.stringify(error, undefined, 2));
	   });
     }

var googleUser = {};
  var startApp = function() {
    gapi.load('auth2', function(){
      // Retrieve the singleton for the GoogleAuth library and set up the client.
      auth2 = gapi.auth2.init({
        client_id: '<?php echo env('GOOGLE_CLIENT_ID')?>',
        cookiepolicy: 'single_host_origin',
        // Request scopes in addition to 'profile' and 'email'
        //scope: 'additional_scope'
      });
      attachSignin(document.getElementById('googleSignIn'));
    });
  };
startApp();
   function fbAsyncInit() {
  FB.init({
   appId      : '<?php echo env('FB_CLIENT_ID')?>',
   status     : true, // check login status
   cookie     : true, // enable cookies to allow the server to access the session
   xfbml      : true  // parse XFBML
  });
 }

 function fblogIn() {
    FB.login(
         function(response) {
    if (response.status== 'connected') {
     FB.api('/me?fields=name,email', function(response) {
         console.log(response);
          // console.log('Good to see you, ' + response.email + '.');
	   response._token='{!! csrf_token() !!}';
         $.post( "<?php echo url();?>/account/facebook",response , function(response) {
	 window.location.href=response;
	 //console.log("Response: "+response);
	 });  
  
        });
	
    }
   }
   ,{
 scope: "email,public_profile,user_location"
     }
  );
 }
 fbAsyncInit();
 
 
  function fblogOut() {
  FB.logout(function(response) {
   console.log('logout :: ', response);
   
  });
 }
  // When the browser is ready...
  $(function() {
  
   $.validator.addMethod("email", function(value, element) 
      { 
      return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 
      }, "Please enter a valid email address.");

    // Setup form validation  //

    $("#member_login").validate({
        // Specify the validation rules
        rules: {            
            email: 
            {
                required : true,
                email: true
            },
            password: "required"            
        },
        
        // Specify the validation error messages
        messages: {
            email: {
               required: "Please enter email id",
               email : "Please enter a valid email address."
            },
            price: "Please enter password"
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
                <h2 class="wow fadeInDown">Get Mixin’</h2>              

                  @if(Session::has('error'))
                    <div class="alert alert-danger container">
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
		   <?php
		  Session::forget('success');
		  Session::forget('error');
		  ?>
                <div class="log_btnblock md15">
                <a href="javascript:void(0)" onclick="fblogIn()"><img src="<?php echo url();?>/public/frontend/images/log_fb_big.png" width="167" alt=""></a>
               <a href="javascript:void(0)"  id="googleSignIn"><img src="<?php echo url();?>/public/frontend/images/log_google.png" width="167" alt=""></a>
                </div>
               <!--  <img src="<?php echo url();?>/public/frontend/images/or.png" alt=""> -->
              <form class="form-horizontal" role="form" id="member_login" name="member_login" method="POST" action="{{ url('memberLogin') }}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="tot_formlog clearfix">
                    <div class="input-group wow slideInLeft md15">
                      <input type="text" name="email" id="email" class="form-control" value="{!! $mem_email !!}" placeholder="email" aria-describedby="basic-addon2">
                      <span class="input-group-addon glyphicon glyphicon-user" id="basic-addon2"></span>
                    </div>
                    <div class="input-group wow slideInRight md30">
                      <input type="password" name="password" id="password" class="form-control" placeholder="password" aria-describedby="basic-addon3">
                      <span class="input-group-addon glyphicon glyphicon-lock" id="basic-addon3"></span>
                    </div>
                    <div class="wow fadeInLeft checkbox pull-left">
                        <label><input type="checkbox" name="remember_me" id="remember_me" <?php if($mem_email!=""){ ?> checked <?php } ?> value="1">Keep me logged in!</label>
                    </div>
                    <a href="{!! url() !!}/member-forgot-password" class="wow fadeInRight btn-link pull-right">Forgot your password?</a>
                    <button type="submit" class="wow fadeInUp btn btn-default sub_btn">Submit</button>                    
                </div>
              </form>
                <p class="wow zoomInUp brand_p clearfix">Want a Member Account? <a href="<?php url();?>register">Sign up now!</a></p>
            </div>
            
            
            
        </div>
        <!--login_cont-->
        
        
        
    </div>
    <!--for login page-->
    
    
    
@stop