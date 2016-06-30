@extends('admin/layout/admin_template')

@section('content')
    <script type="text/javascript">

   // var dateToday = new Date();
   //  $(function() {
   //      $( "#dob" ).datepicker({
   //  startDate: dateToday,
   //      dateFormat: "yy-mm-dd",
   //   });

   //      $( "#utopia-dashboard-datepicker" ).datepicker().css({marginBottom:'20px'});

   //      $(".chzn-select").chosen(); $(".chzn-select-deselect").chosen({allow_single_deselect:true});

       
        
   //  });

</script>

<script>
  
  // When the browser is ready...
  $(function() {
 
    $("#form_member").validate({
        
        ignore: [],
        // Specify the validation rules
        rules: {
            fname: "required",
            lname: "required",
            email: {
                      required: true,
                      email: true
                    },
           // gender: "required",
            //dob: "required",
           
            phone_no: 
                    {
                      phoneUS: true,
                      required: true
                    }
            
        },
        
        // Specify the validation error messages
        messages: {
            fname: "Please enter first name.",
            lname: "Please enter last name.",
            email: "Please enter valid email address.",
            //gender: "Please choose gender.",
            //dob: "Please enter date of birth.",
            phone_no: "Please enter valid phone number."
        },               

        submitHandler: function(form) {
            form.submit();
        }
    });

$( "#dob" ).datepicker({
                changeYear: true,
                yearRange: '1920:2015', 
                maxDate: <?php echo date('y/m/d')?>, 
                dateFormat: 'yy-mm-dd'
            });
  });
  
  </script>
    
   {!! Form::model($member,array('method' => 'PATCH','id'=>'form_member','files'=>true,'name'=>'form_member','class'=>'form-horizontal row-fluid','route'=>array('admin.member.update',$member->id))) !!}

    <div class="control-group">
          <label class="control-label" for="basicinput">First Name *</label>
          <div class="controls">
               {!! Form::text('fname',null,['class'=>'span8','id'=>'fname']) !!}
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="basicinput">Last Name *</label>
          <div class="controls">
               {!! Form::text('lname',null,['class'=>'span8','id'=>'lname']) !!}
          </div>
        </div>
      <div class="control-group">
            <label class="control-label" for="basicinput">User Name *</label>
            <div class="controls">
                 {!! Form::text('username',null,['class'=>'span8','id'=>'username']) !!}
            </div>
        </div>
	    
      <div class="control-group">
            <label class="control-label" for="basicinput">Password</label>
            <div class="controls">
                 {!! Form::password('password',['class'=>'span8','id'=>'password']) !!}
                 
            </div>
        </div>
	    
        <div class="control-group">
            <label class="control-label" for="basicinput">Email *</label>
            <div class="controls">
                 {!! Form::text('email',null,['class'=>'span8','id'=>'email']) !!}
            </div>
        </div>
	    
      <!-- <div class="control-group">
            <label class="control-label" for="basicinput">Slug *</label>
            <div class="controls">
               //  {!! Form::text('slug',null,['class'=>'span8','id'=>'slug']) !!}
            </div>
        </div> -->

       <!-- <div class="control-group">
            <label class="control-label" for="basicinput">Gender</label>
            <div class="controls">
             {!! Form::radio('gender','Male') !!} Male
             {!! Form::radio('gender','Female') !!} Female
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="basicinput">Date of Birth</label>
            <div class="controls">
             {!! Form::text('dob',null,['class'=>'span8','id'=>'dob']) !!}
            </div>
        </div>-->
	
	
        <div class="control-group">
            <label class="control-label" for="basicinput">Phone</label>
            <div class="controls">
             {!! Form::text('phone_no',null,['class'=>'span8','id'=>'phone_no']) !!}
            </div>
        </div>

<div class="control-group">
            <label class="control-label" for="basicinput">Profile Image</label>
            <div class="controls">
             {!! Form::file('pro_image',null,['class'=>'btn','id'=>'pro_image','placeholder'=>'Profile Image'])!!}
              <?php if(!empty($member->pro_image)){?>
		<img src="<?php echo url();?>/uploads/member/<?php echo $member->pro_image?>" class="img-responsive" alt="" width="150">
              <?php }?>
            </div>
        </div>
    
    <div class="form-group">
        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
@stop


