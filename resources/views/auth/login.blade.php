@extends('admin.layout.layout')

@section('content')
  <div class="tab"><div class="tab_cell"><div class="container-fluid">
    <div class="row">
      <div class="col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading"><i class="fa fa-lock"></i>Login</div>
          <div class="panel-body">

            @include('admin.partials.errors')
           <!-- For For Got password Success mail -->
            @if(Session::has('success'))
              <div class="alert alert-success">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{!! Session::get('success') !!}</strong>
              </div>
            @endif

              @if(Session::has('error'))
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{!! Session::get('error') !!}</strong>
                </div>
              @endif

            <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="form-group">
                <label class="col-md-4 control-label">E-Mail Address</label>
                <div class="col-md-7">
                  <input type="email" class="form-control" name="email"
                         value="{{ old('email') }}" autofocus>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-4 control-label">Password</label>
                <div class="col-md-7">
                  <input type="password" class="form-control" name="password">
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-7 col-md-offset-4">
                  <div class="checkbox">
                    <!-- <label>
                      <input type="checkbox" name="remember"> Remember Me
                    </label> -->
                    <label>
                      <a href="<?php echo url().'/admin/forgotpassword'?>"> Forgot Password ? </a>
                    </label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <button type="submit" class="btn btn-primary">Login</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div></div></div>
@endsection