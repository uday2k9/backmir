{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 
@extends('admin/layout/admin_template')

@section('content')

<!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#faq_form").validate({
        
        ignore: [],
        // Specify the validation rules
        rules: {
            question: "required",
            answer: {
                        required: function() 
                        {
                        CKEDITOR.instances.answer.updateElement();
                        }
                    }
        },
        
        // Specify the validation error messages
        messages: {
            question: "Please enter question.",
            answer: "Please enter answer."
        },               

        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>

    
    {!! Form::model($faq,array('method' => 'PATCH','class'=>'form-horizontal row-fluid','route'=>array('admin.faq.update',$faq->id))) !!}
   
    <div class="control-group">
        <label class="control-label" for="basicinput">Question</label>

        <div class="controls">
             {!! Form::text('question',null,['class'=>'span8','id'=>'question']) !!}
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="basicinput">Description</label>

        <div class="controls">
             {!! Form::textarea('answer',null,['class'=>'span8 ckeditor','id'=>'answer']) !!}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            {!! Form::submit('Save', ['class' => 'btn']) !!}
           
             <a href="{!! url('admin/faq')!!}" class="btn">Back</a>
           
        </div>
    </div>
        
    {!! Form::close() !!}
@stop