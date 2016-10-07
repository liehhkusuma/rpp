<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title>Small ERP Application - Lingkar9</title>

  <link href="<?php echo assets('bo.css');?>/style.default.css" rel="stylesheet">
  <link href="<?php echo assets('bo.css');?>/main_style.css" rel="stylesheet">

  <script type="text/javascript" src="{{ route('config:javascript') }}"></script>
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
  <![endif]-->
</head>

<body class="signin">

<!-- Preloader -->
{{-- <div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div> --}}

<section>
  
    <div class="signinpanel">
        
        <div class="row">
            
            <div class="col-md-7">
                
                <div class="signin-info">
                    <div class="logopanel">
                        <h1><span>[</span> IntApp <span>]</span></h1>
                    </div><!-- logopanel -->
                
                    <div class="mb20"></div>
                
                    <h4><strong>You are on Lingkar9 Internal Application Small ERP!</strong></h4>
                    <p>Now you will make much cooperation with us. <br>
                    We share you the information you need for your administration. <br>
                    Sign in and enjoy!</p>
                    <div class="mb20"></div>
                    {{-- <strong>Not a member? <a href="/signup.html">Sign Up</a></strong> --}}
                </div><!-- signin0-info -->
            
            </div><!-- col-sm-7 -->
            
            <div class="col-md-5">
                
                <form id="TheForm" method="post" action="{{ route('AuthCtrl:dologin') }}" ajax-form="true">
                  {{ UI::CSRF() }}
                  <h4 class="nomargin">Sign In</h4>
                  <p class="mt5 mb20">Login to access your account.</p>

                  <div class="notif"></div>
              
                  <input name="username" type="text" class="form-control uname" placeholder="Username" />
                  <input name="password" type="password" class="form-control pword" placeholder="Password" />
                  <a href="#"><small>Forgot Your Password?</small></a>
                  <button type="submit" class="btn btn-success btn-block btn-loading">Sign In</button>
                </form>
            </div><!-- col-sm-5 -->
            
        </div><!-- row -->
        
        <div class="signup-footer">
            <div class="pull-left">
                &copy; {{ yearcopy(lang('backoffice.site.copy_year')) }}. All Rights Reserved. {{ lang('backoffice.site.copyright') }}
            </div>
            <div class="pull-right">
                Created By: <a href="http://{{ lang('backoffice.site.web') }}" target="_blank">{{ lang('backoffice.site.name') }}</a>
            </div>
        </div>
        
    </div><!-- signin -->
  
</section>

  <script src="<?php echo assets('bo.js');?>/jquery-1.10.2.min.js"></script>
  <script src="<?php echo assets('bo.js');?>/jquery-migrate-1.2.1.min.js"></script>
  <script src="<?php echo assets('bo.js');?>/bootstrap.min.js"></script>
  <script src="<?php echo assets('bo.js');?>/modernizr.min.js"></script>
  <script src="<?php echo assets('bo.js');?>/retina.min.js"></script>
  <script src="<?php echo assets('bo.js');?>/plugins.js"></script>

  <script src="<?php echo assets('bo.js');?>/custom.js"></script>
  <script src="<?php echo assets('bo.js');?>/main.js"></script>

  <script type="text/javascript">
  $(function(){
    $("#TheForm").validate({
      rules : {
        username : { required : true },
        password : { required : true },
      }
    });
  });
  </script>

</body>
</html>
