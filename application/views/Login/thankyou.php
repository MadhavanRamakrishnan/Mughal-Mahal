<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Vaibhav Mehta">
    <title><?php echo $this->config->item('company_name'); ?> Reset Password</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap/bootstrap.css" /> 

    <!-- Fonts  -->
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,500,600,700,300' rel='stylesheet' type='text/css'>
    
    <!-- Base Styling  -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/app/app.v1.css" />
    <style type="text/css">
      .alert{
        color: red;
      }
    </style>
  </head>
  <body>  
    <div class="container">
      <div class="row">
      <div class="col-lg-4 col-lg-offset-4 text-center" >
          <b><h3 ><u><?php echo $this->config->item('company_name'); ?></u></h3>
          <h2>Thank You!!!</h2></b>
          <i>
          <h3>Your Password is successfully reset!</h3>
         </i> 
         <a href="<?php echo site_url('Login');?>">
            <button type="button" class="btn btn-purple btn-block">Login</button>
         </a>
        </div>
        </div>
    </div>
    
    <!-- JQuery v1.9.1 -->
    <script src="<?php echo base_url(); ?>/assets/js/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>/assets/js/plugins/underscore/underscore-min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url(); ?>/assets/js/bootstrap/bootstrap.min.js"></script>
    
    <!-- Globalize -->
    <script src="<?php echo base_url(); ?>/assets/js/globalize/globalize.min.js"></script>
    
    <!-- NanoScroll -->
    <script src="<?php echo base_url(); ?>/assets/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>    
    <!-- Custom JQuery -->
    <script src="<?php echo base_url(); ?>/assets/js/app/custom.js" type="text/javascript"></script>
    
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      
      ga('create', 'UA-56821827-1', 'auto');
      ga('send', 'pageview');  
    </script>
  </body>
</html>