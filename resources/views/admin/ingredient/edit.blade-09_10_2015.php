{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 


@extends('admin/layout/admin_template')

@section('content')
<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->

<!--<script>
$(document).ready(function(){
    var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];
    
    $( ".tags_auto" ).autocomplete({
      source: availableTags
    });
});
 
  </script>-->

<!-- jQuery Form Validation code -->
  <script>
  var intRegex = /^\d+$/;
  form_notsubmit_vitamin = 0;
  form_notsubmit_value = 0;
  form_notsubmit_name = 0;

  // When the browser is ready...
  $(document).ready(function(e) {

      
		  $(document).on('keyup','.vit_text',function(){
		 var $this=$(this);
		if($this.val()==''){
			$this.addClass('error_red');
			if($this.parent().find('.appended_error_p').length>0){
				
			}
			else{
			$this.parent().append('<label class="appended_error_p">Enter A Vitamins Value</label>');
			}
      form_notsubmit_vitamin = 1;
		}
		else{
			$this.parent().find('.appended_error_p').remove();
			$this.removeClass('error_red');	
      form_notsubmit_vitamin = 0;
		}
	  });
	  $(document).on('keyup','.comp_name',function(){

		 var $this=$(this);
		if($this.val()==''){
			$this.addClass('error_red');
			if($this.parent().find('.appended_error_comp').length>0){
				
			}
			else{
			$this.parent().append('<label class="appended_error_comp">Enter A Component Name</label>');
			}
      form_notsubmit_name = 1;
		}
		else{
			$this.parent().find('.appended_error_comp').remove();
			$this.removeClass('error_red');	
      form_notsubmit_name = 0;
		}
	  });
	  
	  $(document).on('keyup','.comp_value',function(){

		 var $this=$(this);
		 var this_val=$this.val();
		if(!intRegex.test(this_val)){
			$this.val('');
			$this.addClass('error_red');
			if($this.parent().find('.appended_error_val').length>0){
			$this.parent().find('.appended_error_val').html('Please Enter a Numeric value');	
			}
			else{
			$this.parent().append('<label class="appended_error_val">Please Enter a numeric value</label>');
			}
      
      form_notsubmit_value = 1;
		}
		else{

			$this.parent().find('.appended_error_val').remove();
			$this.removeClass('error_red');	
      form_notsubmit_value = 0;
		}
	  });
    $(document).on('click','.sub_btn_spec',function(){
		   var total=0;
		   //$('.input_fields_wrap').each(function(index, element) {            
        
			$('.vit_text').each(function(index, element) {
                var $this=$(this);
				console.log(($this).attr('class'));
				if($this.val()==''){
				$this.addClass('error_red');
				
				
					if($this.parent().find('.appended_error_p').length>0){
					//alert();
					}
					else{
												
						$this.parent().append('<label class="appended_error_p">Enter A Vitamins Value</label>');
					}
          form_notsubmit_vitamin = 1;
          return false;
				}
				else{
					//alert('asds');
					$this.parent().find('.appended_error_p').remove();
					$this.removeClass('error_red');	
          form_notsubmit_vitamin = 0;
				}
         });

		 //});
		 $('.comp_name').each(function(index, element) {
                var $this=$(this);
				if($this.val()==''){
				$this.addClass('error_red');	
				if($this.parent().find('.appended_error_comp').length>0){
				
					}
					else{
					$this.parent().append('<label class="appended_error_comp">Enter A Component Name</label>');
					}
          form_notsubmit_name = 1;
          return false;
				}
				else{
					$this.parent().find('.appended_error_comp').remove();
					$this.removeClass('error_red');	
          form_notsubmit_name = 0;
				}
         });
		 $('.comp_value').each(function(index, element) {
			 	
      var $this=$(this);
				total=parseInt(total)+parseInt($this.val());
				//alert(total);
				
				if($this.val()=='' || total!=100){
					$this.parent().find('.appended_error_val').remove();
					//alert(total);
					$this.addClass('error_red');
				if($this.parent().find('.appended_error_val').length>0){
				
					}
					else{
						if($this.val()==''){
							$this.parent().append('<label class="appended_error_val">Please Enter A Value</label>');	
						}
						if($('.module-head').find('.appended_error_val').length>0){					
							$('.module-head').find('.appended_error_val').html('Total Amount For all The component(%) should be equal to 100');
						}
						else{
							$('.module-head').append('<label class="appended_error_val">Total Amount For all The component(%) should be equal to 100</label>');	
						}
					}
          //form_notsubmit_value = 1;
          //return false;
				}
				else{
					//alert($this.val());					
					$('.module-head').parent().find('.appended_error_val').remove();
					$this.parent().find('.appended_error_val').remove();	
					$('.comp_value').removeClass('error_red');
          //form_notsubmit_value = 0;
				}
         });
		  
		 $("#edit_frm").validate({
        
        ignore: [],

        rules: {
            name: "required",
            chemical_name: "required",
            price_per_gram: {
                required:true,
                number:true
            },
            list_manufacture: "required",

            description: {
                        required: function() 
                        {
                        CKEDITOR.instances.description.updateElement();
                        }
                    }
        },
        
        // Specify the validation error messages
        messages: {
            name: "Please enter ingredient name.",
            chemical_name: "Please enter chemical name.",
            price_per_gram: {
                required:"Please enter price per gram.",
                number:"Input only numbers."
            },
            list_manufacture: "Please enter manufacture.",
            description: "Please enter description."
        },               

        submitHandler: function(form) {
          //alert(form_notsubmit_vitamin+'///'+form_notsubmit_value+'////'+form_notsubmit_name)

          if(form_notsubmit_vitamin==0 && form_notsubmit_value ==0 && form_notsubmit_name ==0)
           form.submit();
       
    
      
        }
    });
	  });
    // Setup form validation on the #register-form element
    

  });
  
  
  
  
  </script>
        

        {!! Form::model($ingredient,['method' => 'PATCH','route'=>['admin.ingredient.update',$ingredient->id],'class'=>'form-horizontal row-fluid','id'=>'edit_frm']) !!}

        <div class="control-group">
                <label class="control-label" for="basicinput">Ingredient Name *</label>

                <div class="controls">
                     {!! Form::text('name',null,['class'=>'span8','id'=>'name']) !!}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="basicinput">Chemical Name *</label>

                <div class="controls">
                     {!! Form::text('chemical_name',null,['class'=>'span8','id'=>'chemical_name']) !!}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="basicinput">Price / gm *</label>

                <div class="controls">
                     {!! Form::text('price_per_gram',null,['class'=>'span8','id'=>'price_per_gram']) !!}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="basicinput">Manufacture *</label>

                <div class="controls">
                     {!! Form::text('list_manufacture',null,['class'=>'span8','id'=>'list_manufacture']) !!}
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label" for="basicinput">Description *</label>
                <div class="controls">
                     {!! Form::textarea('description',null,['class'=>'span8 ckeditor','id'=>'description']) !!}
                </div>
            </div>
             <div class="control-group">
                <label class="control-label" for="basicinput">Type </label>
                <div class="controls">
                    {!! Form::select('type', array('synthetic' => 'Synthetic', 'whole_food' => 'Whole Food'),null, ['class' => 'field']); !!}
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="basicinput">Organic</label>
                <div class="controls">
                    {!! Form::select('organic', array('yes' => 'Yes', 'no' => 'No'),null, ['class' => 'field']); !!}
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="basicinput">Antibiotic Free</label>
                <div class="controls">
                    {!! Form::select('antibiotic_free', array('yes' => 'Yes', 'no' => 'No'),null, ['class' => 'field']); !!}
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="basicinput">GMO</label>
                <div class="controls">
                    {!! Form::select('gmo', array('yes' => 'Yes', 'no' => 'No'),null, ['class' => 'field']); !!}
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="basicinput">Form Factor</label>
                <div class="controls">
                @foreach($all_formfactors as $each_form_factor)
                    <input type="checkbox" name="form_factor[]" <?php echo ((in_array($each_form_factor->id, $all_check_formfactors))?'checked':''); ?> value="{!! $each_form_factor->id !!}" id="{!! $each_form_factor->id !!}"> &nbsp;{!! $each_form_factor->name !!}<br/>
                @endforeach

                </div>
            </div>
            <div class="tot_wrap">
               
                    <div class="control-group">
                        <label class="control-label" for="basicinput">Components Group</label>
                        <div class="controls">
                             <a href="javascript:void(0);" class="btn btn-success spec_btn"><span>+</span>Add Component Group</a>
                        </div>
                    </div>
                     
                    <?php 
                     //echo "<pre>"; print_r($all_components); exit;
                   // echo $all_components[0]['component_details']->id;
                   //  exit;

                    $i=0;
                    foreach($all_components as $each_component)
                    {
                      //echo $each_component['component_details']->name;
                      ?>
                   <div class="input_fields_wrap" id="<?php echo $i;?>">
                    <div class="control-group">
                        <label class="control-label" for="basicinput">Components Group</label>
                        <div class="controls">
                             <a href="javascript:void(0);" class="btn btn-danger remove_btn"><span>-</span>Delete Component Group</a>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="basicinput">Component Name</label>

                        <div class="controls">
                             <input class="span8 comp_name" type="text" name="component_name[<?php echo $i;?>][name]" value="<?php echo $each_component['component_details']->component_name;?>">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="basicinput">Component(%) Value</label>

                        <div class="controls">
                             <input class="span8 comp_value" type="text" name="component_name[<?php echo $i;?>][percentage]" value="<?php echo $each_component['component_details']->percentage;?>">
                        </div>
                    </div>

                    <?php foreach($each_component['vitamins'] as $each_vitamin) {
                      ?>
                    <div class="control-group">
                        <label class="control-label" for="basicinput">Vitamin</label>
                        <div class="controls">
                             <input class="span8 tags_auto vit_text" type="text" name="component_name[<?php echo $i;?>][vitamin][]" value="<?php echo $each_vitamin;?>">
                             <a href="javascript:void(0);" class="btn btn-danger remove_vitamin">-</a>
                        </div>
                    </div>
                    
                 <?php }?>
                    <div class="control-group">
                        <label class="control-label" for="basicinput">Add More Vitamins (Click On '+' item)</label>
                        <div class="controls">
                            
                             <a href="javascript:void(0);" class="btn btn-success add_vitamin">+</a>
                        </div>
                    </div>
                  </div>    
                 <?php 
                    $i++;
                   } 
                  ?>
               
            </div>
            
            


            <div class="control-group">
                <div class="controls">
                    {!! Form::submit('Save', ['class' => 'btn sub_btn_spec']) !!}
                  
                     <a href="{!! url('admin/ingredient')!!}" class="btn">Back</a>
                   
                </div>
            </div>
        
        {!! Form::close() !!}


   <script type="text/javascript">
    $(document).ready(function() {
   
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".tot_wrap"); //Fields wrapper
    var add_button      = $(".spec_btn"); //Add button ID
    var a=0;
    var x = '<?php  echo count($all_components)-1;?>'; //initlal text box count
    $(document).on('click','.spec_btn',function(e){ //on add input button click
        e.preventDefault();
        
            x++; //text box increment
            $(wrapper).append('<div class="input_fields_wrap" id="'+x+'"><div class="control-group"><label class="control-label" for="basicinput">Components Group</label><div class="controls"><a href="javascript:void(0);" class="btn btn-danger remove_btn"><span>-</span>Remove This Group</a></div></div><div class="control-group"><label class="control-label" for="basicinput">Component Name</label><div class="controls"><input class="span8 comp_name" type="text" name="component_name['+x+'][name]"></div></div><div class="control-group"><label class="control-label" for="basicinput">Component(%) Value</label><div class="controls"><input class="span8 comp_value" type="text" name="component_name['+x+'][percentage]"></div></div><div class="control-group"><label class="control-label" for="basicinput">Vitamin</label><div class="controls"><input class="span8 tags_auto vit_text" type="text" name="component_name['+x+'][vitamin][]"><a href="javascript:void(0);" class="btn btn-success add_vitamin">+</a></div></div></div>'); //add input box
			
			setTimeout(function(){
			$('html, body').animate({
				scrollTop: $("div#"+x+".input_fields_wrap").offset().top
			}, 400);
			$("div#"+x+".input_fields_wrap").addClass('blink_me');
			
			},600);
			setTimeout(function(){
				$("div#"+x+".input_fields_wrap").removeClass('blink_me');	
			},1600);
        
    });
    
    $(wrapper).on("click",".remove_btn", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent().parent().parent('div.input_fields_wrap').remove(); 
    });
	$(document).on("click",".add_vitamin", function(e){ //user click on remove text
        e.preventDefault(); 
		
		var $this=$(this);
		var this_id=$(this).parent().parent().parent('div.input_fields_wrap').attr('id');
		//alert(this_id);
		$this.parent().parent().parent('div.input_fields_wrap').append('<div class="control-group"><label class="control-label" for="basicinput">Vitamin</label><div class="controls"><input class="span8 tags_auto vit_text" type="text" name="component_name['+this_id+'][vitamin][]" id="vitamin'+a+'"><a href="javascript:void(0);" class="btn btn-danger remove_vitamin">-</a></div></div>');

			
	a++;
    });
	$(document).on("click",".remove_vitamin", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent().parent().remove();
    });
	
	
	
	
	
});
   </script>     

    @stop