<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="Best family restaurant for the best Indian buffet in Hawally">
   <!-- <meta name="google-signin-client_id" content="823542492889-n17k4kteoi45l7q9kv2fltigsjopa8ob.apps.googleusercontent.com"> -->
   <meta name="google-signin-client_id" content="211802950628-t19693t361crs1ncel5klaf9uh3phni7.apps.googleusercontent.com">

   <title>Order Now | Mughal Mahal</title>

   <link rel="shortcut icon" href="<?= base_url() ?>assets/front-end/images/favicon.ico" type="image/x-icon">
   <link rel="icon" href="<?= base_url() ?>assets/front-end/images/favicon.ico" type="image/x-icon">
   <link rel="shortcut icon" href="<?= base_url() ?>assets/front-end/favicon.ico" type="image/x-icon">
   <link rel="icon" href="<?= base_url() ?>assets/front-end/favicon.ico" type="image/x-icon">

   <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/front-end/style.css"/>
   <link href="<?= base_url() ?>assets/front-end/css/lightslider.min.css" rel="stylesheet" media="screen" type="text/css"/>
   <link href="<?= base_url() ?>assets/front-end/css/animate.min.css" rel="stylesheet" media="screen" type="text/css"/>
   <link href="<?= base_url() ?>assets/front-end/css/colorbox.css" rel="stylesheet" type="text/css"/>
   <link href="<?= base_url() ?>assets/front-end/font/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
   <link href="<?= base_url(); ?>assets/css/chosen/chosen.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url('assets/front-end/css/modality.min.css'); ?>">

   
   <link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i,900" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
   
   <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/front-end/css/custom.css"/>

   <script src="<?= base_url() ?>assets/front-end/js/jquery.min.js"></script>
   <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
   <script src="<?= base_url() ?>assets/front-end/js/lightslider.min.js"></script>
   <!-- <script src="<?= base_url(); ?>assets/js/chosen/chosen.jquery.min.js"></script> -->
   <script type="text/javascript" src="<?= base_url() ?>assets/front-end/js/jquery.colorbox.js"></script> 
   <script src="https://apis.google.com/js/client:platform.js" async defer></script>
   <script src="<?= base_url(); ?>assets/front-end/js/google.js"></script>
   <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAaWqUMoAlFiTao-1cDOQTtckOyA43eWQ4&libraries=places"></script>
   <script type="text/javascript">
      jQuery(document).ready(function()
      {
          jQuery(".orderPopup").colorbox({inline:true, width:"90%" ,height:"80%",close: "Close"});

          jQuery(".orderPopupOnlineorder").colorbox({ inline:true  , className: 'my-class',close: "Close"  }); 

          jQuery(".topOrderButton").colorbox({ inline:true  , className: 'my-class',close: "Close"  }); 

          jQuery(".minusDishpopUp").colorbox({ inline:true,  width:"80%" , className: 'my-class',close: "Close" }); 

          jQuery(".addressModel").colorbox({ inline:true  , width:"80%" ,className: 'my-class',close: "Close" }); 
         
          jQuery(document).click( function(){
              jQuery('div.startorderonline').hide();
          });

      });
   </script>
   <link rel="stylesheet" href="<?= base_url(); ?>assets/front-end/css/jquery-ui.css">
   <link href="<?= base_url() ?>assets/front-end/css/style2.css" rel="stylesheet" type="text/css"/>
   <script src="<?= base_url() ?>assets/front-end/js/custom/footer.js" type="text/javascript"></script>

   <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-159057794-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-159057794-1');
    </script>

</head>
<body> 
  <script type="text/javascript">
     /*jQuery(".cartLink").click( function(event){
        alert("in");
            event.stopPropagation();
            jQuery("div.cartPopup").toggle();
      });*/
  </script>
<div id="loading-div-background" style="display:none">
      <div id="loading-div" class="ui-corner-all">
          <img src="<?php echo base_url('assets/images/front-end/loading1.gif'); ?>" alt="Loading..">
      </div>
</div>
<?php 
      $this->default_language   = $this->config->item('default_language');
      $this->supported_language = $this->config->item('supported_languages');
      if(isset($_COOKIE['lang']) && $_COOKIE['lang']!='' && in_array($_COOKIE['lang'],$this->supported_language))
      {
        $lang      = $_COOKIE['lang'];
        $langFile  = $lang.'_frontend_lang';
      }
      else
      {
        $lang      = 'EN';
        $langFile  = 'EN_frontend_lang'; 
      }

      $this->lang->load($langFile,$lang);
      
      $data=array();
      $data['message'] = new stdClass();
      $data['message']->Profile      = $this->lang->line('message_Profile');
      $data['message']->MyOrder      = $this->lang->line('message_MyOrder');
      $data['message']->Logout       = $this->lang->line('message_Logout');
      $data['message']->OrderOnline  = $this->lang->line('message_OrderOnline');
      $data['message']->AboutUs      = $this->lang->line('message_AboutUs');
      $data['message']->ContactUs    = $this->lang->line('message_ContactUs');

