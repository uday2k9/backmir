@extends('frontend/layout/frontend_template')
@section('content')
<script src="<?php echo url();?>/public/frontend/js/jquery.raty.js"></script>
<link href="<?php echo url();?>/public/frontend/css/jquery.raty.css" rel="stylesheet">

<?php $url=url()."/rate-product/".$product->id;

?>
 <div class="inner_page_container nomar_bottom">
    	   <!--my_acct_sec-->
           <div class="my_acct_sec">           
               <div class="container">
               {!! Form::open(['url' => $url,'method'=>'POST', 'files'=>true, 'role'=>'form', 'id'=>'dashboardpersonal']) !!}
               <input type="hidden" name="product_id" id="product_id" value="<?php echo $product->id;?>"/>
               <div class="col-sm-10 col-sm-offset-1">
               
               <div class="row"><div class="form_dashboardacct">
               		<h3>Review for purchased product</h3>
                    <div class="bottom_dash clearfix">
                    	
                        <div class="row">
                        <h5 class="col-sm-offset-3">Write Your Review</h5>
                        <div class="col-sm-3 left_acct_img rev_img">
                        <img src="<?php if(($product->image1 !='') && (file_exists('uploads/product/'.$product->image1))) {?><?php echo url();?>/uploads/product/{!! $product->image1 !!}<?php } ?>" class="img-responsive" alt="<?php echo $product->product_name ?>">
                        </div>
                        <div class="col-sm-9">
                        
                          <div class="form-group">
                            <div class="row">
                            <div class="col-sm-6">
                            
                            {!! Form::text('review_name',$memberdetail->fname." ".$memberdetail->lname,['class'=>'form-control','id'=>'review_name','placeholder'=>'Full Name',"readonly"=>"readonly"]) !!}
                            </div>
                                
                            <div class="col-sm-6 mt10">
                            	<div class="basic" data-average="12" data-id="1"></div>
                                    <input type="hidden" name="rating_val" id="rating_val"  />
                            </div></div>
                          </div>
                          <div class="form-group">
                            
                             {!! Form::text('review_title',null,['class'=>'form-control','id'=>'review_title','placeholder'=>'Review Title']) !!}
                          </div>
                          <div class="form-group">
                             
                                {!! Form::textarea('message',null,['class'=>'form-control','id'=>'desc_rev','placeholder'=>'Description(Max 150 Characters)', 'maxlength'=>"150" ]) !!}
                          </div>
                          
                          
                 
                        </div>
                        </div>
                        
                    </div>
                    
                    <div class="form_bottom_panel">
                     <a href="{!! url(); !!}/member-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>
                    <button type="submit" form="dashboardpersonal" class="btn btn-default green_sub pull-right">Rate Now!</button>
                    </div>
                    
               </div>
               
               </div>
               
               </div>
                {!! Form::close() !!} 
               </div>           
           </div>
           <!--my_acct_sec ends-->
 </div>

    


<script type="text/javascript">
var token='{!! csrf_token() !!}';
  $(document).ready(function(){
    /*$(".basic").jRating({
    bigStarsPath:'<?php echo url();?>/public/frontend/css/icons/stars.png',
    smallStarsPath:'<?php echo url();?>/public/frontend/css/icons/small.png',
    onClick : function(element,rate) {
        
         $("#rating_val").val(rate)
        },
    showRateInfo: true,
    rateMax : 5,
    phpPath :'<?php echo url();?>/rate-ajax?',
    });*/
    
    $('.basic').raty({
                 click: function(score, evt) {
		    // alert('ID: ' + this.id + "\nscore: " + score + "\nevent: " + evt);
		      $("#rating_val").val(score)
		   },
                  starHalf    : '<?php echo url();?>/public/frontend/css/images/star-half.png',
                  starOff     : '<?php echo url();?>/public/frontend/css/images/star-off.png',
                  starOn      : '<?php echo url();?>/public/frontend/css/images/star-on.png'  , 
                  });
    
    
    
    $("#dashboardpersonal").validate({
    
        // Specify the validation rules
        rules: {
      review_name:"required",
      review_title:"required",
      message: "required",
     rating_val: "required",
            
     },
		
        submitHandler: function(form) {
            form.submit();
        }
    });
    
    
  });
</script>
@stop

