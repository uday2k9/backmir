@extends('frontend/layout/frontend_template')

@section('content')
<div id="container">



    <div class="inner_page_container">
      <div class="header_panel">
          <div class="container">
           <h2>Brands</h2>
            </div>
        </div>   
<!-- Start inventory panel -->
<?php if($memberdetail->fname){
$name=$memberdetail->fname." ".$memberdetail->lname;
}else{
$name='';

}?>

    <div id="myModal" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content clearfix">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">Request Ingredient</h4>
              </div>
              <div class="modal-body">
                <div class="col-sm-12">
                  {!! Form::open(['url' => 'inventory','method'=>'POST', 'files'=>true,  'id'=>'req_ing','class'=>"form-horizontal"]) !!}
                    <div class="form-group">

                    {!! Form::text('name',NULL,['class'=>'form-control','id'=>'name','placeholder'=>'Full Name']) !!}
                    </div>
                    <div class="form-group">

                    {!! Form::text('contact_email',$memberdetail->email,['class'=>'form-control','id'=>'contact_email','placeholder'=>'Your Email']) !!}
                    </div>
                     <div class="form-group">

                    {!! Form::text('ingradient_name',$name,['class'=>'form-control','id'=>'ingradient_name','placeholder'=>'Ingradient Name']) !!}
                    </div>
                    <div class="form-group">

                    {!! Form::textarea('request_ing',null,['class'=>'form-control','id'=>'request_ing','placeholder'=>'Why You Want This Ingradients']) !!}
                    </div>
                    
                    <div class="form-group"><input type="submit" class="butt" value="Submit"></div>
                    {!! Form::close() !!} 
                    </div>
                </div>
                
          </div>
      </div>
    </div>

<div class="inventory_panel smaller_inv">
<div class="container">
  <a href="#myModal" class="btn btn-lg btn-primary reqst_ing pull-right" data-toggle="modal">Request Ingredient</a>
  <ul>
  <li class="active"><a href="">A</a></li><li><a href="">B</a></li><li><a href="">C</a></li><li><a href="">D</a></li><li><a href="">E</a></li><li><a href="">F</a></li><li><a href="">G</a></li><li><a href="">H</a></li><li><a href="">I</a></li><li><a href="">J</a></li><li><a href="">K</a></li><li><a href="">L</a></li><li><a href="">M</a></li><li><a href="">N</a></li><li><a href="">O</a></li><li><a href="">P</a></li><li><a href="">Q</a></li><li><a href="">R</a></li><li><a href="">S</a></li><li><a href="">T</a></li><li><a href="">U</a></li><li><a href="">V</a></li><li><a href="">W</a></li><li><a href="">X</a></li><li><a href="">Y</a></li><li><a href="">Z</a></li>
  </ul>
</div>
</div>
<!-- End inventory panel -->
<div class="container">
 @if(Session::has('error'))
    <div class="alert alert-danger mt20">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{!! Session::get('error') !!}zxcxc</strong>
    </div>
  @endif

  @if(Session::has('success'))
    <div class="alert alert-success mt20">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{!! Session::get('success') !!}</strong>
    </div>
  @endif
<div class="inventory_boxes">
<div class="inv_box">
<div class="inv_head">A</div>
<div class="inv_body">
<ul>
<li><a href="">Acerola Powder</a></li>
<li><a href="">Acetyl-L-Carnitine</a></li>
<li><a href="">Artichoke</a></li>
<li><a href="">Astragalus</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">B</div>
<div class="inv_body">
<ul>
<li><a href="">Bacopa Monnieri</a></li>
<li><a href="">Beta Alanine</a></li>
<li><a href="">Black Cohosh</a></li>
<li><a href="">Brazil Nuts</a></li>
<li><a href="">Bromelain</a></li>
<li><a href="">Butterbur</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">C</div>
<div class="inv_body">
<ul>
<li><a href="">Cacao</a></li>
<li><a href="">Caffeine</a></li>
<li><a href="">Calcium</a></li>
<li><a href="">Capsaicin</a></li>
<li><a href="">Casein Protein</a></li>
<li><a href="">Chasteberry</a></li>
<li><a href="">Choline</a></li>
<li><a href="">Chondroiton</a></li>
<li><a href="">Chromium</a></li>
<li><a href="">Citrus Bioflavinoid Complex</a></li>
<li><a href="">Collagen</a></li>
<li><a href="">Collard Greens</a></li>
<li><a href="">Copper</a></li>
<li><a href="">Creatine</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">D</div>
<div class="inv_body">
<ul>
<li><a href="">Dandelion Root</a></li>
<li><a href="">DIIM</a></li>
<li><a href="">Dong Quai Root</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">E</div>
<div class="inv_body">
<ul>
<li><a href="">Egg white protein</a></li>
<li><a href="">Enchinacea</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">F</div>
<div class="inv_body">
<ul>
<li><a href="">flaxseed powder</a></li>
<li><a href="">Fucoxanthin</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">G</div>
<div class="inv_body">
<ul>
<li><a href="">Garlic Extract</a></li>
<li><a href="">Ginger</a></li>
<li><a href="">Ginko Biloba</a></li>
<li><a href="">Ginseng</a></li>
<li><a href="">Glucosamine</a></li>
<li><a href="">Green Tea Extract</a></li>
</ul>
</div>
</div>

