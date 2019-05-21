<style type="text/css">
	.dish_top {
	    width: 100%;
	    float: left;
	    padding: 3px 0;
	}
	.dishes_menu .container {
    padding-bottom: 15px;
}
</style>
<div class="sub_page_main">
	<div class="dishes_main main_layout">
		<div class="dishes_menu">
			<div class="container">
				<div class="menu_cat_main">
					<div id="topMenu">
						<div id="box">
				  			<div class="menu_wrap">
				    			<ul class="list">
				    				<li><h2><?php  echo $this->lang->line('message_Favourites'); ?></h2></li>
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
					<input type="hidden" class="autocomlocality" autocomplete="" name="" placeholder="<?php echo $this->lang->line('message_autocompph'); ?>" value="<?php echo (isset($_SESSION['locality_value']))?$_SESSION['locality_value']:""; ?>" locality_id="<?php echo (isset($_SESSION['locality']))?$_SESSION['locality']:""; ?>" locality_val="<?php echo (isset($_SESSION['locality_value']))?$_SESSION['locality_value']:""; ?>" >
					<input type="hidden" id="is_favourite" value="1">
				</div>
			</div>
		</div>

		<div class="list_dish_category_wise">
		</div>
	</div>
	<div class="dishes_main mobi_layout">
		<div class="dishes_menu">
			<div class="container">
				<div class="menu_cat_main">
					<div id="topMenu">
						<div id="box">
				  			<div class="menu_wrap">
				    			<ul class="list">
				    				<li><h2><?php  echo $this->lang->line('message_Favourites'); ?></h2></li>
				    			</ul>
				  			</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="">
				<div class="dish_top">
					<div class="dish_top">
						<input type="hidden" class="autocomlocality" autocomplete="" name="" placeholder="<?php echo $this->lang->line('message_autocompph'); ?>" value="<?php echo (isset($_SESSION['locality_value']))?$_SESSION['locality_value']:""; ?>" locality_id="<?php echo (isset($_SESSION['locality']))?$_SESSION['locality']:""; ?>" locality_val="<?php echo (isset($_SESSION['locality_value']))?$_SESSION['locality_value']:""; ?>" >
					    <input type="hidden" id="is_favourite" value="1">
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
