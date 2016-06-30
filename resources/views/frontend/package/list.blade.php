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
                     
                    <div class="table-responsive">
                    <input type="hidden" name="site_base_url" id="site_base_url" value="<?php echo url();?>" />
                    <table class="table special_height">
                    <thead>
                      <tr>
                        <th>Package ID</th>
                        <th>Package Type</th>
                        <th>Image</th>
                        <th>Name</th>                        
                        <th>Edit</th>
                        <th>Delete</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                     @if(count($packages) > 0)
                     <?php $i=1;?>
                     @foreach($packages as $package){
                      <tr>
                        <td><?php echo $i;?></td>
                        <td>{{ \App\Model\PackageType::where('id',$package->package_type)->first()->name }}</td>
                        <td>
                          @if( \App\Model\PackageType::where('id',$package->package_type)->first()->image !='')
                            <img src="{{ url() }}/uploads/package/type/{{ \App\Model\PackageType::where('id',$package->package_type)->first()->image }}" width="80" />
                          @else
                            <img src="{{ url() }}/uploads/no-image-found.jpg" width="80" />
                          @endif
                        </td>
                        <td>
                          <?php echo $package->name ?>
                          
                        </td>                       
                       <td>

                          <a href="<?php echo url()?>/package/edit/{{ $package->id }}" class="btn btn-warning">Edit</a></td>
                       </td>
                       <td>
                         <a href="javascript:void(0);" onclick="delete_item({{ $package->id }})" class="btn btn btn-danger">Delete</a>
                       </td>
                      </tr>
                      <?php $i++;?>
                     @endforeach
                     @else
                     <tr>
                        <td colspan="6">No data available in table</td>                        
                      </tr>
                     @endif
                      
                    </tbody>
                  </table>
                  </div>
                    <div><?php echo $packages->render() ?></div>
                    <div class="form_bottom_panel">
                    <a href="<?php echo url();?>/brand-dashboard" class="green_btn pull-left"><i class="fa fa-angle-left"></i> Back to Dashboard</a>
                    <a href="<?php echo url();?>/package/create" class="green_sub text-center pull-right"><i class="fa fa-plus-circle"></i>Create Package</a> 
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
         // alert(id);
            if(confirm('Are you sure?'))
            {
                var site_base_url = $("#site_base_url").val();
                // alert(site_base_url);
                window.location.href=site_base_url+'/package/delete/'+id;
            }
        }
    </script>

 @stop