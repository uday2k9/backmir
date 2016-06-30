@extends('frontend/layout/frontend_template')
@section('content')
<?php // print_r($all_sitesetting); exit;?>
<div class="inner_page_container">
    	<div class="header_panel">
        	<div class="container">
        	 <h2>Contact Us</h2>
            </div>
        </div>   
<!-- Start Products panel -->
<div class="products_panel faq_cnt">
<div class="container">
<div class="col-sm-10 col-sm-offset-1 contact_panel">
<h4>Contact Us</h4>
    <div class="row">
			 @if(Session::has('error'))
			    <div class="alert alert-error container">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{!! Session::get('error') !!}</strong>
			    </div>
			  @endif
			  @if(Session::has('success'))
			    <div class="alert alert-success container">
			    <button type="button" class="close" data-dismiss="alert">×</button>
			    <strong>{!! Session::get('success') !!}</strong>
			    </div>
			  @endif
			 </div>
                            
<div class="row">
<div class="col-sm-6">

<p class="common_cnt"><i class="fa fa-map-marker"></i>{!! strip_tags($all_sitesetting['address']); !!}</p>
<p class="common_cnt"><i class="fa fa-mobile"></i>Ph: {!! $all_sitesetting['contact']; !!}</p>
<p class="common_cnt"><i class="fa fa-envelope"></i>Email: <a href="mailto:{!! $all_sitesetting['email']; !!}">{!! $all_sitesetting['email']; !!}</a></p>
</div>
<div class="col-sm-6">
<?php
if($memberdetail->fname){
$name=$memberdetail->fname." ".$memberdetail->lname;
}else{
$name='';

}
?>
 {!! Form::open(['url' => 'contact-us','method'=>'POST', 'files'=>true,  'id'=>'contact_form','class'=>"form-horizontal"]) !!}
<div class="form-group">

{!! Form::text('contact_name',$name,['class'=>'form-control','id'=>'contact_name','placeholder'=>'Full Name']) !!}
</div>
<div class="form-group">

{!! Form::text('contact_email',$memberdetail->email,['class'=>'form-control','id'=>'contact_email','placeholder'=>'Your Email']) !!}
</div>
<div class="form-group">

{!! Form::text('contact_subject',null,['class'=>'form-control','id'=>'contact_subject','placeholder'=>'Subject']) !!}
</div>
<div class="form-group">

    {!! Form::textarea('message',null,['class'=>'form-control','id'=>'message','placeholder'=>'Message']) !!}
</div>
<div class="form-group"><input type="submit" form="contact_form"  class="butt" value="Submit"></div>
{!! Form::close() !!} 
</div>
</div>
</div>
</div>
</div>
<!-- End Products panel --> 
 </div>
    
    <script>

  
  // When the browser is ready...
  $(function() {

   

    // Setup form validation  //
    $("#contact_form").validate({
    
        // Specify the validation rules
        rules: {
      contact_name:"required",
      contact_email: {
                    required : true,
                    email: true
                },
      contact_subject: "required",
      message: "required",
     
     },
		
        submitHandler: function(form) {
            form.submit();
        }
    });


  });
  

    
</script>
@stop