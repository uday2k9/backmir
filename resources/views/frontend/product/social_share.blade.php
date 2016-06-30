@extends('frontend/layout/frontend_template')
@section('content')
<div class="inner_page_container">
    <div class="header_panel">
        <div class="container">
         <h2>Share Content</h2>
          </div>
    </div> 
    <div> 
		<p><img src="{!! url().'/uploads/share_image/'.$all_sitesetting['share_image'] !!}"/></p>
		<p>{!! $all_sitesetting['share_content'] !!}</p>
	</div>
</div>

@stop