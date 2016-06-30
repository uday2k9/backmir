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
               
               <div class="row"><div class="form_dashboardacct">
                  <h3>My Address</h3>
                    
                     @if(Session::has('error'))
                    <div class="alert alert-error container-fluid">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! Session::get('error') !!}</strong>
                    </div>
                  @endif
                  @if(Session::has('success'))
                    <div class="alert alert-success container-fluid">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{!! Session::get('success') !!}</strong>
                    </div>
                  @endif
                  
                  
                    <div class="bottom_dash clearfix">
                      
                        <div class="row">
                        <h5 class="text-center">Your addresses are listed below</h5>
                        <?php foreach ($address as $adata){ ?>
                        <div class="col-sm-6 col-md-4">
                        <div class="box_edit_address">
                        <p>
                        <?php echo $adata->address_title?><br>
                        <?php echo $adata->first_name?> <?php echo $adata->last_name?><br>
                        
                        <?php echo $adata->address?><br>
                        <?php echo $adata->city?>, <?php  echo $obj->get_state($adata->zone_id)?><br>
                        <?php echo $obj->get_country($adata->country_id)?>
                         
                        </p>
                        <div class="btn-group">
                        <a href="<?php echo url();?>/edit-brand-shipping-address?id=<?php echo $adata->id?>" class="btn btn-small-green pull-left"><i class="fa fa-pencil-square-o"></i>Edit</a>
                        <?php if($brand_details->address!=$adata->id){ ?>
                        <a href="javascript:void(0)" onclick="Deladdress('<?php echo url();?>/delete-brand-shipping-address?id=<?php echo $adata->id?>')" class="btn btn-small-red pull-right"><i class="fa fa-times"></i>Delete</a>
                            
                        <?php }?>
                        </div> 
                        </div>
                        </div>
                        
                        <?php }?>
                        
                     
                        
                        </div>
                        
                    </div>
                    
                    <div class="form_bottom_panel">
                    <!--<a href="<?php echo url();?>/brand-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>-->
                    <a href="<?php echo url();?>/create-brand-shipping-address" class="green_sub text-center pull-right"><i class="fa fa-plus-circle"></i>New Address</a> 
                    </div>
                    
               </div>
               
               </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
 </div>
    
    <script>
        function Deladdress(url){
            
            var a =confirm("Are you sure to delete this address");
            
            if (a){
               location.href=url;
            }
        }
        
    </script>
 @stop