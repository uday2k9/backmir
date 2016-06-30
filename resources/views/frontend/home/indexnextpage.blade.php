@extends('frontend/layout/ajaxfrontend_template')
@section("content")
    <div class="container">
    <div class="product_list">
    <?php foreach($products as $product){ ?>
      <div class="product">
            <div class="head_section">
              <h2 title="<?php echo $product->product_name?>"><?php echo $product->product_name?></h2>
              <p class="price">Starting at <?php echo '$'.number_format($product->min_price,2);?> </p>
            </div>
            <div class="image_section" style="background:url('<?php echo url();?>/uploads/product/home_thumb/<?php echo $product->image1?>');background-size:cover;height:240px;">
                
                <div class="image_info">
                   <!--  <a href="<?php //echo url();?>/product-details/{!! $product->product_slug !!}" class="butt cart"><img src="<?php //echo url();?>/public/frontend/images/icon2.png" alt=""/> Add to cart</a> -->
                    <a href="<?php echo url();?>/product-details/{!! $product->product_slug !!}" class="butt butt-green"><img src="<?php echo url();?>/public/frontend/images/icon3.png" alt=""/> Get Details</a>
                </div>
          </div> 
      </div>
      <?php  }?>
    </div>
   
      <?php echo $obj->paginate_function($item_per_page, $current_page, $total_records, $total_pages)?>
     
            <script>
            $(document).ready(function(){
                  $("#fromtorec").html('<?php echo $from?>–<?php echo $to?>');
                  $("#totalrec").html('<?php echo $total_records?>');
                  
            });
            </script>
@stop