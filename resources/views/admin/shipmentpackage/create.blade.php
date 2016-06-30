{!! HTML::script('resources/assets/js/ckeditor/ckeditor.js') !!} 

@extends('admin/layout/admin_template')

@section('content')

<!-- jQuery Form Validation code -->
<script>

// When the browser is ready...
        $(function() {

                    // Setup form validation on the #register-form element
                    $("#shipmentpackage_form").validate({

                        ignore: [],

                        rules: {
                            name: "required",
                            type: "required",
                            width: {
                            "required" : true,
                            "number": true
                            },
                            length: {
                            "required" : true,
                            "number": true
                            }

                        },

                        // Specify the validation error messages
                        messages: {
                            name: "Please enter Package Name.",
                            type: "Please enter Package Type.",
                            width: {
                                "required": "Please enter Package Width.",
                                "number": "Only Numbers allow"
                            },
                            length: {
                                "required": "Please enter Package Length.",
                                "number": "Only Numbers allow"
                            }

                        },               


                    });
                    
                    $('#p_type').change(function(){
                        
                    var p_type=$('#p_type').val();
                        $.ajax({
                        url: "<?php echo url();?>/ajax/create_child_types",
                        data: {parent_type:p_type,_token: '{!! csrf_token() !!}'},
                        type :"post",
                        success: function( data ) {
                        if (p_type!='0' && data!=""){
                            $("#typediv").html(data);
                        }
                        }
                        });
                    });
                    


        });
</script>
 
        {!! Form::open(['url' => 'admin/shipment-package','method'=>'POST', 'files'=>true,'class'=>'form-horizontal row-fluid','id'=>'shipmentpackage_form']) !!}
            <div class="control-group">
                <label class="control-label" for="basicinput">Name *</label>

                <div class="controls">
                     {!! Form::text('name',null,['class'=>'span8','id'=>'name']) !!}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="basicinput">Parent Types *</label>

                <div class="controls">
                     
                     {!! Form::select('p_type',$parenttypesarray,['class'=>'span8'],array('id'=>'p_type')) !!}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="basicinput">Types *</label>

                <div class="controls" id="typediv">
                     
                     {!! Form::select('type',$typesarray,['class'=>'span8','id'=>'type']) !!}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="basicinput">Width * </label>

                <div class="controls">
                     {!! Form::text('width',null,['class'=>'span3','id'=>'width']) !!}
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="basicinput">Height </label>

                <div class="controls">
                     {!! Form::text('height',null,['class'=>'span3','id'=>'height']) !!}
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="basicinput">Length *</label>

                <div class="controls">
                     {!! Form::text('length',null,['class'=>'span3','id'=>'length']) !!}
                </div>
            </div>
            

            
            
            

            <div class="control-group">
                <div class="controls">
                    {!! Form::submit('Save', ['class' => 'btn']) !!}
                   
                     <a href="{!! url('admin/shipment-package')!!}" class="btn">Back</a>
                   
                </div>
            </div>
        
        {!! Form::close() !!}

    @stop