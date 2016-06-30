<!DOCTYPE html>
<html lang="en">
<head>
	@include('includes.head')
</head>
<body>

	 @include('includes.header')
     
        <!-- /navbar -->
        <div class="wrapper">
            <div class="container">
                <div class="row">
                    <div class="span3">
                         @include('includes.left')
                        <!--/.sidebar-->
                    </div>
                    <!--/.span3-->
                    <div class="span9">
                    	<div class="content">
							<div class="module">
								<div class="module-head">
									<h3>{{ isset($module_head) ? $module_head : 'Peru BookStore' }}</h3>
								</div>
								<div class="module-body">
                        			@yield('content')
                            	</div>
                            </div>
                        </div>
                        <!--/.content-->
                    </div>
                    <!--/.span9-->
                </div>
            </div>
            <!--/.container-->
        </div>
        <!--/.wrapper-->
    <div class="footer">
           @include('includes.footer')
    </div>
</body>
</html>