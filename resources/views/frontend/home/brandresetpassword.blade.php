@extends('frontend.layout.frontend_template')

@section('content')
<!-- jQuery Form Validation code -->
<script>
  
  // When the browser is ready...
  $( document ).ready(function(){
    $("#reset_password_form").validate({

       rules: {
                password: {
                            required: true,
                            minlength:6                            
                        },
                con_password: {
                            required: true,
                            equalTo: "#password"
                          }
            },
            messages: {
                password: {
                        required:"Please enter new password",
                        minlength:"Please enter minimum 6 character"
                },
               con_password: "Please enter same password again"
      }

        
    });

  });
  
</script>

    

  
   <div class="brand_login">
        <div class="login_cont">
            <div class="log_inner text-center">
                <h2 class="wow fadeInDown">Reset Password</h2>              
                
                @if(Session::has('error'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! Session::get('error') !!}</strong>
                    </div>
                @endif
                @if(Session::has('success'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! Session::get('success') !!}</strong>
                    </div>
                @endif
                
                

               <?php if(isset($link)){$link = $link;}else{$link='';} ?>
            <form class="form-horizontal" method="POST" id="reset_password_form" action="{!! url('brand-reset-password/'.$link) !!}" > 

              <input type="hidden" name="_token" value="{!! csrf_token() !!}">

              <div class="tot_formlog clearfix">
                <div class="input-group wow slideInLeft md15">
                    <input type="password" class="form-control" name="password" id="password" value=""  placeholder="Password" autofocus> 
                    <span class="input-group-addon glyphicon glyphicon-user" id="basic-addon2"></span>
                </div>
             
                <div class="input-group wow slideInLeft md15">
                  <input type="password" class="form-control" name="con_password" id="con_password" value=""  placeholder="Confirm Password"  autofocus> 
                  <span class="input-group-addon glyphicon glyphicon-user" id="basic-addon2"></span>
                </div>
             
                  <button type="submit" class="wow fadeInUp btn btn-default sub_btn">Update</button>
                </div>
            </form>   
            </div>
        </div>
    </div>

              

@endsection

