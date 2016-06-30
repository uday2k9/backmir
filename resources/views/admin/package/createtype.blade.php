@extends('admin/layout/admin_template')

@section('content')

        {!! Form::open(['url' => 'admin/package/storetype','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'form_factor']) !!}
        <div class="control-group">
            <label class="control-label" for="basicinput">Name *</label>
            <div class="controls">
                 {!! Form::text('name',null,['class'=>'span8','id'=>'name']) !!}
            </div>
        </div>

        <div class="control-group">
                <label class="control-label" for="basicinput">Image</label>

                <div class="controls">
                     {!! Form::file('image',array('class'=>'form-control','id'=>'image','accept'=>"image/*")) !!}
                   
                </div>
        </div>
        

        <div class="control-group">
            <div class="controls">
                {!! Form::submit('Save', ['class' => 'btn']) !!}
                 <a href="{!! url('admin/package/type')!!}" class="btn">Back</a>
            </div>
        </div>
       
        {!! Form::close() !!}

@stop
@section('scripts')
  <!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {   

    // Setup form validation  //
    


    $("#form_factor").validate({
    
        // Specify the validation rules
        rules: {
            name: "required" ,            
            image: "required"       
        },
        
        // Specify the validation error messages
        messages: {
            name: "Please enter package name",
            image: "Please upload a image",
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>  
@stop