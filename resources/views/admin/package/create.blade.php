@extends('admin/layout/admin_template')

@section('content')

        {{-- Form::open(['url' => 'admin/package/store','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'form_factor']) --}}
        <form method="POST" action="<?php echo url();?>/admin/package/store" accept-charset="UTF-8" class="form-horizontal row-fluid" id="form_factor" enctype="multipart/form-data">
          <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <div class="control-group">
            <label class="control-label" for="basicinput">Choose Package Type</label>
            <div class="controls">                                 
                {!! Form::select('package_type', $package_types, '' ,['single','id'=>'package_type']) !!}                                       
            </div>   
        </div>

        <div class="control-group">
            <label class="control-label" for="basicinput">Name *</label>
            <div class="controls">
                 {!! Form::text('name',null,['class'=>'span8','id'=>'name']) !!}
            </div>
        </div>

        
        <div class="control-group">
                <label class="control-label" for="basicinput">Form Factor</label>                
                <div class="controls">                                 
                    {{-- Form::select('formfactor[]', $formfactors, '' ,['multiple','id'=>'formfactor']) --}}                                       
                    <select name="formfactor[]" class="formfactor" multiple="multiple" id="formfactor" size="5" onchange="get_value(this.value)">
                        @foreach($formfactors as $key=>$val)
                          <option value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>                    

                    @foreach($formfactors_val as $key2=>$val2)
                      {!! Form::hidden('formfactor_type[]','formfactor_type_'.$val2,['class'=>'span4','id'=>'formfactor_type_'.$key2, 'placeholder'=>'Type']) !!}
                    @endforeach
                </div>                
        </div>
        <div class="control-group">
            <label class="control-label" for="basicinput">Dimension</label>
            <div class="controls">
                {!! Form::text('maximum_depth',null,['class'=>'span4','id'=>'maximum_depth', 'placeholder'=>'Length']) !!}
                {!! Form::text('maximum_width',null,['class'=>'span4','id'=>'maximum_width', 'placeholder'=>'Width']) !!} 
                {!! Form::text('maximum_height',null,['class'=>'span4','id'=>'maximum_height', 'placeholder'=>'Height']) !!}
            </div>
        </div> 

        <div class="control-group">
            <label class="control-label" for="basicinput">Lower Bound</label>
            <div class="controls">
             {!! Form::text('minimum_lower_bound',null,['class'=>'span4','id'=>'minimum_lower_bound', 'placeholder'=>'Width']) !!}
             {!! Form::text('maximum_lower_bound',null,['class'=>'span4','id'=>'maximum_lower_bound', 'placeholder'=>'Height']) !!}
            </div>
        </div>        

        <div class="control-group">
            <label class="control-label" for="basicinput">Upper Bound</label>
            <div class="controls">
                 {!! Form::text('minimum_bound_label',null,['class'=>'span4','id'=>'minimum_bound_label', 'placeholder'=>'Width']) !!}
                 {!! Form::text('maximum_bound_label',null,['class'=>'span4','id'=>'maximum_bound_label', 'placeholder'=>'Height']) !!}
            </div>
        </div>

        <div class="control-group" id="unit_count" style="display:none;">
            <label class="control-label" for="basicinput">Unit(Count)</label>
            <div class="controls">                
                 <input  type="text" name="maximum_unit_c" placeholder="Maximum Unit" id="maximum_unit_1" class="span4" />
                 <input  type="text" name="minimum_unit_c" placeholder="Minimum Unit" id="minimum_unit_1" class="span4">
            </div>
        </div>
        <div class="control-group" id="unit_grams" style="display:none;">
            <label class="control-label" for="basicinput">Unit(Grams)</label>
            <div class="controls">             
                 <input  type="text" name="maximum_unit_g" placeholder="Maximum Unit" id="maximum_unit_2" class="span4">
                 <input  type="text" name="minimum_unit_g" placeholder="Minimum Unit" id="minimum_unit_2" class="span4">
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                {!! Form::submit('Save', ['class' => 'btn']) !!}
                 <a href="{!! url('admin/package')!!}" class="btn">Back</a>
            </div>
        </div>
        {!! Form::hidden('form_factor_select',null,['class'=>'span4','id'=>'form_factor_select']) !!}
        {!! Form::hidden('form_factor_type',null,['class'=>'span4','id'=>'form_factor_type']) !!}
      </form>
        {{-- Form::close() --}}

@stop
@section('scripts')
  <!-- jQuery Form Validation code -->
  <script type="text/javascript">
  
  // When the browser is ready...
  $(function() {   

    // Setup form validation  //
    


    $("#form_factor").validate({
    
        // Specify the validation rules
        rules: {
            name: "required" ,
           // formfactor: "required" ,            
            maximum_width: 
            {
                number: true
            },
            maximum_height: 
            {
                number: true
            },
            maximum_depth: 
            {
                number: true
            },
            price: 
            {
                number: true
            },
            maximum_unit: 
            {
                number: true
            },
            minimum_lower_bound: 
            {
                number: true
            }, 
            minimum_bound_label: 
            {
                number: true                
            },
            maximum_bound_label: 
            {
                number: true
            }
            
                 
        },
        
        // Specify the validation error messages
        messages: {
            name: "Please enter package name",
            //formfactor: "Please enter package name",
            maximum_width: "",
            maximum_height: "",
            maximum_depth: "",
            price: "",
            maximum_lower_bound: "",
            minimum_lower_bound: "",
            minimum_bound_label: "",
            maximum_bound_label: ""
        }
        
        /*submitHandler: function(form) {
            form.submit();
        }*/
    });

    /*$('form').submit(function(){
         var options = $('#formfactor > option:selected');
         if(options.length == 0){
             $(".multiselect").css("border", "solid 1px red");
             return false;
         }
    }); */

    $('#formfactor').on('change', function() {
         var options = $('#formfactor > option:selected');
         if(options.length == 0){ 
            //Hide all unit rows       
            $("#unit_count").hide();
            $("#unit_grams").hide();

            //Hide all unit rows       
            $("#maximum_unit_1").val('');
            $("#minimum_unit_1").val('');


            $("#maximum_unit_2").val('');
            $("#minimum_unit_2").val('');
         }

    });

  });
  
  </script>
  
  <script type="text/javascript">
    $(document).ready(function() {

      $('#formfactor').multiselect(); 
        
       /* $.validator.addMethod("needsSelection", function (value, element) {
            var count = $(element).find('option:selected').length;
            return count > 0;
        });
        $.validator.messages.needsSelection = 'Select Atleast One Course';
        formfactor: {

                    needsSelection: true,
                    required:true

        },         
        ignore: ':hidden:not("#formfactor")',*/

              
    });

    function get_value()
    {         
        var formfactors = [];         
        $.each($(".formfactor option:selected"), function(){  
            //Add all selected value in an array            
            formfactors.push($(this).val());              
        });  

        //write all selected value in a textbox
        document.getElementById("form_factor_select").value=formfactors;

        //get all select value from textbox
        var form_factor_select = $("#form_factor_select").val();

        // Get form token value
        var token = document.getElementById('token').value
        if(form_factor_select=='')
        {

        }

        $.ajax({

            type:'POST',        
            headers: {'X-CSRF-TOKEN': token},
            url:'<?php echo url();?>/admin/package/appendfield/'+form_factor_select, 
            success: function(data) { 
                //Hide all unit rows       
                $("#unit_count").hide();
                $("#unit_grams").hide();

                //Hide all unit rows       
                $("#maximum_unit_1").val('');
                $("#minimum_unit_1").val('');


                $("#maximum_unit_2").val('');
                $("#minimum_unit_2").val('');

                //Add response to textbox         
                document.getElementById("form_factor_type").value=data;  

                var array = $('#form_factor_type').val().split(",");
                $.each(array,function(i){            
                    if(array[i]==1)
                    {                    
                        $("#unit_count").show();                
                    }
                    if(array[i]==2)
                    {               
                        $("#unit_grams").show();                  
                    }
                });          
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {         
            }
        });    
    }

        
</script>

@stop