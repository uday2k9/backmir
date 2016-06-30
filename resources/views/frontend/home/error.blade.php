@extends('frontend/layout/frontend_template')
@section('content')
<div class="inner_page_container nopage_back">
    	<div class="header_panel">
        	<div class="container">
        	 <h2>Error</h2>
            </div>
        </div>   
<!-- Start Products panel -->
<div class="products_panel">
	<div class="container">
    	<div class="table_col text-center">
             
            <div class="error_cell">
                <i class="fa fa-exclamation-triangle"></i>
                <p class="oops_div">Something Went Wrong,<span>please try Later</span></p>
                <a class="return_btn" href="<?php echo url();?>">Return to homepage</a>
            </div>
            
        </div>
    </div>
</div>
<!-- End Products panel --> 
 </div>
 @stop