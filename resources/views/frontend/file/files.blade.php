@extends('frontend/layout/frontend_template')
@section('content')

<script src="<?php echo url();?>/public/frontend/js/stacktable.js"></script>
<link href="<?php echo url();?>/public/frontend/css/stacktable.css" rel="stylesheet">

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
               <div class="container" style="width:970px;">
               
               <div class="col-sm-10 col-sm-offset-1">
               
               <div class="row"><div class="form_dashboardacct">
                  <h3>{{ $title }}</h3>

                   @if(Session::has('error'))
                    <div class="alert alert-danger container-fluid">
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
                     
                    <div class="table-responsive">
                    <input type="hidden" name="site_base_url" id="site_base_url" value="<?php echo url();?>" />
                    <table class="table special_height">
                    <thead>
                      <tr>
                        <th>Preview</th>
                        <th>Name</th>  
                        <th>Upload On</th>                     
                        <th>Rename</th>
                        <th>Delete</th>                        
                      </tr>
                    </thead>
                    <tbody>
                      @if(count($filemanagement) >0 )
                      <?php $i=1; ?>
                      @foreach($filemanagement as $file)                      
                      <tr>
                        <td>
                          @if($file->directory==0)
                          <?php
                            $f_name=$file->file_path.'/'.$file->file_name;                           
                            $file_type=get_mime_type($f_name);                           
                            if($file_type=="msword")
                            {
                              $icon=url().'/public/images/word.png';
                            }
                            else if($file_type=="pdf")
                            {
                              $icon=url().'/public/images/pdf.png';
                            }
                            else if($file_type=="image")
                            {
                              $icon=$file->file_url;
                            }
                            else
                            {
                              $icon=url().'/public/images/other.png';
                            }
                          ?>       
                          <img src='{{ $icon }}' width='64' />
                          @else                          
                          <img src='{{ url() }}/public/images/directory.png' width='64' />
                          @endif
                        </td>  
                        <td>
                          {{ $file->file_name }}
                        </td>                 
                        <td>{{ Carbon\Carbon::parse($file->updated_at)->format('M/d/Y') }}</td>                        
                        <td>
                          <a href="<?php echo url();?>/file/rename/{{ $file->id }}"  class="btn btn btn-danger">Rename</a>
                        </td>
                        <td>
                          <a href="javascript:void(0);" onclick="delete_item({{ $file->id }})" class="btn btn btn-danger">Delete</a>
                        </td>                         
                      </tr>
                      <?php $i++ ?>
                      @endforeach
                      @else
                     <tr>
                        <td colspan="6">No data available in table</td>                        
                      </tr>
                     @endif
                    </tbody>
                  </table>
                  </div>
                    <div><?php echo $filemanagement->render() ?></div>
                    <div class="form_bottom_panel">
                    <a href="<?php echo url();?>/file" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to List</a>
                    <a href="<?php echo url();?>/file/upload/{{ $id }}" class="green_sub text-center pull-right"><i class="fa fa-plus-circle"></i>Upload File</a> 
                    </div>
               </div>
               
               </div>
               
               </div>
               
               </div>           
           </div>
           <!--my_acct_sec ends-->
 </div>
 <script>
// Delete Coupon from brand //

  function ConfirmDelete()
  {
  var x = confirm("Are you sure you want to delete?");
  if (x)
    return true;
  else
    return false;
  }

</script>
<script>
        function delete_item(id)
        {         
            if(confirm('Are you sure you want to delete?'))
            {
                var site_base_url = $("#site_base_url").val();                
                window.location.href=site_base_url+'/file/delete/'+id;
            }
        }
    </script>

 @stop
 <?php
    function get_mime_type($file)
    {
        // our list of mime types
        $mime_types = array(
                "pdf"=>"pdf"
                ,"exe"=>"application/octet-stream"
                ,"zip"=>"application/zip"
                ,"docx"=>"msword"
                ,"doc"=>"msword"
                ,"xls"=>"application/vnd.ms-excel"
                ,"ppt"=>"application/vnd.ms-powerpoint"
                ,"gif"=>"image"
                ,"png"=>"image"
                ,"jpeg"=>"image"
                ,"jpg"=>"image"
                ,"mp3"=>"audio/mpeg"
                ,"wav"=>"audio/x-wav"
                ,"mpeg"=>"video/mpeg"
                ,"mpg"=>"video/mpeg"
                ,"mpe"=>"video/mpeg"
                ,"mov"=>"video/quicktime"
                ,"avi"=>"video/x-msvideo"
                ,"3gp"=>"video/3gpp"
                ,"css"=>"text/css"
                ,"jsc"=>"application/javascript"
                ,"js"=>"application/javascript"
                ,"php"=>"text/html"
                ,"htm"=>"text/html"
                ,"html"=>"text/html"
                ,"sql"=>"sql"
                ,"csv"=>"csv"
        );
        $a=explode('.',$file);
        $extension = end($a); 
       return $mime_types[$extension];
    }
 ?>