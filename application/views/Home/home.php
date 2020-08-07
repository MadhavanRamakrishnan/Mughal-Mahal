<div class="section" id="ordernowSection">
    <div class="container clearfix">
        <div class="locationTop">

            <ul>
                <li>
                    <span>Delivery Location</span>
                    <?= $locality[0]->name ?>
                </li>
                <li>
                    <span>Delivery Time</span>
                    <?= $locality[0]->delivered_time ?>minutes Approx.
                </li>
                <li>
                    <a id="iframe2" href="#myModal" class="orderPopupOnlineorder"><?= $this->lang->line('change_preferences'); ?></a>
                </li>
        </div>
        <!-- <div class="orderContent"> -->

        <div class="leftMain">

            <div class="scroller_anchor">

                <div class="searchForm">
                    <form id="searchform" method="get" action="http://18.216.199.131/admin/">

                        <input id="s" type="text" name="s" value="" class="text" placeholder="Search.." />
                        <input id="x" type="submit" value="" class="button" />
                    </form>
                </div>
            </div>

            <div class="contentSection wow fadeInUp" data-wow-duration="1s" data-wow-delay="0" style="visibility: visible; animation-duration: 2s; animation-name: fadeInUp;">

                <div class="tabs_wrapper">


                    <ul class="tabs" id="category_tab">

                        <a href="#tab1">
                            <li rel="tab1">Offers</li>
                        </a>
                        <a href="#tab2">
                            <li rel="tab2">Kings Feast for 4</li>
                        </a>
                        <a href="#tab3">
                            <li rel="tab3">Recommended</li>
                        </a>
                        <a href="#tab4">
                            <li rel="tab4">Whopper®</li>
                        </a>
                        <a href="#tab5">
                            <li rel="tab5">Classic Burgers</li>
                        </a>
                        <a href="#tab6">
                            <li rel="tab6">King Savers</li>
                        </a>
                        <a href="#tab7">
                            <li rel="tab7">Add-Ons</li>
                        </a>
                        <a href="#tab8">
                            <li rel="tab8">Beverages</li>
                        </a>

                    </ul>

                    <div class="tab_container" id="dishes_tab">

                        <!--  tab 1 -->
                        <div id="tab1" class="tabClickcontent">
                            <h3 class="tab_drawer_heading" rel="tab1">Offers</h3>
                            <div class="tab_content">
                                <!--  <h2>Offers</h2> -->
                                <div class="productContent wow fadeIn" data-wow-duration="1s" data-wow-delay="0" style="visibility: visible; animation-duration: 2s; animation-name: fadeIn;">
                                    <img src="<?= site_url() ?>assets/front-end/images/product1.jpg" alt="" />
                                    <h3>Bigg Boss Whopper</h3>
                                    <p>Try Burger Kings "Vichitra Jodis" with the Soft buns & Crunchy chips; Creamy Cheese slice & Chunky salad mix </p>
                                    <h4>PROMOCODE: BIGGBOSS</h4>
                                    <a id="iframe" href="#data" class="orderPopup">Apply</a><span>*Terms and Conditions Apply</span>
                                </div>

                                <div class="productContent wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s" style="visibility: visible; animation-duration: 2s; animation-name: fadeIn;">
                                    <img src="<?= site_url() ?>assets/front-end/images/product1.jpg" alt="" />
                                    <h3>Bigg Boss Whopper 2</h3>
                                    <p>Try Burger Kings "Vichitra Jodis" with the Soft buns & Crunchy chips; Creamy Cheese slice & Chunky salad mix </p>
                                    <h4>PROMOCODE: BIGGBOSS</h4>
                                    <a id="iframe" href="#data" class="orderPopup">Apply</a><span>*Terms and Conditions Apply</span>

                                </div>

                                <div class="productContent wow fadeIn" data-wow-duration="1s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 2s; animation-name: fadeIn;">
                                    <img src="<?= site_url() ?>assets/front-end/images/product1.jpg" alt="" />

                                    <h3>Bigg Boss Whopper 3</h3>
                                    <p>Try Burger Kings "Vichitra Jodis" with the Soft buns & Crunchy chips; Creamy Cheese slice & Chunky salad mix </p>
                                    <h4>PROMOCODE: BIGGBOSS</h4>
                                    <a id="iframe" href="#data" class="orderPopup">Apply</a><span>*Terms and Conditions Apply</span>
                                    <div style="display:none">
                                        <div id="data">
                                            <h2>Chikken Tikka (All Time Hit)</h2>

                                            <div class="popupContent">
                                                <div class="leftPopup">
                                                    <h3>Your Choice Of(Choose 1) <span>required</span></h3>
                                                    <form>
                                                        <input type="radio" name="test" value="test" checked>Half<span>+2.350KD</span><br>
                                                        <input type="radio" name="test" value="test">Full<span>+3.950KD</span><br>
                                                    </form>
                                                    <h3>Your Choice Of(Choose 1) <span>required</span></h3>
                                                    <form>
                                                        <input type="radio" name="test" value="test" checked>Non Spicy<br>
                                                        <input type="radio" name="test" value="test">Less Spicy<br>
                                                        <input type="radio" name="test" value="test" checked>Medium Spicy<br>
                                                        <input type="radio" name="test" value="test">Spicy<br>
                                                    </form>
                                                    <div class="clear"></div>

                                                    <h3>Special Instruction</h3>
                                                    <input type="textarea" name="instruction" value="instruction" />

                                                </div>
                                                <div class="rightPopup">
                                                    <h3>Item Cart<span>$68.00</span></h3>

                                                    <div class="rightTwopopup">
                                                        <div class="left">
                                                            <p>Chicken Wings (15 Pcs)</p>
                                                            <div class="numbers-row">
                                                                <input type="text" name="numId" id="numId" value="1">
                                                            </div>
                                                        </div>
                                                        <div class="right">
                                                            <p>$ 39</p>
                                                        </div>
                                                    </div>
                                                    <div class="rightTwopopup">
                                                        <div class="left">
                                                            <p>10 Pcs Grilled & 5 Pcs Crispy Wings</p><br />
                                                            <div class="numbers-row">
                                                                <input type="text" name="numId" id="numId" value="1">
                                                            </div>
                                                        </div>
                                                        <div class="right">
                                                            <p>$ 29</p>
                                                        </div>
                                                    </div>

                                                    <div class="clear"></div>

                                                    <a href="" class="cartButton">ADD TO CART</a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="rightMain" id="sidebar">
            <div class="rightOut">
                <div class="sidebar__inner">
                    <div class="rightOne">
                        <h3>CART : 2 ITEMS
                            <span>$ 465</span></h3>
                    </div>
                    <div class="rightTwo">
                        <div class="left">
                            <div class="tooltip">
                                <p>Paneer King Melt Combo <img src="http://18.216.199.131/admin/assets/front-end/images/info.png" alt="">
                                    <span class="tooltiptext">Info</span>
                            </div>
                            </p><br />
                            <div class="numbers-row">
                                <input type="text" name="numId" id="numId" value="1">
                            </div>
                        </div>
                        <div class="right">
                            <div class="tooltip">
                                <p>$ 215 <img src="http://18.216.199.131/admin/assets/front-end/images/minus.png" alt="" /> </p>
                                <span class="tooltiptext">Info</span>
                            </div>
                        </div>
                    </div>
                    <div class="rightTwo rightThree">
                        <div class="left">
                            <div class="tooltip">
                                <p>Crispy Chicken Supreme Combo <img src="http://18.216.199.131/admin/assets/front-end/images/info.png" alt=""></p><br />
                                <span class="tooltiptext">Info</span>
                            </div>
                            <div class="numbers-row">
                                <input type="text" name="numId" id="numId" value="1">
                            </div>
                        </div>
                        <div class="right">
                            <div class="tooltip">
                                <p>$ 215 <img src="http://18.216.199.131/admin/assets/front-end/images/minus.png" alt="" /></p>
                                <span class="tooltiptext">Info</span>
                            </div>
                        </div>
                    </div>
                    <div class="rightTwo rightFour">
                        <div class="left">
                            <p>Subtotal</p>
                        </div>
                        <div class="right">
                            <p>$465</p>
                        </div>
                        <div class="clear"></div>

                        <div class="left">
                            <p>Delivery Charges @ 29 </p>
                        </div>
                        <div class="right">
                            <p>$29</p>
                        </div>
                        <div class="clear"></div>
                        <div class="left">
                            <p>Vat</p>
                        </div>
                        <div class="right">
                            <p>$10.98</p>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="rightTwo rightFive">
                        <form id="coupencode" method="get" action="http://18.216.199.131/admin/">
                            <input id="code" type="text" name="s" value="" class="text" placeholder="COUPON CODE" />
                            <input id="apply" type="submit" value="Apply" class="button" />
                        </form>
                    </div>

                    <div class="rightTwo rightSix">
                        <div class="left">
                            <p>Total</p>
                        </div>
                        <div class="right">
                            <p><b>$ 516</b></p>
                        </div>
                    </div>

                    <div class="rightTwo rightSeven">
                        <a href="http://mughalmahal.sweans.org/html/checkout.html">PROCEED TO CHECKOUT</a>
                    </div>
                </div>
            </div>
        </div>
        <!--  </div> -->
    </div>
