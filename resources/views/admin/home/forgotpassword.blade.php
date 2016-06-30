@extends('admin.layout.layout')

@section('content')
<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $( document ).ready(function(){
    // Setup form validation on the #register-form element
    $("#forgot_form").validate({
   
        // Specify the validation rules
        rules: {
            email: {
                    required: true,
                    email: true
                   }
               },
        // Specify the validation error messages
        messages: {
            email: "Please enter valid email"
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>
 
  <div class="tab"><div class="tab_cell"><div class="container-fluid">
    <div class="row">
      <div class="col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading"><i class="fa fa-lock"></i>Forgot Password</div>
          <div class="panel-body">

 @if(Session::has('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('error') !!}</strong>
        </div>
 @endif


            <form class="form-horizontal" method="POST" "id"="forgot_form" action="{!! url('admin/forgotpasswordcheck') !!}">

              <input type="hidden" name="_token" value="{!! csrf_token() !!}">

              <div class="form-group">
                <label class="col-md-4 control-label">E-Mail Address</label>
                <div class="col-md-7">
                  <input type="email" class="form-control" name="email" id="email" value="" autofocus> <!-- {{ old('email') }} -->
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div></div></div>
@endsection

