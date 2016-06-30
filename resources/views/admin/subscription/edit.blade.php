@extends('admin/layout/admin_template')

@section('content')
    <link rel="stylesheet" href="{{ url() }}/public/frontend/calendar/jquery-ui.css" />    
    
    <!-- Load jQuery UI Main JS  -->
    <script src="{{ url() }}/public/frontend/calendar/jquery-ui.js"></script>
<script>
  
  // When the browser is ready...
  $(function() {
 
    $("#form_brand").validate({
        
        ignore: [],
        // Specify the validation rules
        rules: {
            fname: "required",
            lname: "required",
            email: {
                      required: true,
                      email: true
                    },
            gender: "required",
            dob: "required",
           
            phone_no: 
                    {
                      phoneUS: true,
                      required: true
                    }
            
        },
        
        // Specify the validation error messages
        messages: {
            fname: "Please enter first name.",
            lname: "Please enter last name.",
            email: "Please enter valid email address.",
            gender: "Please choose gender.",
            dob: "Please enter date of birth.",
            phone_no: "Please enter valid phone number."
        },               

        submitHandler: function(form) {
            form.submit();
        }
    });

    $(function() {
      $( "#start_date" ).datepicker({
                showOn: "button",
                buttonImage: "{{ url() }}/public/frontend/calendar/images/calendar.gif",
                buttonImageOnly: true,
                buttonText: "Select date",
                minDate: 0, 
                maxDate: "+0D",
                dateFormat: "yy-m-d",
                changeYear: false
              });
      

      $( "#end_date" ).datepicker({
                showOn: "button",
                buttonImage: "{{ url() }}/public/frontend/calendar/images/calendar.gif",
                buttonImageOnly: true,
                buttonText: "Select date",
                minDate: 7, 
                maxDate: "+5Y",
                dateFormat: "yy-m-d",
                changeYear: true
              })
      });
    });
  
  </script>
    
   {!! Form::model($subscription,array('method' => 'PATCH','id'=>'form_subscription','files'=>true,'name'=>'form_subscription','class'=>'form-horizontal row-fluid','route'=>array('admin.subscription.update',$subscription->subscription_id))) !!}

    {!! Form::hidden('member_id',null,['class'=>'span8','id'=>'member_id']) !!}         
    <div class="control-group">
          <label class="control-label" for="basicinput">Brand Name </label>
          <div class="controls">
               {!! $subscription->getSubMembers->business_name; !!}
          </div>
    </div>
        <div class="control-group">
            <label class="control-label" for="basicinput">Status</label>
            <div class="controls">
              <input type="radio" name="status" value="expired" <?php if($subscription->getSubMembers->subscription_status=='expired'){ echo "checked"; } ?> />Block
              <input type="radio" name="status" value="active" <?php if($subscription->getSubMembers->subscription_status=='active'){ echo "checked"; } ?>  />Active  
            </div>
        </div>
    
     
    
        <div class="control-group">
          <label class="control-label" for="basicinput">Subscription Start Date *</label>
          <div class="controls">
               {!! Form::text('start_date',null,['class'=>'span8','id'=>'start_date']) !!}
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="basicinput">Subscription End Date *</label>
          <div class="controls">
               {!! Form::text('end_date',null,['class'=>'span8','id'=>'end_date']) !!}
          </div>
        </div>
     
        <div class="control-group">
            <label class="control-label" for="basicinput">Total Fee</label>
            <div class="controls">
                 {!! Form::text('subscription_fee',null,['class'=>'span8','id'=>'subscription_fee']) !!}
            </div>
        </div>
	    
	<div class="control-group">
            <label class="control-label" for="basicinput">Pay Status</label>
            <div class="controls">
            <?php if($subscription->payment_status == '' || $subscription->payment_status =='pending')
            {
              $payment_status ='pending';
            }
            else
            {
              $payment_status ='paid';
            }
            ?>
                {!! Form::select('payment_status', array('' => 'select one') +$status,$payment_status, array('id' => 'payment_status','class'=>"form-control")); !!}
            </div>
        </div>        
	    
	<div class="control-group">
            <label class="control-label" for="basicinput">Transaction ID</label>
            <div class="controls">
                <?php echo $subscription->transaction_id ?>
            </div>
        </div>
 
    
    
    <div class="form-group">
        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
        <a href="{!! url('admin/subscription')!!}" class="btn btn-primary">Back</a>
    </div>
    {!! Form::close() !!}

    <script>
      $( document ).ready(function() {
         // var sel_rad = $("input[name=status]:checked").val();
          //if(sel_rad=='expired')
          //{                
          //  $('#start_date').attr('readonly', true);
         // }
         // if(sel_rad=='active')
         // {
           // $('#end_date').attr('readonly', true);
         // }
         // $("input[name=status]").click(function() {     
           //   sel_rad = $("input[name=status]:checked").val();
            //  if(sel_rad=='expired')
            //  {                
             //   $('#start_date').attr('readonly', true);
             //   $('#end_date').attr('readonly', false);
             // }
             // if(sel_rad=='active')
             // {
               // $('#start_date').attr('readonly', false);
               // $('#end_date').attr('readonly', true);
             // }
         // });
      //});
    </script>
@stop



