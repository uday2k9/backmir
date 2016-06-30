<!DOCTYPE html>
<html lang="en">
<head>
	@include('admin.includes.head')
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title : 'Miramix' }}</title>
    @yield('scripts')
</head>
<body>

	 @include('admin.includes.header')
     
     
        <!-- /navbar -->
        <div class="wrapper">
            <div class="container">
                <div class="row">
                    <div class="span3">
                         @include('admin.includes.left')
                        
                        <!--/.sidebar-->
                    </div>
                    <!--/.span3-->
                    <div class="span9">
                    	<div class="content">
							<div class="module">
								<div class="module-head">
									<h3>{{ isset($module_head) ? $module_head : 'Miramix' }}</h3>
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
           @include('admin.includes.footer')
    </div>
</body>
</html>