?>
<div class="section" id="topSecion">
  <div class="container">
      <ul class="langSwitch">
          <li class="lang"><span>EN</span></li>
          <li class="lang"><span>AR</span></li>
      </ul>
      <?php if($_COOKIE['user_id'] == ''){?>
      <ul class="logSwitch usernotlogin">
            <li><a href="#login"><?= $this->lang->line('message_Login') ?> / <?= $this->lang->line('sign_up') ?></a></li>
            <li><?= $userdata[0]->first_name." ".$userdata[0]->last_name; ?></li>
        </ul>
      <?php }else{?>
      <ul class="langSwitch userlogin">
            <li><?= $userdata[0]->first_name." ".$userdata[0]->last_name; ?>
              <button class="btn btn-primary"><a href="<?php echo base_url();?>Home/logoutUser">Logout</a></button>
            </li>
        </ul>
      <ul class="langSwitch">
            <li>
              <a href="<?php echo base_url();?>Home/myOrder" style="color: white;" >My Order</a>
            </li>
        </ul>
      <?php } ?>

      <a href="#startorderonlinedata2" class="topOrderButton"><span><?= $this->lang->line('message_onlineorder') ?></span></a>

      <div style="display:none">
          <div id="startorderonlinedata2">
            <div id="startorderonline" class="swForms fancybox-content" style="display: inline-block;">
                <h2><?= $this->lang->line('locality_rest') ?></h2>
                <div class="search_box">
                    <div class="serach_fild">
                    <!--   <input type="text" class="startorderonlineLocation autocomlocality" page="dish" autocomplete="" name="" placeholder="<?php echo $this->lang->line('message_autocompph'); ?>" > -->
                    
                    <select class="startorderonlineLocation chosen-select chosen" id="localityId"   data-placeholder="Select dish name">
                      <?php foreach ($this->locality as $key => $value) {?>
                      <option value="<?php echo $value->locality_id ?>" <?=  ($value->locality_id==$_COOKIE['locality_id'])?'selected':''; ?> ><?= $value->name; ?></option>
                      <?php } ?>
                    </select> 
                      
                    </div>
                    <div class="search_btn">
                        <a href="<?= site_url();?>"><input type="button" id="start_my_order" value="<?= $this->lang->line('start_my_order') ?>"></a>
                    </div>
                </div>
            </div>
          </div>
      </div>

      <?php if($this->uri->segment(2) != "orderSummary" && $this->uri->segment(2) != "myOrder"){?>
      <a href="#cartPopup" class="cartLink"><div id="cartCount"><?= $this->cartItem; ?></div></a>
      <?php } ?>
        
          <div class="cartPopup">                  
              <div class="rightTwopopup rightTwopopup-cart">
                  <div class="row11">
                    <div class="left left-cart">                                                    
                      <div class="numbers-row numbers-row-cart">                                        
                        <input type="text" name="numId" id="numId" value="1">
                      </div>
                      <p>10 Pcs Grilled</p>
                     </div>
                    <div class="right right-cart">
                      <p>$ 29</p>
                    </div>
                   <div class="order_cancle"><img dish_id="226" dishid="2" class="removedish" src="<?= base_url() ?>assets/front-end/images/closed.png"></div>
                  </div>
                  <div class="clear"></div>
                   <div class="row11">
                    <div class="left left-cart">                                                    
                      <div class="numbers-row numbers-row-cart">                                        
                        <input type="text" name="numId" id="numId" value="1">
                      </div>     
                      <p>10 Pcs Grilled</p>                       
                     </div>
                    <div class="right right-cart">
                      <p>$ 29</p>
                    </div>
                   <div class="order_cancle"><img dish_id="226" dishid="2" class="removedish" src="<?= base_url() ?>assets/front-end/images/closed.png"></div>
                  </div>
                  <div class="clear"></div>
                  <div class="row2">
                    <div class="left left-cart">
                        <p>Total</p>                         
                    </div>
                    <div class="right right-cart">
                        <p>$465</p>
                    </div>
                  </div>
                  <div class="clear"></div>
                  <a href="#" class="checkoutbutton">Checkout</a>
              </div>                    
          </div>

      <!--  -->

      <a href="https://www.instagram.com/mughalmahalkuwait/" class="instalink"></a>
  </div>
</div> 

<div class="header">
    <a href="<?= base_url() ?>" class="logoLink"><img src="<?= base_url() ?>assets/front-end/images/logo-with-bg.png" alt="Mughal Mahal" /></a>
    <div class="container">
        <div class="menuButtonOuter">
            <div class="menuButton"></div>
            <ul class="nav-menu" >
                <li id="menu-item-11" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-11"><a href="<?= base_url() ?>"><?= $this->lang->line("message_Home") ?></a></li>
                <li id="menu-item-12" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-12"><a href="http://www.mughalmahal.com/about-us/"><?php  echo $this->lang->line('message_AboutUs'); ?></a></li>
                <li id="menu-item-13" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-13"><a href="http://www.mughalmahal.com/contact-us/"><?php  echo $this->lang->line('message_ContactUs'); ?></a></li>
            </ul>
        </div>
    </div>
</div>


