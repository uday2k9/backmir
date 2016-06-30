@extends('frontend/layout/frontend_template')
@section('content')
<div class="inner_page_container">
    	<div class="header_panel">
        	<div class="container">
        	 <h2>Brands</h2>
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
<a href="<?php echo url()?>/contact-us" class="reqst_ing pull-right">Request Ingredient</a>
</div>
</div>
<!-- End inventory panel -->
<div class="container">
<div class="inventory_boxes">

<?php foreach($pageindex as $dataindex=>$datacontent){
if(count($datacontent)>0){
?>
        <div class="inv_box">
        <div class="inv_head"><?php echo strtoupper($dataindex)?></div>
        <div class="inv_body">
        
        <ul>
        
        <?php foreach($datacontent as $data){ ?>
        <li><a href=""><?php echo $data->name?></a></li>
       
        <?php  }?>
        </ul>
        </div>
        </div>
<?php } }?>

</div>
</div>
 
 </div>
    
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