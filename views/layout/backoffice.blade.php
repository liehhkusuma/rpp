<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Unicorn Admin</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- CSS REQUIRED FOR THIS PAGE ONLY
		============================================= -->
		@yield('vendor_css')
		@yield('syntax_css')

		<!-- Stylesheets
	    ============================================= -->
        <link rel="stylesheet" href="<?php echo assets('bo.css');?>/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo assets('bo.css');?>/font-awesome.css" />
        <link rel="stylesheet" href="<?php echo assets('bo.css');?>/fullcalendar.css" />
        <link rel="stylesheet" href="<?php echo assets('bo.css');?>/jquery.jscrollpane.css" /> 
        <link rel="stylesheet" href="<?php echo assets('bo.css');?>/unicorn.css" />
        <link rel="stylesheet" href="<?php echo assets('bo.css');?>/main_style.css" />

    	<script type="text/javascript" src="{{ route('config:javascript') }}"></script>
        <!--[if lt IE 9]>
        <script type="text/javascript" src="<?php echo assets('bo.js');?>/respond.min.js"></script>
        <![endif]-->

        @yield('vendor_css')
        @yield('syntax_css')
			
	</head>	
	<body data-color="grey" class="flat">
		<div id="wrapper">
			<div id="header">
				<h1><a href="./index.html">Unicorn Admin</a></h1>	
				<a id="menu-trigger" href="#"><i class="fa fa-bars"></i></a>	
			</div>
			
			<div id="user-nav">
			@include('backoffice.includes.nav')
	        </div>

			<div id="sidebar">
			@include('backoffice.includes.sidebar')
			</div>
		
			<div id="content">
				<div class="container-fluid">
				@yield('content')
				</div>
				<div class="clearfix"></div>
			</div>

			<div class="row">
				<div id="footer" class="col-xs-12">
					2012 - 2013 &copy; Unicorn Admin. Brought to you by <a href="https://wrapbootstrap.com/user/diablo9983">diablo9983</a>
				</div>
			</div>
		</div>

            <script src="<?php echo assets('bo.js');?>/excanvas.min.js"></script>
            <script src="<?php echo assets('bo.js');?>/jquery.min.js"></script>
            <script src="<?php echo assets('bo.js');?>/jquery-ui.custom.js"></script>
            <script src="<?php echo assets('bo.js');?>/bootstrap.min.js"></script>
            <script src="<?php echo assets('bo.js');?>/jquery.flot.min.js"></script>
            <script src="<?php echo assets('bo.js');?>/jquery.flot.resize.min.js"></script>
            <script src="<?php echo assets('bo.js');?>/jquery.sparkline.min.js"></script>
            <script src="<?php echo assets('bo.js');?>/fullcalendar.min.js"></script>
            <script src="<?php echo assets('bo.js');?>/plugins.js"></script>

             <!-- This Page JavaScripts
			============================================= -->
			@yield('vendor_js')
            
            <script src="<?php echo assets('bo.js');?>/jquery.nicescroll.min.js"></script>
            <script src="<?php echo assets('bo.js');?>/unicorn.js"></script>
            {{-- <script src="<?php echo assets('bo.js');?>/unicorn.dashboard.js"></script> --}}
            <script src="<?php echo assets('bo.js');?>/custom.js"></script>
            <script src="<?php echo assets('bo.js');?>/main.js"></script>

             <!-- Footer Scripts
		    ============================================= -->
		    @yield('modal')
		    @yield('footer_script')
	</body>
</html>
