@extends('frontend/layout/frontend_template')
@section('content')
<div class="inner_page_container">
    	<div class="header_panel">
        	<div class="container">
        	 <h2>Inventory</h2>
            </div>
        </div>   
<!-- Start inventory panel -->
<div class="inventory_panel smaller_inv">
<div class="container">
<ul>
<?php foreach($pageindex as $dataindex=>$datacontent){
if(count($datacontent)>0){
?>
<li class="<?php if($dataindex=='a'){echo 'active';}?>"><a href=""><?php echo strtoupper($dataindex)?></a></li>
<?php }}?>
</ul>
<?php if($memberdetail->fname){
$name=$memberdetail->fname." ".$memberdetail->lname;
}else{
$name='';

}?>
<!-- <a href="<?php echo url()?>/contact-us" class="reqst_ing pull-right">Request Ingredient</a> -->
  <a href="#myModal" class="btn btn-lg btn-primary reqst_ing pull-right" data-toggle="modal">Request Ingredient</a>
    <div id="myModal" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content clearfix">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">Request Ingredient</h4>
              </div>
              <div class="modal-body">
                <div class="col-sm-12">
                  {!! Form::open(['url' => 'inventory','method'=>'POST', 'files'=>true,  'id'=>'req_ing','class'=>"form-horizontal"]) !!}
                    <div class="form-group">

                    {!! Form::text('name',NULL,['class'=>'form-control','id'=>'name','placeholder'=>'Full Name']) !!}
                    </div>
                    <div class="form-group">

                    {!! Form::text('contact_email',$memberdetail->email,['class'=>'form-control','id'=>'contact_email','placeholder'=>'Your Email']) !!}
                    </div>
                     <div class="form-group">

                    {!! Form::text('brand_name',$name,['class'=>'form-control','id'=>'brand_name','placeholder'=>'Brand Name']) !!}
                    </div>
                    <div class="form-group">

                    {!! Form::text('request_ing',null,['class'=>'form-control','id'=>'request_ing','placeholder'=>'Request Ingredient']) !!}
                    </div>
                    
                    <div class="form-group"><input type="submit" class="butt" value="Submit"></div>
                    {!! Form::close() !!} 
                    </div>
                </div>
                
          </div>
      </div>
    </div>
</div>
</div>
<!-- End inventory panel -->
<div class="container">
  @if(Session::has('error'))
    <div class="alert alert-danger mt20">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{!! Session::get('error') !!}zxcxc</strong>
    </div>
  @endif

  @if(Session::has('success'))
    <div class="alert alert-success mt20">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{!! Session::get('success') !!}</strong>
    </div>
  @endif
<div class="inventory_boxes">

<?php foreach($pageindex as $dataindex=>$datacontent){
if(count($datacontent)>0){
?>
        <div class="inv_box">
        <div class="inv_head"><?php echo strtoupper($dataindex)?></div>
        <div class="inv_body">
        
        <ul>
        
        <?php foreach($datacontent as $data){ ?>
        <li><a href="<?php echo url()?>/inventory-products/<?php echo $data->id?>"><?php echo $data->name?></a></li>
       
        <?php  }?>
        </ul>
        </div>
        </div>
<?php } }?>

</div>
</div>
 
 </div>

<script>


  
  // When the browser is ready...
  $(function() {
    // Setup form validation  //
    $('#myModal').on('shown.bs.modal',function(){

      $("#req_ing").validate({
      // Specify the validation rules
      rules: {
        name:"required",
        contact_email: {
                      required : true,
                      email: true
                  },
        brand_name:"required",
        request_ing: "required"
       },
        submitHandler: function(form) {
            form.submit();
        }
      });
    });

  });
    
</script>
<script>
  $(document).on('click','.inventory_panel li a',function(e){
	  e.preventDefault();
	  var $this=$(this);
	  var this_text=$this.text();
	  $('.inventory_panel li').removeClass('active');
	  $this.parent().addClass('active');
	  $('.inv_box').removeClass('active');
	  $('.inv_box').each(function(index, element) {
      	  var $this=$(this);
		  var this_subtext=$this.find('.inv_head').text();
		  if(this_text==this_subtext){
			$this.addClass('active');
			$('html, body').animate({
				scrollTop: $this.offset().top-80
		  }, 2000);  
		  }		  
      });
  });
  </script>
@stop