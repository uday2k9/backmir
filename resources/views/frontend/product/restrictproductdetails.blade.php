@extends('frontend/layout/frontend_template')
@section('content')
<?php //echo "<pre/>";print_r($productformfactor);  exit;?>
<div class="inner_page_container">
<div class="header_panel">
          <div class="container">
           <h2>Brands</h2>
             <ul class="breadcrumb">
                <li><a href="{!! url()!!}">Home</a></li>
                <li><a href="{!! url()!!}/brands">Brands</a></li>
                <li>Restricted Access</li>
             </ul>
            </div>
        </div>
 <div class="col-xs-12 text-center noprod_cart pad_bot20">
         <img src="<?php echo url();?>/public/frontend/images/restrictedprod.png" alt="">
         <p class="empt_shpcart">You are not authorised to see our products</p>
         <a href="<?php echo url();?>" class="butt">Return To HomePage</a>
         </div>
  </div>

 @stop