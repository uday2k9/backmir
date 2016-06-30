@extends('frontend/layout/frontend_template')
@section('content')
<div class="inner_page_container">
        <div class="header_panel">
            <div class="container">
             <h2><?php echo isset($cms->title)?$cms->title:'';?></h2>
            </div>
        </div>   
<!-- Start Products panel -->
<div class="products_panel about_panel">
    <div class="container">
    <div class="top_about">

    <?php echo isset($cms->description)?html_entity_decode($cms->description):'No data found.'; ?>

    </div>
</div>
<!-- End Products panel --> 
 </div>
 </div>
@stop