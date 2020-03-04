<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $this->config->item('company_name'); ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/images/avtar/favicon.png">
    <meta name="description" content="">
    <meta name="author" content="Akshay Kumar">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap.css" />
    <link href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/datepicker.css" rel="stylesheet">

    <!-- Calendar Styling  -->
    <!-- <link rel="stylesheet" href="<?php //echo base_url(); ?>assets/css/plugins/calendar/calendar.css" /> -->

    <!-- Fonts  -->
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,300' rel='stylesheet' type='text/css'>

    <!-- Base Styling  -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app/app.v1.css" />


    <!-- DateTime Picker  -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/mycss.css" />

    <!-- Chosen select -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/bootstrap-chosen/chosen.css" />

    <!-- JQuery v1.9.1 -->
    <script src="<?php echo base_url(); ?>assets/js/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>  
    <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script> -->
    
    <!-- Bootstrap -->
    <script src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap-toggle.min.js"></script>
    <script type="text/javascript">
      var logoutLink ="<?php echo base_url("Login/logoutUser");  ?>";
  </script>
  
  <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAaWqUMoAlFiTao-1cDOQTtckOyA43eWQ4&libraries=places"></script>
  <!-- <script src="<?php echo base_url(); ?>assets/front-end/js/google.js"></script> -->

  <!-- DateTimePicker JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>

  <!-- Google Map -->
  <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-159057794-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-159057794-1');
    </script>


</head>

<body data-ng-app>

    <!-- Preloader -->
    <div class="loading-container">
        <div class="loading">
            <div class="l1">
                <div></div>
            </div>
            <div class="l2">
                <div></div>
            </div>
            <div class="l3">
                <div></div>
            </div>
            <div class="l4">
                <div></div>
            </div>
        </div>
    </div>

    <!-- Preloader -->

    <?php
    $this->load->view('Elements/left_nav');
    ?>

    <section class="content">

        <header class="top-head container-fluid">
            <button type="button" class="navbar-toggle pull-left">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php if(isset($userdata[0]->restaurant_name)){ ?>
               <span class="navbar-toggle pull-left Restaurant_name"><h4><?php echo $userdata[0]->restaurant_name; ?></h4></span>
           <?php } ?>
           <ul class="nav-toolbar">
            <li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu lg pull-right arrow panel panel-default arrow-top-right">
                    <div class="panel-body text-center">
                        <div class="row">
                                <!-- <div class="col-xs-12 col-sm-12"><a href="<?php echo site_url('User/LoginUser/'.$userdata[0]->user_id); ?>" class="text-green"><span class="h2"><i class="fa fa-user"></i></span><p class="text-gray no-margn">Profile</p></a></div> 

                                <div class="col-xs-12 visible-xs-block"><hr></div>

                                <div class="col-lg-12 col-md-12 col-sm-12  hidden-xs"><hr></div> -->
                                <div class="col-xs-12 col-sm-12 " style=" margin-top: 15px;"><a href="<?php echo site_url('User/editProfile'); ?>" class="text-red"><span class="h2"><i class="fa fa-user"></i></span><p class="text-gray no-margn">Profile</p></a></div>
                                <div class="col-xs-12 col-sm-12" style=" margin-top: 17px;"><a href="<?php echo site_url('Login/logoutUser'); ?>" class="text-red"><span class="h2"><i class="fa fa-power-off"></i></span><p class="text-gray no-margn">Logout</p></a></div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
        <!-- Header Ends -->
        <div class="modal fade" id="logOutConformation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel" align="center">Alert</h4>
                    </div>
                    <div class="modal-body" align="center">
                        <div class="form-group">
                            <label>You will logout within 5 minutes</label>
                        </div>
                    </div>

                </div>
            </div>
        </div>      

        <!-- <audio id="notifySound" preload="auto" type="audio/mpeg" ></audio> -->
        <audio id="notifySound">
          <source src="<?= base_url();?>assets/sound/notifySound.mp3" type="audio/mpeg">
          </audio>

          <script type="text/javascript">
              
           var userData = <?php echo json_encode($userdata[0]); ?>;
          </script>