<?php
namespace App\Helper;
use Session;use DB;
use Cart;
ini_set('memory_limit', '-1');

class helpers extends Cart {

	function somethingOrOther()
	{
        $col = 'id';
        $new_created_slug = 25;
        $model = 'ingredients';
        $conditions = $col." = '".$new_created_slug."'";

        //return $ingredients_details = DB::table($model)->where($col,'=',$new_created_slug)->count();
	    return (mt_rand(1,2) == 1) ? 'something' : 'other';
	}


	function createThumbnail($imageName,$newWidth,$newHeight,$uploadDir,$moveToDir)
    {
        $path = $uploadDir . '/' . $imageName;

        $mime = getimagesize($path);

        if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
        if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
        if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
        if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }
        if($mime['mime']=='image/gif'){ $src_img = imagecreatefromgif($path); }
	

        $old_x = imageSX($src_img);
        $old_y = imageSY($src_img);

        if($old_x > $old_y)
        {
            $thumb_w    =   $newWidth;
            $thumb_h    =   $old_y/$old_x*$newWidth;
        }

        if($old_x < $old_y)
        {
            $thumb_w    =   $old_x/$old_y*$newHeight;
            $thumb_h    =   $newHeight;
        }

        if($old_x == $old_y)
        {
            $thumb_w    =   $newWidth;
            $thumb_h    =   $newHeight;
        }

        $dst_img        =   ImageCreateTrueColor($thumb_w,$thumb_h);

        /* for png black background error Start */
        imagealphablending($dst_img, false);
        imagesavealpha($dst_img,true);
        $transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
        imagefilledrectangle($dst_img, 0, 0, $thumb_w, $thumb_h, $transparent);
        /* for png black background error end */

        imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);


        // New save location
        $new_thumb_loc = $moveToDir . $imageName;

        if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,9); }
        if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,100); }
        if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,100); }
        if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,100); }
        if($mime['mime']=='image/gif'){ $result = imagegif($dst_img,$new_thumb_loc,100); }


        imagedestroy($dst_img);
        imagedestroy($src_img);
        return $result;
    }


    function checkUserLogin(){
        if(Session::has('member_userid') || Session::has('brand_userid')){
            return true;
        }
        else
            return false;
    }

    function checkBrandLogin(){
        if( Session::has('brand_userid')){
            return true;
        }
        return false;
    }

    function checkMemberLogin(){
        if( Session::has('member_userid')){
            return true;
        }
        return false;
    }



    public function create_slug($page_title,$model,$col)
    {
        
        $current_created_slug=$new_created_slug = preg_replace('/[^\da-z]/i', '-', strtolower($page_title));
        $new_created_slug=$current_created_slug=trim(preg_replace('/-+/', '-', $new_created_slug), '-');
        $stat=0;
        $cnt=1;
        while($stat==0)
        {
            //$conditions = $model.".".$col." = '".$new_created_slug."'";
            //$check_slug = $this->$model->find('count',array('conditions'=>$conditions));

            $check_slug = DB::table($model)->where($col,'=',$new_created_slug)->count();
            // DB::enableQueryLog();
            // print_r(DB::getQueryLog());
            
            if($check_slug>0)
            {
                $new_created_slug = $current_created_slug.'-'.($cnt);
            }
            else
            {
                $stat=1;
            }
            $cnt++;
        }
        return $new_created_slug;
    }

    public function edit_slug($page_title,$model,$col,$primary_value)
    {
        
        $current_created_slug=$new_created_slug = preg_replace('/[^\da-z]/i', '-', strtolower($page_title));
        $new_created_slug=$current_created_slug=trim(preg_replace('/-+/', '-', $new_created_slug), '-');
        $stat=0;
        $cnt=1;
        while($stat==0)
        {

            $check_slug = DB::table($model)->where($col,'=',$new_created_slug)->where('id','!=',$primary_value)->count();
            // DB::enableQueryLog();
            // print_r(DB::getQueryLog());
            
            if($check_slug>0)
            {
                $new_created_slug = $current_created_slug.'-'.($cnt);
            }
            else
            {
                $stat=1;
            }
            $cnt++;
        }
        return $new_created_slug;
    }

    function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }
    
    function get_state($state_id){
        if (DB::table('zones')->where('zone_id',$state_id)->exists()) {
            $state = DB::table('zones')->where('zone_id',$state_id)->first();
            return $state->name;
        }
        else
           return ""; 
    }
    
    function get_country($country_id){
	
        if (DB::table('countries')->where('country_id',$country_id)->exists()) {
            $country = DB::table('countries')->where('country_id',$country_id)->first();
            return $country->name;
        }
        else
           return ""; 
	
    }

    function get_state_name($state_code,$country_id)
    {
        if (DB::table('zones')->where('code',trim($state_code))->exists()) {
            $state = DB::table('zones')
                    ->leftjoin('countries', 'countries.country_id', '=', 'zones.country_id')
                    ->select('zones.*', 'countries.name as country_name')
                    ->where('countries.iso_code_3',trim($country_id))
                    ->where('zones.code',trim($state_code))->first();
            return $state->name;
        }
        else
           return $state_code; 
    }
    function get_statecode($state_id){
        if (DB::table('zones')->where('zone_id',$state_id)->exists()) {
            $state = DB::table('zones')->where('zone_id',$state_id)->first();
            return $state->code;
        }
        else
           return ""; 
    }
    
    
    function get_statecode_by_name($state_code)
    {
        if (DB::table('zones')->where('name',trim($state_code))->exists()) {
            $state = DB::table('zones')
                    ->select('zones.*')
                    ->where('zones.name',trim($state_code))->first();
            return $state->code;
        }
        else
           return $state_code; 
    }
    
    function get_country_name($country_code) // three digit code
    { 
        if (DB::table('countries')->where('iso_code_3',trim($country_code))->exists()) {
            $country = DB::table('countries')->where('iso_code_3',trim($country_code))->first();
            return $country->name;
        }
        else
           return $country_code; 
    }

    function getProductDetails($product_id){
    
        /*$pro_details = DB::table('products')->select('products.*', 'product_formfactor_durations.*')
            ->leftJoin('product_formfactors', 'products.id', '=', 'product_formfactors.product_id')
            ->leftJoin('product_formfactor_durations', 'product_formfactor_durations.product_formfactor_id', '=', 'product_formfactors.id')
            ->where('products.id',$product_id)->first();*/

         $pro_details = DB::table('products')
            ->leftJoin('product_formfactors', 'products.id', '=', 'product_formfactors.product_id')
            ->leftJoin('product_formfactor_durations', 'product_formfactor_durations.product_formfactor_id', '=', 'product_formfactors.id')
            ->where('products.id',$product_id)->first();
            
        return $pro_details;
    }
    
    function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
    {
        $pagination = '';
        if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
    	$pagination .= '<ul class="pagination pagination-sm">';
           
    	$right_links    = $current_page + 3;
    	$previous       = $current_page - 3; //previous link
    	$next           = $current_page + 1; //next link
    	$first_link     = true; //boolean var to decide our first link
           
    	if($current_page > 1){
    	    $previous_link = ($previous==0)?1:$previous;
    	    $pagination .= '<li class="first"><a href="#" data-page="1" title="First">&laquo;</a></li>'; //first link
    	    $pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous">&lt;</a></li>'; //previous link
    		for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
    		    if($i > 0){
    			$pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
    		    }
    		}  
    	    $first_link = false; //set first link to false
    	}
           
    	if($first_link){ //if current active page is first link
    	    $pagination .= '<li class="first active"><a href="#">'.$current_page.'</a></li>';
    	}elseif($current_page == $total_pages){ //if it's the last active link
    	    $pagination .= '<li class="last active"><a href="#">'.$current_page.'</a></li>';
    	}else{ //regular current link
    	    $pagination .= '<li class="active"><a href="#">'.$current_page.'</a></li>';
    	}
    	       
    	for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
    	    if($i<=$total_pages){
    		$pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
    	    }
    	}
    	if($current_page < $total_pages){
    		$next_link = ($i > $total_pages)? $total_pages : $i;
    		$pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next">&gt;</a></li>'; //next link
    		$pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last">&raquo;</a></li>'; //last link
    	}
           
    	$pagination .= '</ul>';
        }
        return $pagination; //return pagination links
    }

    function getCmsLink($cms_id)
    {
     $cms_details = DB::table('cmspages')->select('slug')->where('id',$cms_id)->first();
     
     return $cms_details;
 
 
    }
	
	function validateRating($product_id){
	$member1=Session::get('brand_userid');
	$member2=Session::get('member_userid');
	if(!empty($member1)){
	$memberdetail = DB::table('brandmembers')->where("id",$member1)->first();
	}elseif(!empty($member2)){
	   $memberdetail =DB::table('brandmembers')->where("id",$member2)->first();
	}
	 
		
		$rating_details = DB::table('product_rating')->where('product_id',$product_id)->where("user_id",$memberdetail->id)->first();
         if(count($rating_details)<=0){
		return true;
	 }else
         return false;
		
	}
	
	
	
	
	

