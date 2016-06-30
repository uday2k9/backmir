@extends('admin/layout/admin_template')

@section('content')
<script type="text/javascript" src="http://davidstutz.github.io/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="http://davidstutz.github.io/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" type="text/css"/>

        {!! Form::open(['url' => 'admin/package/updatetype','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'form_factor']) !!}
        {!! Form::hidden('package_id',$package->id,['class'=>'span8','id'=>'package_id']) !!}
        
        <div class="control-group">
            <label class="control-label" for="basicinput">Name *</label>
            <div class="controls">
                 {!! Form::text('name',$package->name,['class'=>'span8','id'=>'name']) !!}
            </div>
        </div>

        <div class="control-group">
                <label class="control-label" for="basicinput">Image</label>

                <div class="controls">
                     {!! Form::file('image',array('class'=>'form-control','id'=>'image','accept'=>"image/*")) !!}
                     @if($package->image!='')
                     <p><img  src="<?php echo url()?>/uploads/package/type/{!! $package->image; !!}" class="nav-avatar spec_navavatar"></p>
                     @else
                     <p><img  src="<?php echo url()?>/uploads/no-image-found.jpg" class="nav-avatar spec_navavatar"></p>
                     @endif
                     
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
            name: "required"                
        },
        
        // Specify the validation error messages
        messages: {
            name: "Please enter package name"           
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script> 
@stop