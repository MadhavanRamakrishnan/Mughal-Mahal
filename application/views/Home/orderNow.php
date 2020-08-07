<style>
    a.topOrderButton.cboxElement {
        display: none;
    }

</style>

<div class="banner bannerInner section" style="background-image: url(assets/images/front-end/orderBanner.jpg); ">
    <div class="bannerBack">
        <div class="container">
            <h1 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0" style="visibility: visible; animation-duration: 2s; animation-name: fadeInDown;"><?php  echo $this->lang->line('message_OrderNow'); ?></h1>
        </div>
    </div>
</div>
<div class="section" id="ordernowSection">
    <div class="container clearfix">
        <div class="locationTop">
            <ul>
                <li>
                    <span><?= $this->lang->line('delivery_location') ?></span>
                    <div id="locality_div"><?= $locality[0]->name ?></div>
                </li>

                <li><span>Min Order </span>
                    <div><?= $locality[0]->min_order_amount?> KD</div>
                </li>


                <li>
                    <span><?= $this->lang->line('delivery_time') ?></span>
                    <div id="locality_time_div"><?= $locality[0]->delivered_time ?><?= $this->lang->line('minutes_approx') ?></div>
                </li>
                <li>
                    <a id="iframe2" href="#myModal" class="change_preferences"><?= $this->lang->line('change_preferences'); ?></a>
                </li>
            </ul>
        </div>

        <!-- <div class="orderContent"> -->
        <div class="leftMain">
            <div class="scroller_anchor">
                <div class="searchForm">
                    <form id="searchform" method="post">
                        <input id="searchBox" type="text" name="s" value="" class="text" placeholder="<?= $this->lang->line('search') ?>" />
                    </form>
                </div>
            </div>

            <div class="contentSection wow fadeInUp" data-wow-duration="1s" data-wow-delay="0" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">
                <div class="tabs_wrapper">

                    <ul class="tabs" id="category_tab"></ul>
                    <div class="tab_container" id="dishes_tab"></div>



                </div>
            </div>
        </div>
        <div class="rightMain" id="sidebar">
            <div class="rightOut">
                <div class="sidebar__inner">

                    <div class="rightOne">
                        <h3><?= $this->lang->line('cart') ?> : 0 ITEMS
                            <span> 0 KD</span> </h3>
                    </div>


                    <div class="itemCart"></div>

                    <div class="rightTwo rightFour">
                        <div class="left">
                            <p><?= $this->lang->line('message_Subtotal') ?></p>
                        </div>
                        <div class="right">
                            <p><span id="subtotal">0.000</span> KD</p>
                        </div>
                        <div class="clear"></div>
                        <div class="left">
                            <p><?= $this->lang->line('packaging_charges') ?></p>
                        </div>
                        <div class="right">
                            <p><span id="deliveryCharge">0.000</span> KD</p>
                        </div>
                        <div class="clear"></div>
                    </div>







                    <div class="rightTwo rightSix">
                        <div class="left">
                            <p><?= $this->lang->line('message_Total') ?></p>
                        </div>
                        <div class="right">
                            <p><b><span id="total">0.000</span> KD</b></p>
                        </div>
                    </div>

                    <div class="rightTwo rightSeven">
                        <a class="check-out"><?= $this->lang->line('proceed_to_checkout') ?></a>
                    </div>



                </div>
            </div>
        </div>
        <!--  </div> -->
    </div>
</div>
<div style="display:none">
    <div id="data">
        <h2 id="ch_dish_name"><?= $this->lang->line('add_item_choice'); ?></h2>
        <div class="popupContent">
            <div class="leftPopup">
                <div class="dish_choices">
                    <form id="dish_choices_form">
                        <input type="hidden" name="finalPrice" id="finalPrice">
                        <h3>Your Choice Of(Choose 1) <span>required</span></h3>
                        <input type="radio" name="test" value="test" checked>Half<span>+2.350KD</span><br>
                        <input type="radio" name="test" value="test">Full<span>+3.950KD</span><br>
                        <h3>Your Choice Of(Choose 1) <span>required</span></h3>
                        <input type="radio" name="test1" value="test" checked>Non Spicy<br>
                        <input type="radio" name="test1" value="test">Less Spicy<br>
                        <input type="radio" name="test1" value="test" checked>Medium Spicy<br>
                        <input type="radio" name="test1" value="test">Spicy<br>
                    </form>
                </div>
                <div class="clear"></div>
                <h3><?= $this->lang->line('special_instruction') ?></h3>
                <input type="textarea" name="instruction" id="instruction" value="" placeholder="<?= $this->lang->line('dish_instruction') ?>" />
            </div>
            <div class="rightPopup">
                <h3><?= $this->lang->line('item_cart') ?>
                    <!-- <span>$68.00</span> -->
                </h3>
                <div class="rightTwopopup">
                    <div class="left openPopupAdd">
                        <p class="item_name">Chicken Wings (15 Pcs)</p>
                        <div class="numbers-row">
                            <input type="text" name="numId" id="numId" class="numDishId" value="1" dishPrice="" predishcount="">
                        </div>
                    </div>
                    <div class="right">
                        <p class="item_price">39 KD</p>
                    </div>
                </div>
                <div class="clear"></div>
                <a href="javascript:void(0)" class="cartButton addcart" onclick="formsubmit();"><?= $this->lang->line('add_to_cart') ?></a>
            </div>
        </div>

    </div>
</div>

<!-- custom scroll bar -->
<!--<link rel="stylesheet" href="http://manos.malihu.gr/repository/custom-scrollbar/demo/jquery.mCustomScrollbar.css" />
<script src="http://manos.malihu.gr/repository/custom-scrollbar/demo/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" >    
       (function($){
            $(window).load(function(){
              $(".testloop").mCustomScrollbar({
                scrollButtons:{enable:true},
                theme:"light-thick",
                scrollbarPosition:"outside"
            });
        });
    })(jQuery);
   
</script>-->
<!-- custom scroll bar -->


<div class="fixedCartbar">
    <div class="container">
        <div class="cartdetails">
            <a href="#cartPopup" onclick="document.documentElement.scrollTop = 0;" class="cartLink">
                <div id="cartCount" class="cartCountDown"><?= $this->cartItem; ?></div>
            </a>
            <div class="totalOrderDown">Order Total </div>
        </div>
        <div class="mobileCartbutton">
            <div class="buttonMob"><a href="#" class="checkoutbuttonDown">Checkout</a></div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/front-end/js/custom/orderNow.js?v=1.6" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/front-end/js/custom/dish.js?v=1.0" type="text/javascript"></script>
