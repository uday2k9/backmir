@extends('admin/layout/admin_template')

@section('content')

<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#vitamin_form").validate({
    
        // Specify the validation rules
        rules: {
            name: "required",
            weight: "required"
        },
        
        // Specify the validation error messages
        messages: {
            firstname: "Please enter vitamin name",
            lastname: "Please enter vitamin weight"
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>
        {!! Form::open(['url' => 'admin/vitamin','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'vitamin_form']) !!}
        <div class="control-group">
                <label class="control-label" for="basicinput">Name</label>

                <div class="controls">
                     {!! Form::text('name',null,['class'=>'span8','id'=>'name']) !!}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="basicinput">Weight</label>

                <div class="controls">
                     {!! Form::text('weight',null,['class'=>'span8','id'=>'weight']) !!}
                </div>
            </div>


            <div class="control-group">
                <div class="controls">
                    {!! Form::submit('Save', ['class' => 'btn']) !!}
                   
                     <a href="{!! url('admin/vitamin')!!}" class="btn">Back</a>
                   
                </div>
            </div>
        </div>
        {!! Form::close() !!}

    @stop