</div>

<div style="display:none">
    <div id="data">
        <h2>Chikken Tikka (All Time Hit)</h2>

        <div class="popupContent">
            <div class="leftPopup">
                <h3>Your Choice Of(Choose 1) <span>required</span></h3>
                <form>
                    <input type="radio" name="test" value="test" checked>Half<span>+2.350KD</span><br>
                    <input type="radio" name="test" value="test">Full<span>+3.950KD</span><br>
                </form>
                <h3>Your Choice Of(Choose 1) <span>required</span></h3>
                <form>
                    <input type="radio" name="test" value="test" checked>Non Spicy<br>
                    <input type="radio" name="test" value="test">Less Spicy<br>
                    <input type="radio" name="test" value="test" checked>Medium Spicy<br>
                    <input type="radio" name="test" value="test">Spicy<br>
                </form>
                <div class="clear"></div>

                <h3>Special Instruction</h3>
                <input type="textarea" name="instruction" value="instruction" />

            </div>
            <div class="rightPopup">
                <h3>Item Cart<span>$68.00</span></h3>
                <div class="rightTwopopup">
                    <div class="left">
                        <p>Chicken Wings (15 Pcs)</p>
                        <div class="numbers-row">
                            <input type="text" name="numId" id="numId" value="1">
                        </div>
                    </div>
                    <div class="right">
                        <p>$ 39</p>
                    </div>
                </div>
                <div class="rightTwopopup">
                    <div class="left">
                        <p>10 Pcs Grilled & 5 Pcs Crispy Wings</p><br />
                        <div class="numbers-row">
                            <input type="text" name="numId" id="numId" value="1">
                        </div>
                    </div>
                    <div class="right">
                        <p>$ 29</p>
                    </div>
                </div>
                <div class="clear"></div>
                <a href="" class="cartButton">ADD TO CART</a>
            </div>
        </div>
    </div>
</div>
