 @extends('frontend/layout/frontend_template')
@section('content')
<div class="inner_page_container nomar_bottom">
<div id="nav-icon2">
  <span></span>
  <span></span>
  <span></span>
  <span></span>
  <span></span>
  <span></span>
</div>

<div class="mob_topmenu_back"></div>

<div class="top_menu_port">
 @include('frontend/includes/left_menu')
</div>
         <!--my_acct_sec-->
           <div class="my_acct_sec">           
               <div class="container">
               
               <div class="col-sm-10 col-sm-offset-1">
               
               <div class="row"><div class="form_dashboardacct">
                  <h3>Upload File</h3>
          
                 @if(Session::has('error'))
                    <div class="alert alert-error container-fluid">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! Session::get('error') !!}</strong>
                    </div>
                  @endif
                  @if(Session::has('success'))
                    <div class="alert alert-success container-fluid">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{!! Session::get('success') !!}</strong>
                    </div>
                  @endif
      
                    {!! Form::open(['url' => 'file/upload','method'=>'POST', 'files'=>true,  'id'=>'directory_create']) !!}
                      {!! Form::hidden('directory_id',$id,['class'=>'form-control','id'=>'directory_id','placeholder'=>'Diectory Name'])!!}
                      {!! Form::hidden('dir_name',$id,['class'=>'form-control','id'=>'dir_name','placeholder'=>'Diectory Name'])!!}
                      <div class="bottom_dash special_bottom_pad clearfix">                      
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="row">
                              <div class="form-group col-sm-12">                                
                                <div class="upload_button_panel">
                                    <p class="upload_image">
                                    <input class="upload_button files" type="file" name="image1[]" id="image1" multiple="" ></p>                                  
                                </div>
                              </div>
                            </div>  
                          </div>                    

                          <div class="form-group col-sm-6" id="path" style="padding-top:10px;">
                            <select name="directory_name" id="directory_name" class="form-control">
                                <option value="">Select Directory</option>
                                <option value="99999">Root</option>
                                @foreach($directories as $directory)
                                <option value="{{ $directory->id }}" <?php if($id==$directory->id){ echo "selected"; } ?> >{{ $directory->file_name }}</option>
                                @endforeach
                                
                            </select>
                          </div> 

                        </div>                        
                      </div>                    
                      <div class="form_bottom_panel">
                        <a href="<?php echo url();?>/file" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to List</a>                    
                        {!! Form::submit('Save', ['class' => 'btn btn-default green_sub pull-right']) !!}
                      </div>
                    {!! Form::close() !!}
               </div>
               
               </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
    
 </div>
 <script type="text/javascript">   
 // When the browser is ready...
    $( document ).ready(function() {
        var selectedText = $('#directory_name').find("option:selected").text();
        if(selectedText=='Select Directory')
        {
          var text = '';
        }
        else if(selectedText=='Root')
        {
          var text = '99999';
        }
        else
        {
          var text = selectedText; 
        }
        $('#dir_name').val(selectedText);

        $( "#directory_name" ).change(function() {
          var selectedText = $('#directory_name').find("option:selected").text();   
          if(selectedText=='Select Directory')
          {
            var text = '';
          }
          else if(selectedText=='Root')
          {
            var text = '99999';
          }
          else
          {
            var text = selectedText; 
          }        
          $('#dir_name').val(text);
        });
    }); 
 </script>

 <script type="text/javascript">   
 // When the browser is ready...
  $(function() {   

    // Setup form validation  //
    


    $("#directory_create").validate({    
        // Specify the validation rules
        rules: {
            "image1[]": "required",         
            "directory_name": "required"    
                   
        },
        
        // Specify the validation error messages
        messages: {
            "image1[]": "Please upload a file",        
            "directory_name": "Please select directory"        
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
 </script>
 @stop 