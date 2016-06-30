 @extends('frontend/layout/frontend_template')
@section('content')
     <!--for login page-->
    <div class="brand_login">
      
        <!--login_cont-->
        <div class="login_cont">
            <div class="log_inner text-center">
                <h2 class="wow fadeInDown">Opps!</h2>
                  @if(Session::has('error'))
                    <div class="alert alert-danger container">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! Session::get('error') !!}</strong>
                    </div>
                  @endif
                  @if(Session::has('success'))
                    <div class="alert alert-success container">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{!! Session::get('success') !!}</strong>
                    </div>
                  @endif
                  <?php
                  Session::forget('success');
                  Session::forget('error');
                  ?>  
                 @if($show_var!='1')      
                <p id="not_div" style="color:#fff" class="wow zoomInUp brand_p clearfix noti_msg">Your subscription has expired. Please <a onclick="send_email();" href="javascript:void(0);">contact administrator</a> for assistance.</p>
                <div id="flash"></div>
                <div id="display"></div>

                @endif
            </div>  
            <div id="pay_div">
            @if($show_var!='1')
            {!!  Form::open(['url' => 'renew','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'form_factor']) !!}
            <input type="hidden" name="discontinue_product" id="discontinue_product" value="{{ $discontinue_product }}" />
            <input type="hidden" name="continue_product" id="continue_product" value="{{ $continue_product }}" />
            <input type="hidden" name="product" id="product" value="{{ $product }}" />

            <input style="background:#c0c0c0;" readonly type="hidden" value="{{ $brand_email }}" id="brandemail" name="brandemail" placeholder="Total Price" class="form-control ccjs-number">
            <div class="form_bottom_part formarea-bottom clearfix no_botpad" style="background:none; box-shadow:none;">            
              <div style="" class="credit_div">
                <div class="row">
                  <h4>Enter Card Details</h4>
                  <div class="form-group col-sm-12 col-md-12">
                    <label class="col-sm-4 col-md-4">Renew For</label>
                    <div class="col-sm-8 col-md-8">
                      <div class="row">
                        <div class="col-sm-4">                           
                           <label class="col-sm-4 col-md-4">{{ $brand_email }}</label>
                           <input type="hidden" name="member_id" id="member_id" value="{{ $user_id }}" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-sm-12 col-md-12">
                    <label class="col-sm-4 col-md-4">Renew Time</label>
                    <div class="col-sm-8 col-md-8">
                      <div class="row">
                        <div class="col-sm-4">
                          <!-- <select id="total_month" name="total_month" class="form-control" style="background:#c0c0c0;">
                            <option value="">Month</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                          </select> -->
                          <label class="col-sm-6 col-md-6">1 Month</label>
                          <input type="hidden" name="total_month" id="total_month" value="1" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-sm-12 col-md-12">
                    <label class="col-sm-4">Total Price:*</label>
                    <div class="col-sm-4">
                      <input style="background:#c0c0c0;" readonly type="text" value="" id="total_price" name="total_price" placeholder="Total Price" class="form-control ccjs-number">
                      <span id="total_price_show"></span>
                    </div>
                  </div>
                  <div class="form-group col-sm-12 col-md-12">
                    <label class="col-sm-4">Card Number:*</label>
                    <div class="col-sm-4">
                      <input style="background:#c0c0c0;" type="text" value="" id="card_number" name="card_number" placeholder="Card Number" class="form-control ccjs-number">
                    </div>
                  </div>
                  <div class="form-group col-sm-12 col-md-12">
                    <label class="col-sm-4 col-md-4">Card Expiry Date:*</label>
                    <div class="col-sm-8 col-md-8">
                      <div class="row">
                        <div class="col-sm-4">
                          <select id="card_exp_month" name="card_exp_month" class="form-control" style="background:#c0c0c0;">
                            <?php                                                          
                              $year_now=date('Y');   
                            ?>
                            <option value="">Month</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                          </select>
                        </div>

                        <div class="col-sm-4">
                          <select id="card_exp_year" name="card_exp_year" class="form-control" style="background:#c0c0c0;">
                            <option value="">Year</option>                            
                            <option value="<?=$year_now?>"><?=$year_now?></option>
                            <option value="<?=$year_now+1?>"><?=$year_now+1?></option>
                            <option value="<?=$year_now+2?>"><?=$year_now+2?></option>
                            <option value="<?=$year_now+3?>"><?=$year_now+3?></option>
                            <optionalert( "Handler for .change() called." );
        }); value="<?=$year_now+4?>"><?=$year_now+4?></option>
                            <option value="<?=$year_now+5?>"><?=$year_now+5?></option>                            
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                  <div class="form-group col-sm-12 col-md-12">
                    <label class="col-sm-4  col-md-4">Name on Card:</label>
                    <div class="col-sm-8 col-md-4"><input style="background:#c0c0c0;" type="text" value="" id="name_card" name="name_card" placeholder="Name on Card" class="form-control"></div>
                  </div>

                  <div class="form-group col-sm-12 col-md-12 clearfix">
                    <label class="col-sm-4 col-md-4">Card Security Code:*</label>
                    <div class="col-sm-8 col-md-3">
                      <input style="background:#c0c0c0;" type="password" value="" id="cvv" name="cvv" placeholder="Card Security Code (CVV)" class="form-control">
                    </div>
                  </div>
                  <div class="form-group col-sm-12 col-md-12">                    
                    <div class="col-sm-8 col-md-3">
                      <input type="submit" value="Pay" id="pay_submit" class="full_green_btn text-uppercase pull-right logged_inbtn">
                    </div>
                  </div>
                  

                </div>
              </div>
            </div>
            {!! Form::close() !!}
            @endif
          </div>

        </div>
        <!--login_cont-->
        
    </div>
    <!--for login page-->
    
    <script type="text/javascript">
    $( document ).ready(function() {  
        var total_month = $("#total_month").val();
        var discontinue_product = $("#discontinue_product").val();
        var continue_product = $("#continue_product").val();
        var product = $("#product").val();
        var other_total=parseFloat(discontinue_product*4)+parseFloat(continue_product*4);
       
        var total_price=parseFloat((total_month*19))+other_total;
// alert(total_price);
        //alert(other_total);
        $("#total_price").val(total_price);
        $("#total_price_show").val(total_price);   

        $( "#total_month" ).change(function() {
          var total_month = $("#total_month").val();
          var discontinue_product = $("#discontinue_product").val();
          var continue_product = $("#continue_product").val();
          var product = $("#product").val();

          var other_total=parseFloat(discontinue_product*4)+parseFloat(continue_product*4);
          //alert(other_total);
          var total_price=parseFloat((total_month*19))+other_total;
          if(product>1)
          {
            var showing_text='Total '+product+' products'
          }
          else
          {
            var showing_text='Total '+product+' product' 
          }
         
         $("#total_price").val(total_price);
         $("#total_price_show").html(showing_text);
          
        });
    });
    </script>

    <script type="text/javascript">
      $("document").ready(function(){
      $(".js-ajax-php-json").submit(function(){
      var data = {
      "action": "test"
      };
      data = $(this).serialize() + "&" + $.param(data);
      $.ajax({
      type: "POST",
      dataType: "json",
      url: "response.php", //Relative or absolute path to response.php file
      data: data,
      success: function(data) {
      $(".the-return").html(
      "Favorite beverage: " + data["favorite_beverage"] + "<br />Favorite restaurant: " + data["favorite_restaurant"] + "<br />Gender: " + data["gender"] + "<br />JSON: " + data["json"]
      );
      
      alert("Form submitted successfully.\nReturned json: " + data["json"]);
      }
      });
      return false;
      });
      });
  </script>
  <script type="text/javascript">
      function send_email()
      {
       
        var member_id=$("#member_id").val();
        var brandemail=$("#brandemail").val();
        
        //alert(member_id);
        //alert(brandemail);
       // return false;
      //  $("#flash").show(); 
       // $("#display").html(''); 
        $("#flash").show();
        $("#display").html('');
        $.ajax({    
        type:'GET',
        url:'<?php echo url(); ?>/contactadmin/'+member_id,  
        success:function(data){ 
         if(data=='done')
         {
            $("#display").append('<p style="color:#fff" class="wow zoomInUp brand_p clearfix noti_msg">Thank you for contacting us.<p>');
         }
         if(data=='not')
         {
            $("#display").append('<p style="color:#fff" class="wow zoomInUp brand_p clearfix noti_msg">System error! Please try after sometime.<p>');
         }
          $("#not_div").hide(); 
          $("#pay_div").hide(); 
          $("#flash").hide(); 
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){  
          $("#display").html('An error occurred. Please try again later.');
          $("#flash").hide();
        }
        }); 
       
      }
  </script>
@stop