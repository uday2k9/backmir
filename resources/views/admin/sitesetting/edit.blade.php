{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 
@extends('admin/layout/admin_template')

@section('content')
    
    <!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  // $(function() {
  //   // Setup form validation on the #register-form element
  //   $("#cms_form").validate({
        
  //       ignore: [],
  //       // Specify the validation rules
  //       rules: {
  //           name: "required",
  //           address: {
  //                       required: function() 
  //                       {
  //                       CKEDITOR.instances.address.updateElement();
  //                       }
  //                   },
  //           value: 
  //                   {
  //                     required: true,
  //                     phoneUS: true
  //                   }
            
  //       },
        
  //       // Specify the validation error messages
  //       messages: {
  //           title: "Please enter title.",
  //           address: "Please enter address.",
  //           value: "Please enter valid phone number."
  //       },               

  //       submitHandler: function(form) {
  //           form.submit();
  //       }
  //   });

  // });
  
  </script>

    {!! Form::model($sitesettings,['method' => 'PATCH','id'=>'cms_form','files'=>true,'class'=>'form-horizontal row-fluid','route'=>['admin.sitesetting.update',$sitesettings->id]]) !!}
   
    <div class="control-group">
        <label class="control-label" for="basicinput">Name</label>

        <div class="controls">
             {!! Form::text('display_name',null,['class'=>'span8','id'=>'display_name']) !!}
        </div>
    </div>


    <div class="control-group">
        <label class="control-label" for="basicinput">Value</label>

        <div class="controls">
             <?php 
             echo  Form::hidden('type',$sitesettings->type);
             //echo $sitesettings->type;
            if($sitesettings->type == 'textarea')
            {
               echo  Form::textarea('value',null,['class'=>'span8','id'=>snake_case($sitesettings->name)]) ;

            }
            else if($sitesettings->type == 'text')
            {
                echo  Form::text('value',null,['class'=>'span8','id'=>snake_case($sitesettings->name)]) ;
            }
            else if($sitesettings->type == 'radio')
            {
                echo "<div class='label_siteadmin pull-left'><label>". Form::radio('value', 'test',true,['id'=>snake_case($sitesettings->name)])."Test</label></div>";
                echo "<div class='label_siteadmin pull-left'><label>". Form::radio('value', 'live',['id'=>snake_case($sitesettings->name)])."Live</label></div>";
                
            }
            elseif($sitesettings->type == 'file')
            {
              echo  Form::file('image',array('class'=>'form-control','id'=>'image','accept'=>"image/*")) ;
              ?>
              <p><span>Image size should be larger than 200x200 </span></p>
              <span  style="color:red" id="image_error"></span>
              <p class="new_avatar"><img  src="<?php echo url()?>/uploads/share_image/{!! $sitesettings->value !!}" class="nav-avatar"></p>

            <?php 
            echo Form::hidden('share_icon',null,['class'=>'span8']);
            }
            ?>
        </div>
    </div>

    

    <div class="control-group">
        <div class="controls">
            {!! Form::submit('Save', ['class' => 'btn']) !!}
           
             <a href="{!! url('admin/sitesetting')!!}" class="btn">Back</a>
           
        </div>
    </div>
        
    {!! Form::close() !!}

    <script>
        /*---------*/
        
         $( document ).ready(function() {       
          
          var _URL = window.URL || window.webkitURL;
         $("#image").change(function (e) {
             var file, img;
             if ((file = this.files[0])) {
                 img = new Image();
                 img.onload = function () {
                    if(this.width<200 || this.height<200)
                   {
                        $('#image').val(""); 
                        sweetAlert("Oops...", "Social image size should be greater than 200X200", "error");
                        //$('#image_error').html("Social image size should be greater than 200X200"); 
                   }
                   else
                   {
                         $('#image_error').html(""); 
                   }
                 };
                 img.src = _URL.createObjectURL(file);
             }
         });   
         })
</script>
@stop