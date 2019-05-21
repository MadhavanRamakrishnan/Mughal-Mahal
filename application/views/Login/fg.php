<?php
/*if(isset($response)){
print_r($response['message']); exit;


}*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>DFIA Reset Password</title>

  <meta name="description" content="">
  <meta name="author" content="Akshay Kumar">

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap/bootstrap.css" /> 

    <!-- Fonts  -->
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,500,600,700,300' rel='stylesheet' type='text/css'>
    
    <!-- Base Styling  -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/app/app.v1.css" />

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style type="text/css">
  .alert{
    color: red;
    
  }
</style>
</head>
<body>  
    
  
    <div class="container">
      <div class="row">
      <div class="col-lg-4 col-lg-offset-4">
          <h3 class="text-center">DFIA</h3>
            <p class="text-center"><h2>Thank You</h2><h3>Your Password is successfully reset!</h3></p>
              
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

<!-- <div align="center">
  <div class="row">
   <lable>Enter Your Email </lable><br>
   <form method="POST" action="<?php echo site_url('Login/forgotPassword'); ?>">
    <input type="text" name="email"><br>
    <input type="submit" name="submit">
  </div>

  
</div> -->