function time_elapsed_string($ptime)
{
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
                 30 * 24 * 60 * 60  =>  'month',
                      24 * 60 * 60  =>  'day',
                           60 * 60  =>  'hour',
                                60  =>  'minute',
                                 1  =>  'second'
                );
    $a_plural = array( 'year'   => 'years',
                       'month'  => 'months',
                       'day'    => 'days',
                       'hour'   => 'hours',
                       'minute' => 'minutes',
                       'second' => 'seconds'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
        }
    }
}

public function get_minprice($pid){
	
	$products = DB::table('products')
                 ->select(DB::raw('products.id,products.brandmember_id,products.product_name,products.product_slug,products.image1, MIN(`actual_price`) as `min_price`,MAX(`actual_price`) as `max_price`,products.created_at'))
                 ->leftJoin('product_formfactors', 'products.id', '=', 'product_formfactors.product_id')
                 ->where('is_deleted', 0)
                 ->whereRaw('products.active="1"')
		 ->where('product_formfactors.actual_price','!=', 0)
		 ->where('products.id', $pid)
                 ->groupBy('product_formfactors.product_id')->first();
	if(!empty($products))
	return $products->min_price;
	else
	return 0;
}

public function get_last_query() {
	DB::connection()->enableQueryLog();
	$queries = DB::getQueryLog();
	$sql = end($queries);
	      
	if( ! empty($sql['bindings']))
	{
	  $pdo = DB::getPdo();
	  foreach($sql['bindings'] as $binding)
	  {
	    $sql['query'] =
	      preg_replace('/\?/', $pdo->quote($binding),
		$sql['query'], 1);
	  }
	}
	      
	return $sql['query'];
      }



    // public function update($rowId, $attribute){
    //     $cart = Cart::content();

    //     //echo "<pre>";print_r($this->session->get($this->getInstance()));

    //     //$instance = Cart::instance($cart);

    //     //array_push($cart,"blue","yellow");


    //     //Session::put($instance, $cart);

    //     echo "overwrite";
    //     $cart = $this->content();
    //    echo "<pre>";print_r($cart);



    //     exit;
    // }

    public function content()
    {

        $cart_content = parent::content();
        if(Session::has('coupon_type') && Session::has('coupon_discount')){

            $cart_content->coupon_type = Session::get('coupon_type');
            $cart_content->coupon_discount = Session::get('coupon_discount');
        }


       
        return $cart_content;
    }
    
    public function demandredeem($points,$amount){
	
	$cart_content = parent::content();
        if($points && $amount){

            $cart_content->user_points = $points;
            $cart_content->redeem_amount = $amount;
        }


       
        return $cart_content;
	
    }



}
?>