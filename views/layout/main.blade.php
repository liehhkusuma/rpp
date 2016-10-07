<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php echo assets('bo.images');?>/favicon.png" type="image/png">

    <title>Small ERP Application - Lingkar9</title>

    <!-- CSS REQUIRED FOR THIS PAGE ONLY
    ============================================= -->
    @yield('vendor_css')
    @yield('syntax_css')

    <!-- Stylesheets
    ============================================= -->
    <link href="<?php echo assets('bo.css');?>/jquery.gritter.css" rel="stylesheet">
    <link href="<?php echo assets('bo.css');?>/style.default.css" rel="stylesheet">
    <link href="<?php echo assets('bo.css');?>/main_style.css" rel="stylesheet">
    
    <script type="text/javascript" src="{{ route('config:javascript') }}"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo assets('bo.js');?>/html5shiv.js"></script>
    <script src="<?php echo assets('bo.js');?>/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>

<section>
    
    @include('backoffice.includes.left-panel')

    <div class="mainpanel">
    
    @include('backoffice.includes.header-bar')

    @yield('content')
    
    </div><!-- mainpanel -->

    {{-- @include('backoffice.includes.right-panel') --}}
  
</section>

    @yield('var_js')

    <!-- Core JavaScripts
    ============================================= -->
    <script src="<?php echo assets('bo.js');?>/jquery-1.10.2.min.js"></script>
    <script src="<?php echo assets('bo.js');?>/jquery.gritter.min.js"></script>
    <script src="<?php echo assets('bo.js');?>/bootstrap.min.js"></script>
    <script src="<?php echo assets('bo.js');?>/jquery-migrate-1.2.1.min.js"></script>
    <script src="<?php echo assets('bo.js');?>/modernizr.min.js"></script>
    <script src="<?php echo assets('bo.js');?>/jquery.sparkline.min.js"></script>
    <script src="<?php echo assets('bo.js');?>/toggles.min.js"></script>
    <script src="<?php echo assets('bo.vendor');?>/zclip/jquery.zclip.js"></script>
    <script src="<?php echo assets('bo.js');?>/retina.min.js"></script>
    <script src="<?php echo assets('bo.js');?>/jquery.cookies.js"></script>
    <script src="<?php echo assets('bo.js');?>/plugins.js"></script>
    
    <!-- This Page JavaScripts
    ============================================= -->
    @yield('vendor_js')

    <script src="<?php echo assets('bo.js');?>/custom.js"></script>
    <script src="<?php echo assets('bo.js');?>/main.js"></script>

    <!-- Footer Scripts
    ============================================= -->
    @yield('modal')
    @yield('footer_script')

</body>
</html>
