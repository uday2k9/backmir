@extends('admin.layout')

@section('content')
<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
 
$(document).ready(function(){
 $("#reset_password").validate({
        // Specify the validation rules
        rules: {
                    password: "required",
                    con_password: "required",

                    con_password: {
                                      equalTo: "#password"
                                    }
               },
        // Specify the validation error messages
        messages: {
                    password :" Enter Password",
                    con_password :" Enter Confirm Password Same as Password"
                  }
        
        // submitHandler: function(form) {
        //     form.submit();
        // }
    });

 });
  
  </script>
 
  <div class="tab"><div class="tab_cell"><div class="container-fluid">
    <div class="row">
      <div class="col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading"><i class="fa fa-lock"></i>Reset Password</div>
          <div class="panel-body">

@if(Session::has('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('success') !!}</strong>
        </div>
 @endif

   @if(Session::has('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! Session::get('error') !!}</strong>
        </div>
    @endif

<?php if(isset($admin_email)){$admin_email = $admin_email;}else{$admin_email='';} ?>
            <form class="form-horizontal" method="POST" "id"="reset_password" action="{!! url('admin/updatepassword/'.$admin_email) !!}" > <!--onsubmit="return false;"-->

              <input type="hidden" name="_token" value="{!! csrf_token() !!}">

              <div class="form-group">
                <label class="col-md-4 control-label">Reset Password</label>
                <div class="col-md-7">
                  <input type="password" class="form-control" name="password" id="password" value="" autofocus> <!-- {{ old('email') }} -->
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-4 control-label">Confirm Password</label>
                <div class="col-md-7">
                  <input type="password" class="form-control" name="con_password" id="con_password" value="" autofocus> <!-- {{ old('email') }} -->
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

