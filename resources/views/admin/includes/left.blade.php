<?php 
$open = "class='collapsed'";    
$open1 = "class='unstyled collapse'";
    if(!isset($ingredient_class))
        $ingredient_class = '';
    if(!isset($member_class))
        $member_class = '';
    if(!isset($brand_class))
        $brand_class = '';
     if(!isset($home_class))
        $home_class = '';
    if(!isset($cms_class))
        $cms_class = '';
    if(!isset($sitesetting_class))
        $sitesetting_class = '';
    if(!isset($product_class))
        $product_class = '';
    if(!isset($coupon_class))
        $coupon_class = '';
    if(!isset($order_class))
        $order_class = '';
    if(!isset($package_class))
        $package_class = '';
    if(!isset($formfactor_class))
        $formfactor_class = '';
    if(!isset($shipment_class))
        $shipment_class = '';
    if(!isset($package_type_class))
        $package_type_class = '';
    //echo "rr= ".$member_class;
    if($member_class == 'active' || $brand_class =='active')
    {
        $open = '';
        $open1 = "class='unstyled in collapse'";
    }
// echo $open ;
// echo $open1;
?>
<div class="sidebar">
    <ul class="widget widget-menu unstyled">
        <li class="{!! $home_class !!}"><a href="<?php echo url().'/admin/home'?>"><i class="menu-icon icon-dashboard"></i>Dashboard</a></li>
        <li class="{!! $cms_class !!}"><a href="<?php echo url().'/admin/cms'?>"><i class="menu-icon icon-bolt"></i>Content Management </a></li>
        <li class=""><a href="<?php echo url().'/admin/faq'?>"><i class="menu-icon fa fa-question"></i>FAQ Management </a></li>
        <li class="{!! $sitesetting_class !!}"><a href="<?php echo url().'/admin/sitesetting'?>"><i class="menu-icon fa fa-code"></i>Site Settings </a></li>
        <!-- <li><a href="<?php echo url().'/admin/vitamin'?>"><i class="menu-icon icon-bullhorn"></i>Vitamins Management </a></li> -->
    </ul>

    <?php   
        // Reset all variable to null
        $package_type_sub_class="";
        $form_sub_class="";
        $package_sub_class="";
        $ingredient_sub_class="";
        $shipment_sub_class="";     
        if(($module_head=='Package Type') || ($module_head=='Form Factor') || ($module_head=='Packages') || ($module_head=='Ingredient') || ($module_head=='Shipment Package') || ($module_head=='Add Package') || ($module_head=='Update Package') || ($module_head=='Add Form Factor') || ($module_head=='Edit Form Factor') || ($module_head=='Add ingredient') || ($module_head=='Edit Ingredient') || ($module_head=='Add Package Type') || ($module_head=='Update Package Type') || ($module_head=='Add Shipment Package') || ($module_head=='Edit Shipment Package'))
        {
            $add_class="";            
        }
        else
        {
            $add_class="collapse";            
        } 

        if($module_head =='Package Type' || $module_head =='Add Package Type' || $module_head =='Update Package Type')
        {
           $package_type_sub_class="active"; 
        }
        else if($module_head =='Form Factor' || $module_head =='Add Form Factor' || $module_head =='Edit Form Factor')
        {
           $form_sub_class="active"; 
        }
        else if($module_head =='Packages' || $module_head =='Add Package' || $module_head =='Update Package')
        {
           $package_sub_class="active"; 
        }
        else if($module_head =='Ingredient' || $module_head =='Add ingredient' || $module_head =='Edit Ingredient')
        {
           $ingredient_sub_class="active"; 
        }
        else if($module_head =='Shipment Package' || $module_head =='Add Shipment Package' || $module_head =='Edit Shipment Package')
        {
           $shipment_sub_class="active"; 
        }
        else 
        {
            $package_type_sub_class="";
            $form_sub_class="";
            $package_sub_class="";
            $ingredient_sub_class="";
            $shipment_sub_class="";
        }
    ?>
    
    <!--/.widget-nav-->
    <ul class="widget widget-menu unstyled">
        <li><a class="collapsed" data-toggle="collapse" href="#toggleManagement"><i class="menu-icon icon-cog">
        </i><i class="icon-chevron-down pull-right"></i><i class="icon-chevron-up pull-right">
        </i>Management</a>
            <ul id="toggleManagement" class="{{ $add_class }} unstyled">
                <li class="{!! $formfactor_class !!}"><a class="<?php echo $form_sub_class;?>" href="<?php echo url().'/admin/form-factor'?>"><i class="menu-icon icon-bullhorn"></i>Form Factor</a></li>
                <li class="{!! $ingredient_class !!}"><a class="<?php echo $ingredient_sub_class;?>" href="<?php echo url().'/admin/ingredient-list'?>"><i class="menu-icon icon-bullhorn"></i>Ingredients  </a></li>
                <li class="{!! $package_type_class !!}"><a class="<?php echo $package_type_sub_class;?>" href="<?php echo url().'/admin/package/type'?>"><i class="menu-icon icon-bullhorn"></i>Product Package Type  </a></li>
                <li class="{!! $package_class !!}"><a class="<?php echo $package_sub_class;?>" href="<?php echo url().'/admin/package'?>"><i class="menu-icon icon-gift"></i>Product Package </a></li>                
                <li class="{!! $shipment_class!!}"><a class="<?php echo $shipment_sub_class;?>" href="<?php echo url().'/admin/shipment-package'?>"><i class="menu-icon icon-bullhorn"></i>Shippment Package </a></li>
            </ul>
        </li>   
        <li class="{!! $product_class !!}"><a href="<?php echo url().'/admin/product-list/0'?>"><i class="menu-icon icon-bullhorn"></i>Products</a></li>        
        <li><a class="collapsed" data-toggle="collapse" href="#togglePages"><i class="menu-icon icon-cog">
        </i><i class="icon-chevron-down pull-right"></i><i class="icon-chevron-up pull-right">
        </i>All Users </a>
            <ul id="togglePages" class="collapse unstyled">
                <li class="{!! $member_class !!}"><a href="<?php echo url().'/admin/member'?>"><i class="icon-inbox"></i>Members </a></li>
                <li class="{!! $brand_class !!}"><a href="<?php echo url().'/admin/brand'?>"><i class="icon-inbox"></i>Brands </a></li>
            </ul>
        </li>

        <li><a class="collapsed" data-toggle="collapse" href="#toggleOrders"><i class="menu-icon icon-cog">
        </i><i class="icon-chevron-down pull-right"></i><i class="icon-chevron-up pull-right">
        </i>All Orders </a>
            <ul id="toggleOrders" class="collapse unstyled">
                <li class="{!! $member_class !!}"><a href="<?php echo url().'/admin/orders'?>"><i class="icon-inbox"></i>Regular Orders </a></li>
                <li class="{!! $brand_class !!}"><a href="<?php echo url().'/admin/wholesale-orders'?>"><i class="icon-inbox"></i>Wholesale  Orders</a></li>
            </ul>
        </li>

            <li><a href="<?php echo url().'/admin/subscription'?>"><i class="menu-icon icon-bullhorn"></i>Subscriptions</a></li>
            <li class="{!! $coupon_class !!}"><a href="<?php echo url().'/admin/coupon'?>"><i class="menu-icon icon-bullhorn"></i>Coupon</a></li>
    </ul>
    
    <!-- <ul class="widget widget-menu unstyled">
        <li><a href="ui-button-icon.html"><i class="menu-icon icon-bold"></i> Buttons </a></li>
        <li><a href="ui-typography.html"><i class="menu-icon icon-book"></i>Typography </a></li>
        <li><a href="form.html"><i class="menu-icon icon-paste"></i>Forms </a></li>
        <li><a href="table.html"><i class="menu-icon icon-table"></i>Tables </a></li>
        <li><a href="charts.html"><i class="menu-icon icon-bar-chart"></i>Charts </a></li>
    </ul> -->

    <!--/.widget-nav-->
    <ul class="widget widget-menu unstyled">
       <!--  <li><a class="collapsed" data-toggle="collapse" href="#togglePages"><i class="menu-icon icon-cog">
        </i><i class="icon-chevron-down pull-right"></i><i class="icon-chevron-up pull-right">
        </i>More Pages </a>
            <ul id="togglePages" class="collapse unstyled">
                <li><a href="other-login.html"><i class="icon-inbox"></i>Login </a></li>
                <li><a href="other-user-profile.html"><i class="icon-inbox"></i>Profile </a></li>
                <li><a href="other-user-listing.html"><i class="icon-inbox"></i>All Users </a></li>
            </ul>
        </li> -->
        <li><a href="<?php echo url();?>/auth/logout"><i class="menu-icon icon-signout"></i>Logout </a></li>
    </ul>
</div>