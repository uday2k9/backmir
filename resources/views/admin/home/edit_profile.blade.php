@extends('admin/layout/admin_template')



@section('content')



<script type="text/javascript">

    $(document).ready(function(){



        $.validator.addMethod("email", function(value, element) 

        { 

        return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 

        }, "Please enter a valid email address.");





        $('#admin_edit_pro').validate({

            rules: {

                    name: "required",

                    email: {

                                required: true,

                                email: true

                            }

                },

                messages: {

                    name: "Please enter your name",

                    email: "Please enter your valid email id"

                }

        });

     });



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

        if(this.width<100 || this.height<100)

        {

          $('#image').val(""); 

          $('#image_error').html('Image dimension should be greater than 100X100');

        }

        else if(this.width>1000 || this.height>1000){

            $('#image').val(""); 

            $('#image_error').html('Image dimension should be less than 1000X1000');

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

    @if(Session::has('success'))

        <div class="alert alert-success">

            <button type="button" class="close" data-dismiss="alert">×</button>

            <strong>{!! Session::get('success') !!}</strong>

        </div>

    @endif

    



       

    {!! Form::model($user, array('method' => 'PATCH','route' => array('admin.home.update', $user->id),'files'=>true,'id'=>'admin_edit_pro','name'=>'admin_edit_pro')) !!}

        <!-- <form class="form-horizontal row-fluid"> -->

        <div class="control-group">

        <label class="control-label" for="basicinput">Name</label>



        <div class="controls">

             {!! Form::text('name',null,['class'=>'span8']) !!}

        </div>

        </div>



        <div class="control-group">

            <label class="control-label" for="basicinput">Email</label>



            <div class="controls">

                {!! Form::text('email',null,['class'=>'span8']) !!}

            </div>

        </div>

        <div class="control-group">

            <label class="control-label" for="basicinput">Admin Icon</label>



            <div class="controls">

                {!! Form::file('image',array('class'=>'form-control','id'=>'image','accept'=>"image/*")) !!}

                <p id="image_error" style="color:red;"></p>

            </div>

            <p class="new_avatar"><img  src="<?php echo url()?>/uploads/admin_profile/{!! $user->admin_icon; !!}" class="nav-avatar"></p>

            {!! Form::hidden('admin_icon',null,['class'=>'span8']) !!}

        </div>





        <div class="control-group">

            <div class="controls">

                <!-- <button type="submit" class="btn">Submit Form</button> -->

                {!! Form::submit('Save', ['class' => 'btn']) !!}

            </div>

        </div>

        {!! Form::close() !!}

 
    @stop