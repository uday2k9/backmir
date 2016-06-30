@extends('frontend.layout.frontend_template')

@section('content')
<!-- jQuery Form Validation code -->
<script>
  
  // When the browser is ready...
  $( document ).ready(function(){

    $.validator.addMethod("email", function(value, element) 
    { 
    return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 
    }, "Please enter a valid email address.");


    $("#change_mem_form").validate({

       rules: {
              email:{
                      required: true,
                      email:true,
                    }
            },
            messages: {
                email: {
                        required:"Please enter email id"
                }
      }

        
    });

  });
  
</script>

    

  
   <div class="brand_login">
        <div class="login_cont">
            <div class="log_inner text-center">
                <h2 class="wow fadeInDown">Forgot Password</h2>              
                
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
                
                {!! Form::open(array('url'=>'member-forgot-password','method'=>'POST','id' =>'change_mem_form')) !!}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="tot_formlog clearfix">
                        <div class="input-group wow slideInLeft">
                        {!! Form::text('email',NULL,array('class'=>'form-control','id'=>'email','placeholder'=>'Email')) !!}
                          <span class="input-group-addon glyphicon glyphicon-user" id="basic-addon2"></span>
                        </div>
                          {!! Form::submit('Send', ['class' => 'wow fadeInUp btn btn-default sub_btn']) !!}     
                    </div>
                {!! Form::close() !!}     
            </div>
        </div>
    </div>

              

@endsection

