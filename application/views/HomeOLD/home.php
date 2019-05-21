<!-- Start Resturant section -->
<div class="resturant_section">
	<img src="<?php echo base_url('assets/images/front-end/search_section.jpg') ?>" alt="">
	<div class="overlay"></div>
	<!-- Start Container -->
	<div class="container">
		<div class="search_section">
			<h3><?php echo $message->bannertext ?></h3>
			<div class="search_box">
				<div class="serach_fild">
					<input type="text" class="autocomlocality"   placeholder="<?php echo $message->autocompph; ?>" value="">
				</div>
				<div class="search_btn">
					<a href="<?php echo site_url('Home/dishes'); ?>"><input type="submit" name="" value="<?php echo $this->lang->line('message_StartMyOrder'); ?>"></a>
				</div>
			</div>
		</div>
	</div><!-- Close Container -->
</div><!-- Close Resturant section -->

<!-- Start Popular Dishes section -->
<div class="popular_dishes">
	<div class="section_title">
		<h3><?php echo $this->lang->line('message_bestDishes'); ?></h3>
	</div>
	<div class="popular_dishes_slider dishesappend"></div>
</div><!-- Close Popular Dishes section -->

<!-- Start Food OF the Month section -->
<div class="food_month">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="food_left">
					<div class="food_txt">
						<h3><?php echo $this->lang->line('message_FOODOFTHEMONTH'); ?></h3>
						<p><span id="foodOfMonthName"><?php echo $this->lang->line('message_HamourMarmara'); ?></span></p>
						<a id="foodOfMonthUrl" href="<?= site_url('Home/dishes'); ?>"><?php echo $this->lang->line('message_OrderNow'); ?>
						</a>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="food_right">
					<img id="foodOfMonthImage" src="<?php echo base_url('assets/images/front-end/image_food_of_the_month.png'); ?>" alt="">
				</div>
			</div>
		</div>
	</div>
</div><!-- Close Food OF the Month section -->

<!-- Start Food OF the Month section -->
<div class="food_month_slider">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="food_right">
					<div class="owl-carousel  owl-theme" id="restaurant">
			            <div class="item">
			            	<img class="restaurant_img" src="<?php echo base_url('assets/images/front-end/img_our_restaurant.png'); ?>" alt="">
			            </div>
			        </div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="food_left">
					<div class="food_txt resDetails">
						<h3><?php echo $this->lang->line('message_OurRestaurants'); ?></h3>
						<p><span><?php echo $this->lang->line('message_Sharq'); ?></span></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- Close Food OF the Month section -->


<!-- Start First Choice section -->
<div class="first_choice">
	<!-- Start Container -->
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 colxs-12">
				<div class="first_choice_left">
					<h3><?php echo $this->lang->line('message_FirstChoice'); ?></h3>
					<p><?php echo $this->lang->line('message_OurVision'); ?></p>
					<a href="#"><?php echo $this->lang->line('message_MoreAboutUs'); ?></a>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 colxs-12">
				<div class="first_choice_right">
					<img src="<?php echo base_url('assets/images/front-end/img_first_choice.png'); ?>" alt="">
				</div>
			</div>
		</div>
	</div><!-- Close Container -->
</div><!-- Close First Choice section -->

<!-- Satrt Our Barnd section -->
<div class="our_brand">
	<!-- Start Container -->
	<div class="container">
		<div class="section_title">
			<h3><?php echo $this->lang->line('message_OurBrands'); ?></h3>
		</div>
		<div class="brand_logo">
			<ul>
				<li><img src="<?php echo base_url('assets/images/front-end/brands/Oval 2.png'); ?>"></li>
				<li><img src="<?php echo base_url('assets/images/front-end/brands/Oval 2 Copy.png'); ?>"></li>
				<li><img src="<?php echo base_url('assets/images/front-end/brands/Oval 2 Copy 2.png'); ?>"></li>
				<li><img src="<?php echo base_url('assets/images/front-end/brands/Oval 2 Copy 3.png'); ?>"></li>
				<li><img src="<?php echo base_url('assets/images/front-end/brands/Oval 2 Copy 4.png'); ?>"></li>
				<li><img src="<?php echo base_url('assets/images/front-end/brands/Oval 2 Copy 5.png'); ?>"></li>
			</ul>
		</div>
	</div><!-- Close Container -->
</div><!-- Close Our Barnd section -->


<div class="ftr_top_section">
	<img src="<?php echo base_url('assets/images/front-end/ftr_top_im.jpg'); ?>" alt="">
	<div class="container">
		<div class="ftr_position">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="ftr_position_txt">
					<h3><?php echo $this->lang->line('message_Orderfromyourphone'); ?></h3>
					<p><?php echo $this->lang->line('message_Downloadtheapp'); ?></p>
					<ul>
						<li>
							<img src="<?php echo base_url('assets/images/front-end/app_store.png'); ?>" alt="">
						</li>
						<li>
							<img src="<?php echo base_url('assets/images/front-end/google_play.png'); ?>" alt="">
						</li>
					</ul>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="ftr_position_im">
					<img src="<?php echo base_url('assets/images/front-end/phonemodel.png'); ?>">
				</div>
			</div>
		</div>
	</div>
</div>

