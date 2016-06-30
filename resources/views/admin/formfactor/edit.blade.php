@extends('admin/layout/admin_template')

@section('content')

 <!-- jQuery Form Validation code -->  
  <script>
  
  // When the browser is ready...
  $(function() {
  
   $.validator.addMethod("greaterThan",function (value, element, param) {

      var $min = $(param);
      if (this.settings.onfocusout) 
      {
        $min.off(".validate-greaterThan").on("blur.validate-greaterThan", function () {
          $(element).valid();
        });
      }
      return parseFloat(value) > parseFloat($min.val());
    }, "Maximum weight must be greater than minimum weight");

    // Setup form validation  //

    $("#form_factor").validate({
    
        // Specify the validation rules
        rules: {
            name: "required",
            price: 
            {
                required : true,
                number: true
            },
            maximum_weight: 
            {
                number: true,
                greaterThan: '#minimum_weight'
            },
            minimum_weight: 
            {
                number: true
            }

        },
        
        // Specify the validation error messages
        messages: {
            name: "Please enter form factor name",
            price: "Please enter valid form factor upcharge"
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>

    {!! Form::model($formfactor,array('method' => 'PATCH','id'=>'form_factor','name'=>'form_factor','files'=>true,'class'=>'form-horizontal row-fluid','route'=>array('admin.formfactor.update',$formfactor->id))) !!}
   
        <div class="control-group">
            <label class="control-label" for="basicinput">Name *</label>
            <div class="controls">
                 {!! Form::text('name',null,['class'=>'span8','id'=>'name']) !!}
            </div>
        </div>

        <div class="control-group">
                <label class="control-label" for="basicinput">Image *</label>

                <div class="controls">
                     {!! Form::file('image',array('class'=>'span8','id'=>'image')) !!}
                    <p><img  src="<?php echo url()?>/uploads/formfactor/{!! $formfactor->image; !!}" class="nav-avatar spec_navavatar"></p>
                    {!! Form::hidden('hidden_image',$formfactor->image,['class'=>'span8']) !!}
                    <p>Please upload 100x100 image for best fit. </p>
                    <p id="image_error" style="color:red;"></p>
                </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="basicinput">Price *</label>
            <div class="controls">
                 {!! Form::text('price',null,['class'=>'span8','id'=>'price']) !!}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="basicinput">Minimum Weight</label>
            <div class="controls">
             {!! Form::text('minimum_weight',null,['class'=>'span8','id'=>'minimum_weight']) !!}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="basicinput">Maximum Weight</label>
            <div class="controls">
                 {!! Form::text('maximum_weight',null,['class'=>'span8','id'=>'maximum_weight']) !!}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="basicinput">Unit</label>
            <div class="controls">
                  <?php
                      if($formfactor->count_unit==2)
                      {
                        $chk_fir='flase';
                        $chk_sec='true';
                      }
                      else if($formfactor->count_unit==1)
                      {
                        $chk_fir='true';
                        $chk_sec='false';
                      }
                      else
                      {
                        $chk_fir='true';
                        $chk_sec='false';
                      }
                  ?>              
                 {!! Form::radio('count_unit','1',$chk_fir,['class'=>'','id'=>'maximum_weight']) !!} Units&nbsp;
                 {!! Form::radio('count_unit','2',$chk_sec,['class'=>'','id'=>'maximum_weight']) !!} Grams
            </div>
        </div>
        


    
    <div class="form-group">
        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}

<script>
$(document).ready(function()
{
   var _URL = window.URL || window.webkitURL;

   $("#image").change(function (e) {

   var file, img;
   var ValidImageTypes = ["image/gif","image/GIF", "image/jpeg","image/JPEG","image/jpg","image/JPG", "image/png", "image/PNG"];

    if ($.inArray(this.files[0].type, ValidImageTypes) < 0) 
    {
        alert($.inArray(this.files[0].type, ValidImageTypes));

        $('#image').val(""); 

        $('#image_error').html('You must upload an image');
    }

    else if ((file = this.files[0])) 
    {
       img = new Image();

       img.onload = function () {

        if(this.width<60 || this.height<60)

        {

          $('#image').val(""); 

          $('#image_error').html('Image dimension should be greater than 60x60');

        }

        else if(this.width>100 || this.height>100){

            $('#image').val(""); 

            $('#image_error').html('Image dimension should be less than 100x100');
        }

        else
        {
          $('#image_error').html(""); 
        }

    };

    img.src = _URL.createObjectURL(file);

    }

   });  
 });

</script>  
@stop