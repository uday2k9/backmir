{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')

<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#cms_form").validate({
        
        ignore: [],
        // Specify the validation rules
        rules: {
            title: "required",
            //description: "required",
            description: {
                        required: function() 
                        {
                        CKEDITOR.instances.description.updateElement();
                        }
                    }
            
        },
        
        // Specify the validation error messages
        messages: {
            title: "Please enter title.",
            description: "Please enter description."
        },               

        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>
        {!! Form::open(['url' => 'admin/cms','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'cms_form']) !!}
        <div class="control-group">
                <label class="control-label" for="basicinput">Title *</label>

                <div class="controls">
                     {!! Form::text('title',null,['class'=>'span8','id'=>'title']) !!}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="basicinput">Description *</label>

                <div class="controls">
                     {!! Form::textarea('description',null,['class'=>'span8 ckeditor','id'=>'description']) !!}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="basicinput">Meta Name</label>

                <div class="controls">
                     {!! Form::text('meta_name',null,['class'=>'span8','id'=>'meta_name']) !!}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="basicinput">Meta Description</label>

                <div class="controls">
                     {!! Form::textarea('meta_description',null,['class'=>'span8','id'=>'meta_description']) !!}
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="basicinput">Meta Keyword</label>

                <div class="controls">
                     {!! Form::text('meta_keyword',null,['class'=>'span8','id'=>'meta_keyword']) !!}
                </div>
            </div>
            

            <div class="control-group">
                <div class="controls">
                    {!! Form::submit('Save', ['class' => 'btn']) !!}
                   
                     <a href="{!! url('admin/cms')!!}" class="btn">Back</a>
                   
                </div>
            </div>
        
        {!! Form::close() !!}

    @stop