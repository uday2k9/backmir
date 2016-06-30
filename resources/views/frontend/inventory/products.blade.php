@extends('frontend/layout/frontend_template')
@section('content')


<div class="inner_page_container nomar_bottom">


    	   <!--my_acct_sec-->
           <div class="my_acct_sec">           
               <div class="container">
               
               <div class="col-sm-12">
               
               
               <div class="row">
               <div class="form_dashboardacct">
               		<h3>Product List</h3>
                    <div class="bottom_dash clearfix">
                    	
                        <div class="row">
                        
                         <div class="col-sm-12">
                        
                         
                          <div class="product_list">
   	  <?php 
        if((count($ingrproducts))>0) 
        {
          foreach($ingrproducts as $each_product)
            {
	    if(count($each_product->ingredientProducts)>0){
      ?>
              <div class="product">
                	<div class="head_section">
                   	  <h2>{!! ucwords($each_product->ingredientProducts->product_name) !!}</h2>
                       <p class="price">$<?php
		      echo $obj->get_minprice($each_product->product_id)?>
		      
		       <?php //echo isset($each_product->formfactorProducts->min_price)?'$'.$each_product->ingredientProducts->formfactorProducts->min_price:"$0";?> </p>
                      </div>
                  <?php //print_r($each_product->formfactorProducts);?>
                  
                  @if($each_product->ingredientProducts->image1!="")
                  <div class="image_section" style="background:url(<?php echo url();?>/uploads/product/{!! $each_product->ingredientProducts->image1 !!}) no-repeat 0 0;background-size:cover;">
       
                      <div class="image_info">
                     
                      <a href="{!! url() !!}/product-details/{!! $each_product->ingredientProducts->product_slug !!}" class="butt butt-green"><img src="<?php echo url();?>/public/frontend/images/icon3.png" alt=""/> View Details </a>
                  
                     
                      </div>
                  </div>
                  @else
                  <div class="image_section" style="background:url(<?php echo url();?>/public/frontend/images/noimage.png) no-repeat 0 0;background-size:cover;">
                    <div class="image_info">
                      
                      <a href="{!! url() !!}/product-details/{!! $each_product->ingredientProducts->product_slug !!}" class="butt butt-green"><img src="<?php echo url();?>/public/frontend/images/icon3.png" alt=""/> View Details </a>
                  
                    </div>
                  </div>
                  @endif
              </div> 

       
        <?php
	}
            }
        }
        else
        {
        ?>
            <div>No Record Found.</div>
        <?php 
        }
        ?>
      

    </div>
                         </div>

                        
                        </div>
                        
                    </div>
                    
                   
                    
               </div>
               
               </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
           
           
	  
	   
	   <?php /*{!! str_replace('/?', '?', $ingrproducts->appends(Input::except('inventory-products'))->render()) !!} */?>
	   
 </div>



@stop