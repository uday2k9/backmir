@extends('frontend/layout/frontend_template')
@section('content')

<div class="inner_page_container">
  <div class="header_panel">
  	<div class="container">
  	 <h2>Brands</h2>
      </div>
  </div>
    
<!-- Start Products panel -->
<div class="products_panel">
	<div class="container">
    <div class="product_list">
    @if(Session::has('error'))
          <div class="alert alert-danger container-fluid">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{!! Session::get('error') !!}</strong>
          </div>
        @endif
   	  @if($all_brand_member)
        @foreach($all_brand_member as $each_brand_member)
          <div class="product">
            	<div class="head_section">
               	  <h2 title="<?php if ($each_brand_member->business_name!=''){echo $each_brand_member->business_name; }else{ echo $each_brand_member->fname.' '.$each_brand_member->lname; }?>"><?php if ($each_brand_member->business_name!=''){echo $each_brand_member->business_name; }else{ echo $each_brand_member->fname.' '.$each_brand_member->lname; }?></h2>
                  </div>
                <div class="image_section" <?php if(($each_brand_member->pro_image!="")  && (file_exists('uploads/brandmember/'.$each_brand_member->pro_image))){ ?>
           	  		style="background:url(<?php echo url();?>/uploads/brandmember/{!! $each_brand_member->pro_image !!}) no-repeat center center; background-size:cover;height:240px;" <?php } else {?> style="background:url(<?php echo url();?>/uploads/brandmember/noimage.png) no-repeat center center;background-size:cover;height:240px;" <?php } ?>>
                  <div class="image_info">
                    <a href="{!! url() !!}/{!! $each_brand_member->slug !!}" class="butt butt-green"><img src="<?php echo url();?>/public/frontend/images/icon3.png" alt=""/> View Details</a>
                  </div>
              </div> 
          </div>          
        @endforeach
      @endif
    </div>
  </div>
</div>
<!-- End Products panel --> 
</div>
@stop