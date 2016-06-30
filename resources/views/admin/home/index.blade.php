@extends('admin/layout/admin_template')

@section('content')

 
  <!-- /navbar -->
 
  <div class="btn-controls">
      <div class="btn-box-row row-fluid">
         <!--  <a href="#" class="btn-box big span4"><i class=" icon-random"></i><b>65%</b>
              <p class="text-muted">
                  Growth</p> 
          </a>-->
          <a href="#" class="btn-box big span4"><i class="icon-user"></i><b></b>
              <p class="text-muted">
                  Wel Come To Miramix Dashboard</p>
          </a>
          <!-- <a href="#" class="btn-box big span4"><i class="icon-money"></i><b>15,152</b>
              <p class="text-muted">
                  Profit</p>
          </a> -->

          <?php
          if(isset($msg)) echo $msg;
          ?>
      </div>
  </div>
                  
     

@stop