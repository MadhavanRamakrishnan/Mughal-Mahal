<style type="text/css">
	.scroller {
		display: block;
	}
</style>

<div class="sub_page_main">
	<div class="dishes_main main_layout">
		<div class="dishes_menu">
			<div class="container">
				<div class="menu_cat_main">
					<div id="topMenu">
						<div id="box">
				  			<div class="scroller scroller-left"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
				  			<div class="scroller scroller-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
				  			<div class="menu_wrap">
				    			<ul class="list">
				    			</ul>
				  			</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="dish_top">
					<div class="top_search">
						<input type="text" id="search" placeholder="<?php echo $this->lang->line('message_SearchMenu'); ?>">
					</div>
					<div class="locality">
						<input type="text" class="autocomlocality" page="dish" autocomplete="" name="" placeholder="<?php echo $this->lang->line('message_autocompph'); ?>" value="<?php echo (isset($_SESSION['locality_value']))?$_SESSION['locality_value']:""; ?>" locality_id="<?php echo (isset($_SESSION['locality']))?$_SESSION['locality']:""; ?>" locality_val="<?php echo (isset($_SESSION['locality_value']))?$_SESSION['locality_value']:""; ?>" >
						<input type="hidden" id="is_favourite" value="0">
					</div>
				</div>
			</div>
		</div>

		<div class="list_dish_category_wise">
			
		</div>
	</div>
	<div class="dishes_main mobi_layout">
		<div class="container">
			<div class="">
				<div class="dish_top">
					<div class="dish_top">
					<div class="top_search">
						<input type="text" id="searchForMob" placeholder="<?php echo $this->lang->line('message_SearchMenu'); ?>">
					</div>
					<div class="locality">
						<input type="text" class="autocomlocality" page="dish" autocomplete="" name="" placeholder="<?php echo $this->lang->line('message_autocompph'); ?>" value="<?php echo (isset($_SESSION['locality_value']))?$_SESSION['locality_value']:""; ?>" locality_id="<?php echo (isset($_SESSION['locality']))?$_SESSION['locality']:""; ?>" locality_val="<?php echo (isset($_SESSION['locality_value']))?$_SESSION['locality_value']:""; ?>" >
						<input type="hidden" id="is_favourite" value="0">
					</div>
				</div>
				</div>
			</div>
			<div class="row">
				<div class="dish_list_accordin">
					<div class="accordion"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="restaurant_id" value="">
<script type="text/javascript">
	var localityId       =$(".autocomlocality").attr("locality_id");
	var localityval      =$(".autocomlocality").attr("locality_val");
	if(localityId ==""){
		var loc_name   ="<?php echo $locality_name; ?>";
		var localityId ="<?php echo $locality_id; ?>";
		$(".autocomlocality").val(loc_name);
	}else{
		$(".autocomlocality").val(localityval);
	}
</script>