<div class="inv_box">
<div class="inv_head">H</div>
<div class="inv_body">
<ul>
<li><a href="">Horsetail Plant</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">I</div>
<div class="inv_body">
<ul>
<li><a href="">Iron</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">K</div>
<div class="inv_body">
<ul>
<li><a href="">Kale</a></li>
<li><a href="">Kelp</a></li>
<li><a href="">Konjac Root</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">L</div>
<div class="inv_body">
<ul>
<li><a href="">Liver</a></li>
<li><a href="">Longjack</a></li>
<li><a href="">Lutein</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">M</div>
<div class="inv_body">
<ul>
<li><a href="">Maca</a></li>
<li><a href="">Magnesium</a></li>
<li><a href="">Manganese</a></li>
<li><a href="">Melatonin</a></li>
<li><a href="">Milk Thistle</a></li>
<li><a href="">Molybdenum</a></li>
<li><a href="">MSM</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">N</div>
<div class="inv_body">
<ul>
<li><a href="">Nattokinase Powder</a></li>
</ul>
</div>
</div>

<div class="inv_box">
<div class="inv_head">O</div>
<div class="inv_body">
<ul>
<li><a href="">Olive Leaf</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">P</div>
<div class="inv_body">
<ul>
<li><a href="">Pea Protein</a></li>
<li><a href="">Potassium</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">Q</div>
<div class="inv_body">
<ul>
<li><a href="">Quercetin</a></li>
</ul>
</div>
</div>

<div class="inv_box">
<div class="inv_head">R</div>
<div class="inv_body">
<ul>
<li><a href="">Reservatol</a></li>
<li><a href="">Rhodiola Rosea 1% Rosavins</a></li>
<li><a href="">Royal Jelly</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">S</div>
<div class="inv_body">
<ul>
<li><a href="">Saffron</a></li>
<li><a href="">Sardine powder</a></li>
<li><a href="">Seaweed</a></li>
<li><a href="">Sesame Seed Powder</a></li>
<li><a href="">Shiitake Mushroom</a></li>
<li><a href="">Soy Protein</a></li>
<li><a href="">Spinach</a></li>
<li><a href="">St. John's Wort</a></li>
<li><a href="">Sunflower Lecithin</a></li>
<li><a href="">Sweet Potato</a></li>
<li><a href="">Synephrine HCL</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">T</div>
<div class="inv_body">
<ul>
<li><a href="">Theobromine</a></li>
<li><a href="">Tomato</a></li>
<li><a href="">Tribulus Terrestris</a></li>
<li><a href="">Tuna Flakes</a></li>
<li><a href="">Turmeric</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">V</div>
<div class="inv_body">
<ul>
<li><a href="">Valerian Root</a></li>
<li><a href="">Vitamin A</a></li>
<li><a href="">Vitamin B1 (Thiamine HCL)</a></li>
<li><a href="">Vitamin B12 (Cobalamin)</a></li>
<li><a href="">Vitamin B2 (Riboflavin)</a></li>
<li><a href="">Vitamin B3 (Niacin)</a></li>
<li><a href="">Vitamin B5 (Pantothenic Acid)</a></li>
<li><a href="">Vitamin B6 (Pyridoxine)</a></li>
<li><a href="">Vitamin B7 (Biotin)</a></li>
<li><a href="">Vitamin B9 (Folic Acid)</a></li>
<li><a href="">Vitamin C (Ascorbic Acid)</a></li>
<li><a href="">Vitamin D3 (</a></li>
<li><a href="">Vitamin E</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">W</div>
<div class="inv_body">
<ul>
<li><a href="">Whey Protein (90%)</a></li>
</ul>
</div>
</div>
<div class="inv_box">
<div class="inv_head">Z</div>
<div class="inv_body">
<ul>
<li><a href="">Zinc</a></li>
</ul>
</div>
</div>

</div>
</div>
 
 </div>



  </div>


<script>
  $(document).on('click','.inventory_panel li a',function(e){
    e.preventDefault();
    var $this=$(this);
    var this_text=$this.text();
    $('.inventory_panel li').removeClass('active');
    $this.parent().addClass('active');
    $('.inv_box').removeClass('active');
    $('.inv_box').each(function(index, element) {
          var $this=$(this);
      var this_subtext=$this.find('.inv_head').text();
      if(this_text==this_subtext){
      $this.addClass('active');
      $('html, body').animate({
        scrollTop: $this.offset().top-80
      }, 2000);  
      }     
      });
  });
</script>
<script>


  
  // When the browser is ready...
  $(function() {

    $.validator.addMethod("email", function(value, element) 
      { 
      return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 
      }, "Please enter a valid email address.");

    // Setup form validation  //
    $('#myModal').on('shown.bs.modal',function(){

      $("#req_ing").validate({
      // Specify the validation rules
      rules: {
        name:"required",
        contact_email: {
                      required : true,
                      email: true
                  },
        brand_name:"required",
        request_ing: "required"
       },
        submitHandler: function(form) {
            form.submit();
        }
      });
    });

  });
    
</script>
  @stop