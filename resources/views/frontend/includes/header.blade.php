<?php
$about_us = '';
if(isset($getHelper) && $getHelper->getCmsLink(1) !='')
{
$getcms_about_us = $getHelper->getCmsLink(1);
$about_us = $getcms_about_us->slug;
}


?>
<!doctype html>
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><html lang="en" class="no-js"> <![endif]-->
<html lang="en">
<head>
<!-- Basic -->
<?php 
if(Request::segment(1) == '' || Request::segment(1) == 'about-us' || Request::segment(1) == 'faqs')
{
?>
	<title>Custom Supplements | Private Label | Whole Food Supplements - Miramix</title>
	<!-- Define Charset -->
	<meta charset="utf-8">
	<!-- Responsive Metatag -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!-- Page Description and Author -->

	<meta name="description" content="Miramix helps to get yor custom supplements, whole food supplements or private label supplements product that is unique as well fits to your needs.">
	<meta name="keywords" content="Custom Supplements,Whole Food Supplements,Private Label Supplements">
	<?php 
}
else
{
	?>
	<title><?php echo (isset($title) ? $title:'Miramix'); ?></title>
	<!-- Define Charset -->
	<meta charset="utf-8">
	<!-- Responsive Metatag -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!-- Page Description and Author -->
	<meta name="description" content="Miramix helps to get yor custom supplements, whole food supplements or private label supplements product that is unique as well fits to your needs.">
	<meta name="keywords" content="Custom Supplements,Whole Food Supplements,Private Label Supplements">
	<?php 
}
?>

	<meta property="fb:app_id" content="<?php echo env('FB_CLIENT_ID')?>" /> 
	<!-- facebook og Start -->
<?php 

if(!empty($productdetails))
{
?>
	<meta property="og:title" content="<?php echo ucwords($productdetails->product_name);?>" />
	<meta property='og:site_name' content='Miramix' />
	<meta property="og:description" content="<?php echo ($productdetails->description1);?>" />
	<meta property="og:url" content="<?php echo url().'/product-details/'.$productdetails->product_slug;?>" />
	<meta property="og:image" content="<?php echo url().'/uploads/product/'.$productdetails->image1;?>"/> 
<?php 
}
elseif(!empty($all_sitesetting))
{
?>

	<meta property='og:site_name' content='Custom Supplements | Private Label | Whole Food Supplements - Miramix' />
	<meta property="og:description" content="<?php echo ($all_sitesetting['share_content']);?>" />
	<meta property="og:url" content="<?php echo url();?>" />
	<meta property="og:image" content="<?php echo url().'/uploads/share_image/'.$all_sitesetting['share_image'];?>"/> 

<?php 
}
?>
<!-- Facebook Og End -->

<link rel="shortcut icon" href="<?php echo url();?>/public/backend/images/favicon.ico" type="image/x-icon" />
<!--theme font-->
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,600,400italic,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="<?php echo url();?>/public/frontend/css/bootstrap.min.css" type="text/css" media="screen">
<!-- Font Awesome CSS -->
<link rel="stylesheet" href="<?php echo url();?>/public/frontend/css/font-awesome.min.css" type="text/css" media="screen">
<!-- Slicknav -->
<link rel="stylesheet" type="text/css" href="<?php echo url();?>/public/frontend/css/slicknav.css" media="screen">
<!-- MIRAMIX CSS Styles -->
<link rel="stylesheet" type="text/css" href="<?php echo url();?>/public/frontend/css/style.css" media="screen">
<!-- Responsive CSS Styles -->
<link rel="stylesheet" type="text/css" href="<?php echo url();?>/public/frontend/css/responsive.css" media="screen">
<!-- Css3 Transitions Styles -->
<link rel="stylesheet" type="text/css" href="<?php echo url();?>/public/frontend/css/animate.css" media="screen">
<!--theme css-->
<link href="<?php echo url();?>/public/frontend/css/screen.css" rel="stylesheet">
<!-- Sweetalert CSS Styles -->
<link href="<?php echo url();?>/public/frontend/dist/sweetalert.css" rel="stylesheet">
<link href="<?php echo url();?>/public/frontend/jqueryui/jquery-ui.css" rel="stylesheet">
<!-- Color CSS Styles -->
<!-- MIRAMIX JS -->
<script type="text/javascript" src="<?php echo url();?>/public/frontend/js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="<?php echo url();?>/public/frontend/js/modernizrr.js"></script>
<script type="text/javascript" src="<?php echo url();?>/public/frontend/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo url();?>/public/frontend/js/jquery.slicknav.js"></script>
<script type="text/javascript" src="<?php echo url();?>/public/frontend/jqueryui/jquery-ui.min.js"></script>
<script src="<?php echo url();?>/resources/assets/js/jquery.validate-1.14.0.min.js"></script>
<script src="<?php echo url();?>/resources/assets/js/additional-methods-1.14.0.js"></script>
<!-- SweetAlert -->
<script type="text/javascript" src="<?php echo url();?>/public/frontend/dist/sweetalert-dev.js"></script>
<!--api of reCaptcha-->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!--[if IE 8]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body class="<?php echo (isset($body_class)?$body_class:'');?>">
<!-- Full Body Container -->
<div id="container">
<!-- Start Header Section -->
<header class="clearfix">
<div class="container-fluid">
<!-- Start Logo & Naviagtion -->
<div class="navbar navbar-default navbar-top">
<div class="navbar-header">

