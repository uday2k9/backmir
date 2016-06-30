@extends('frontend/layout/frontend_template')
@section('content')

<div class="inner_page_container nomar_bottom">
<div id="nav-icon2">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
  </div>
  <div class="mob_topmenu_back"></div>

<div class="top_menu_port">
	@include('frontend/includes/left_menu')
</div>

    	   <!--my_acct_sec-->
           <div class="my_acct_sec">           
               <div class="container">
               
               <div class="col-sm-10 col-sm-offset-1">
               
               <div class="row">
		 {!! Form::open(['url' => 'member-account','method'=>'POST', 'files'=>true,  'id'=>'member_form']) !!}
		<div class="form_dashboardacct">
               		<h3>My Account Information</h3>
                    <div class="bottom_dash clearfix">
                    	<div class="row">
			 @if(Session::has('error'))
			    <div class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{!! Session::get('error') !!}</strong>
			    </div>
			  @endif
			  @if(Session::has('success'))
			    <div class="alert alert-success">
			    <button type="button" class="close" data-dismiss="alert">×</button>
			    <strong>{!! Session::get('success') !!}</strong>
			    </div>
			  @endif
			 </div>
                        <div class="row">
                        <h5 class="col-sm-offset-3">Your Personal Details</h5>
                        <div class="col-sm-3 left_acct_img">
                        
				<div class="upload_button_panel img_modify">
				<p class="upload_image">
				<input class="upload_button filesbrand" type="file" name="image" id="image" accept="image/*" style="cursor:pointer;"><span>Upload Profile Avatar</span></p>
				<div class="selectedFiles">
				 <?php if(!empty($member['pro_image'])){?>
				   <img src="<?php echo url();?>/uploads/member/<?php echo $member['pro_image']?>" class="img-responsive" alt="" width="150">
				     <input type="hidden" name="hidden_image" id="hidden_image" value="<?php echo $member['pro_image']?>">
				  <?php } else {?>
				    <img src="<?php echo url();?>/public/frontend/images/newdashimages/acct_imgd.png" class="img-responsive" alt="">
				   <!-- <a href="javascript:void(0);" class="text-center">Upload Image</a>-->
				  <?php } ?>                                
				</div>
			      </div> 
			
                        </div>
                        <div class="col-sm-9">
                        
			<div class="form-group">
                            {!! Form::text('username',$member['username'],['class'=>'form-control','id'=>'username','placeholder'=>'Username (Warning: Cannot be changed once created)', 'aria-describedby'=>'basic-addon2'])!!}
                          </div>
                          <div class="form-group">
                            {!! Form::text('fname',$member['fname'],['class'=>'form-control','id'=>'fname','placeholder'=>'First Name', 'aria-describedby'=>'basic-addon2'])!!}
                          </div>
			    
			    <div class="form-group">
                             {!! Form::text('lname',$member['lname'],['class'=>'form-control','id'=>'lname','placeholder'=>'Last Name', 'aria-describedby'=>'basic-addon2'])!!}
                          </div>
			    
			    
                          <div class="form-group">
                             {!! Form::text('phone_no',$member['phone_no'],['class'=>'form-control','placeholder'=>'Phone Number', 'aria-describedby'=>'basic-addon2'])!!}
                          </div>
			                   
                            <div class="form-group">
                            <h5>Preffered Communication : </h5>
                              <?php 
                                  if($member['preffered_communication']==0){
                                ?>
                                  <label>  {!! Form::radio('preffered_communication', 0,true) !!} E-Mail</label> 
                                <?php
                                }
                                else
                                {
                              ?>
                                <label> {!! Form::radio('preffered_communication', 0) !!} E-Mail</label> 
                              <?php
                                }
                                if($member['preffered_communication']==1){
                              ?>
                                  <label>  {!! Form::radio('preffered_communication', 1,true) !!} Message</label> 
                                <?php
                                }
                              else
                                {
                              ?>
                                <label> {!! Form::radio('preffered_communication', 1) !!} Message</label> 
                              <?php
                                }
                              ?>
                            </div>
			                    <div class="form-group">
                             <span>Total Points : <?php echo $member['user_points']?></span>
                          </div>
                          
                          
                       
                        </div>
                        </div>
                        
                    </div>
                    
                    <div class="form_bottom_panel">
                    <!--<a href="<?php echo url();?>/member-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>-->
                    <button type="submit" form="member_form" class="btn btn-default green_sub pull-right">Save</button>
                    </div>
                    
               </div>
               
               
	       {!! Form::close() !!}
	       </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
 </div>
<script>

  
  // When the browser is ready...
  $(function() {

   

    // Setup form validation  //
    $("#member_form").validate({
    
        // Specify the validation rules
        rules: {
	
      fname: "required",
      lname: "required",
     
			phone_no :
                {
                    required : true,
                    phoneUS: true
                },
      
     },
		
        submitHandler: function(form) {
            form.submit();
        }
    });


  });
  
  
    
</script>

@stop