<?php
//echo $cartval = Cart::count();
$cartval ='';
if( Session::has('member_userid')) // If Member Login then cart value comes from database.
{
	$cartval = (isset($cart_value))?$cart_value:'';
}
else
{
	if(Cart::count()>0)
	{
		$cartval = Cart::count(); // Show the session cart value For logout user.
	}
}
// Checking brand login or not //

?>
<a href="javascript:void(0)" class="cart visible-xs-3 visible" id="formob_only" onClick="redirectToCart()"><span id="cart_mob_det" <?php if($cartval==''){?> style="display:none;" <?php }?> ><?php echo $cartval;?></span></a>
<!--<a href="javascript:void(0)" class="cart visible-xs-3 visible" id="formob_only" onClick="redirectToCart()"><span id="cart_det"></span></a>-->


<!-- Stat Toggle Nav Link For Mobiles -->
<button type="button" class="navbar-toggle nav-button" data-toggle="collapse" data-target=".navbar-collapse">
<i class="fa fa-bars"></i>
</button>
<!-- End Toggle Nav Link For Mobiles -->
<a class="navbar-brand" href="<?php echo url();?>">&nbsp;</a>
<a href="mailto:support@miramix.com" class="<?php echo (isset($phone_class)?$phone_class:'telP_top');?>"><i class="fa fa-envelope"></i><span>Email us at support@miramix.com</span></a>
</div>
<div class="navbar-collapse collapse">
<!-- Start Navigation List -->
<ul class="nav navbar-nav navbar-right">
<li><a href="<?php echo url();?>/inventory">Inventory</a></li>
<!-- <li class="<?php echo (isset($brand_active)?$brand_active:'');?>"><a href="<?php echo url();?>/brands">Brands</a></li> -->
<li><a href="<?php echo url();?>/faqs">FAQs</a></li>
<li><a href="<?php echo url().($about_us!='')?$about_us:'#'?>">About Us</a></li>
<?php
if(Session::has('member_userid') || Session::has('brand_userid'))
{
if(Session::has('brand_username'))
{
?>
<li class="login_email"><a href="<?php echo url();?>/brand-dashboard">
{!! Session::get('brand_username') !!} </a></li>
<?php
}
else if(Session::has('member_username'))
{
?>
<li class="login_email"><a href="<?php echo url();?>/member-dashboard">
{!! Session::get('member_username') !!} </a></li>
<?php
}
?>
<li class="login"><a href="<?php echo url();?>/userLogout">Logout</a></li>
<?php
}
else
{
?>
<!--<li class="brand"><a href="<?php echo url();?>/brandLogin">Brand Login</a></li>-->
<li class="sign"><a href="<?php echo url();?>/register">Sign Up</a></li>
<li class="login"><a href="<?php echo url();?>/memberLogin">Login</a></li>
<?php
}

//echo $cartval = Cart::count();
$cartval ='';
if( Session::has('member_userid') || Session::has('brand_userid')) // If Member Login then cart value comes from database.
{
	$cartval = (isset($cart_value))?$cart_value:'';
}

else
{
	if(Cart::count()>0)
	{
		$cartval = Cart::count(); // Show the session cart value For logout user.
	}
}

if(isset($cart_result))
	$cartval =  count($cart_result);

?>
<li class="cart"><a href="javascript:void(0)" onClick="redirectToCart()"><span id="cart_det" <?php if($cartval==''){?> style="display:none;" <?php }?> ><?php echo $cartval;?></span></a></li>

</ul>
<!-- End Navigation List -->
</div>
</div>
<!-- End Header Logo & Naviagtion -->
</div>
</header>
<!-- End Header Section -->
<script>
function redirectToCart()
{
var cart = $("#cart_det").text();
var cart_mob_det = $("#cart_mob_det").text();

//if(cart)
{
window.location = "<?php echo url();?>/show-cart";
}
}
</script>

<script src="<?php echo url();?>/public/frontend/js/stacktable.js"></script>
<link href="<?php echo url();?>/public/frontend/css/stacktable.css" rel="stylesheet">

<!-- For multiselect box -->
 <script src="<?php echo url();?>/public/backend/bootstrap/js/bootstrap-multiselect.js"></script>
 <script src="<?php echo url();?>/public/backend/bootstrap/css/bootstrap-multiselect.css"></script>
 <!-- //For multiselect box -->
