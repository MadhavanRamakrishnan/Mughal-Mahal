<?php
$class      = $this->router->fetch_class();
$method     = $this->router->fetch_method();
$url        = $class.'/'.$method; 
?>
<aside class="left-panel" style="padding:0px;">

    <div class="user text-center">
        <a href="<?php echo site_url('Dashboard');?>"><img src="<?php echo base_url(); ?>assets/images/avtar/mughal_mahal_logo.png" alt="..." style="width: 100%;background-color: #fff;border:none;"></a>
    </div>

    <nav class="navigation">
        <ul class="list-unstyled">

            <!-- <?php if (is_array($menu) && count($menu)>0) {
                foreach ($menu as $key => $value) {
                    ?>
                    <li  data="<?php echo $value->page_id; ?>" title="<?php echo $value->page_title; ?>" class="<?php if(isset($submenu[$value->page_id]) && count($submenu[$value->page_id])>0){echo 'has-active'; } ?> <?php if($url==$value->page_url || $class==$value->page_class) { echo 'active'; } ?>" >
                        <a href="<?= site_url($value->page_url); ?>" class="waves-effect">
                          <i class="<?php echo $value->page_icon; ?>"></i>
                          <span class="nav-label"> <?php echo $value->page_title; ?> </span>
                          <?php if(isset($submenu[$value->page_id]) && count($submenu[$value->page_id])>0){ ?>
                              <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        <?php } ?>

                        <?php if($value->page_title=='Orders'){ ?>
                            <span class="orderCount1" id="newBadge1"><div></div></span>
                        <?php } ?>
                    </a>
                    <?php if(isset($submenu[$value->page_id]) && count($submenu[$value->page_id])>0){ ?>
                        <ul class="list-unstyled submenuCustom submenuCustom_<?php echo $value->page_id; ?>" pid="<?php echo $value->page_id; ?>" style="display: <?php echo ($url==$value->page_url)?'block':'none'; ?>;">
                            <?php if(isset($submenu[$value->page_id])){ 
                                foreach ($submenu[$value->page_id] as $submenuKey => $submenuValue){
                                    ?>
                                    <li class="<?php if($url==$submenuValue->page_url){echo 'active'; } ?>"><a href="<?php echo site_url($submenuValue->page_url); ?>"><?php echo $submenuValue->page_title; ?>
                                </a></li>
                                <?php } ?>
                            </ul>
                        <?php } } ?>
                    </li>
                <?php } } ?> -->


                <li class="<?php if($this->router->fetch_class()=='Dashboard' && $this->router->fetch_method()=='index'){ echo 'active';} ?>">
                    <a href="<?php echo site_url('Dashboard/index'); ?>">
                        <i class="fa fa-tachometer"></i> 
                        <span class="nav-label">Dashboard</span>
                    </a>
                </li>

                <li class="has-submenu <?php if(($this->router->fetch_class()=='Dishes' || $this->router->fetch_class()=='Category' || $this->router->fetch_class()=='Choice') && ($this->router->fetch_method()=='dishList' || $this->router->fetch_method()=='addDishDetail' || $this->router->fetch_method()=='editDishDetail' || $this->router->fetch_method()=='dishCategory' || $this->router->fetch_method()=='addCategory' || $this->router->fetch_method()=='editCategory' || $this->router->fetch_method()=='choicesList' || $this->router->fetch_method()=='choiceCategoryList')) { echo 'active';} ?>">

                    <a href="#"><i class="fa fa-cubes"></i> <span class="nav-label">DISH MANAGEMENT</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-right pull-right" style="margin-right: -5px;"></i>
                        </span>
                    </a>
                        <ul class="list-unstyled">
                            <li class="<?php if($this->router->fetch_class()=='Category' && ($this->router->fetch_method()=='dishCategory' || $this->router->fetch_method()=='addCategory' || $this->router->fetch_method()=='editCategory')){ echo 'active';} ?>">
                            <a href="<?php echo site_url('Category/dishCategory'); ?>">
                               Dish Categories
                            </a>
                        </li>

                        <li class="<?php if($this->router->fetch_class()=='Choice' && $this->router->fetch_method()=='choiceCategoryList'){ echo 'active';} ?>">
                            <a href="<?php echo site_url('Choice/choiceCategoryList'); ?>">
                               Choice Category
                            </a>
                        </li>

                        <li class="<?php if($this->router->fetch_class()=='Choice' && $this->router->fetch_method()=='choicesList'){ echo 'active';} ?>">
                            <a href="<?php echo site_url('Choice/choicesList'); ?>">
                               Choices
                            </a>
                        </li>

                        <li class="<?php if($this->router->fetch_class()=='Dishes' && $this->router->fetch_method()=='dishList'){ echo 'active';} ?>">
                            <a href="<?php echo site_url('Dishes/dishList'); ?>">
                               Dishes
                            </a>
                        </li>
                        </ul>
                    </li>
               

                <li  class="has-submenu <?php if($this->router->fetch_class()=='Restaurants' && ($this->router->fetch_method()=='ownersList' || $this->router->fetch_method()=='addOwner' || $this->router->fetch_method()=='editOwner' || $this->router->fetch_method()=='sales_person'  || $this->router->fetch_method()=='addSalesPerson'  || $this->router->fetch_method()=='editSalesPerson' || $this->router->fetch_method()=='index' || $this->router->fetch_method()=='addRestaurants' || $this->router->fetch_method()=='restaurantDishes' || $this->router->fetch_method()=='editRestaurants' || $this->router->fetch_method()=='editDish'|| $this->router->fetch_method()=='listBestDishes')) { echo 'active';} ?>">

                    <a href="#">
                        <i class="fa fa-cutlery"></i> 
                        <span class="nav-label">RESTAURANT MANAGEMENT</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-right pull-right"></i>
                        </span>
                    </a>   
                    <ul class="list-unstyled">
                        <li class="<?php if($this->router->fetch_class()=='Restaurants' && ($this->router->fetch_method()=='ownersList' || $this->router->fetch_method()=='addOwner' || $this->router->fetch_method()=='editOwner')){ echo 'active';} ?>">
                            <a href="<?php echo site_url('Restaurants/ownersList'); ?>">
                               Manager
                            </a>
                        </li>

                        <li class="<?php if($this->router->fetch_class()=='Restaurants' && ($this->router->fetch_method()=='sales_person' || $this->router->fetch_method()=='addSalesPerson' || $this->router->fetch_method()=='editSalesPerson')){ echo 'active';} ?>">
                            <a href="<?php echo site_url('Restaurants/sales_person'); ?>">
                               Sales Person
                            </a>
                        </li>

                        <li class="<?php if($this->router->fetch_class()=='Restaurants' && ($this->router->fetch_method()=='index' || $this->router->fetch_method()=='addRestaurants' || $this->router->fetch_method()=='restaurantDishes' || $this->router->fetch_method()=='editRestaurants' || $this->router->fetch_method()=='editDish' || $this->router->fetch_method()=='listBestDishes')){ echo 'active';} ?>">
                            <a href="<?php echo site_url('Restaurants/index'); ?>">
                               Restaurant
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="has-submenu <?php if($this->router->fetch_class()=='Orders' && ($this->router->fetch_method()=='index' || $this->router->fetch_method()=='addOrder' || $this->router->fetch_method()=='orderTypeList' || $this->router->fetch_method()=='getOrderDetails')){ echo 'active';} ?>">
                     <a href="#">
                        <i class="fa fa-list"></i> 
                        <span class="nav-label">ORDERS</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-right pull-right"></i>
                        </span>
                    </a>   
                    <ul class="list-unstyled">
                        <li class="<?php if($this->router->fetch_class()=='Orders' && ($this->router->fetch_method()=='index' || $this->router->fetch_method()=='addOrder' || $this->router->fetch_method()=='getOrderDetails')){ echo 'active';} ?>">
                            <a href="<?php echo site_url('Orders/index'); ?>">
                               Order Management
                            </a>
                        </li>

                        <li class="<?php if($this->router->fetch_class()=='Orders' && $this->router->fetch_method()=='orderTypeList'){ echo 'active';} ?>">
                            <a href="<?php echo site_url('Orders/orderTypeList'); ?>">
                               Order Category
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="<?php if($this->router->fetch_class()=='Vehicles' && ($this->router->fetch_method()=='index' || $this->router->fetch_method()=='addVehicle' || $this->router->fetch_method()=='editVehicle')){ echo 'active';} ?>">
                    <a href="<?php echo site_url('Vehicles/index'); ?>">
                        <i class="fa fa-car"></i> 
                        <span class="nav-label">VEHICLES</span>
                        <span class="pull-right-container">
                        </span>
                    </a>
                </li>

                <li class="<?php if($this->router->fetch_class()=='Drivers' && ($this->router->fetch_method()=='index' || $this->router->fetch_method()=='addDrivers' || $this->router->fetch_method()=='editDrivers')){ echo 'active';} ?>">
                    <a href="<?php echo site_url('Drivers/index'); ?>">
                        <i class="fa fa-user"></i> 
                        <span class="nav-label">DRIVERS</span>
                        <span class="pull-right-container">
                        </span>
                    </a>
                </li>

                <li class="<?php if($this->router->fetch_class()=='Customers' && ($this->router->fetch_method()=='index' || $this->router->fetch_method()=='editCustomers')){ echo 'active';} ?>">
                    <a href="<?php echo site_url('Customers/index'); ?>">
                        <i class="fa fa-users"></i> 
                        <span class="nav-label">CUSTOMERS</span>
                        <span class="pull-right-container">
                        </span>
                    </a>
                </li>

                <li class="<?php if($this->router->fetch_class()=='Localities' && ($this->router->fetch_method()=='index' || $this->router->fetch_method()=='addLocality' || $this->router->fetch_method()=='editLocality')){ echo 'active';} ?>">
                    <a href="<?php echo site_url('Localities/index'); ?>">
                        <i class="fa fa-map-marker"></i> 
                        <span class="nav-label">LOCALITIES</span>
                        <span class="pull-right-container">
                        </span>
                    </a>
                </li>


                 <li  class="has-submenu <?php if($this->router->fetch_class()=='Reports' && ($this->router->fetch_method()=='sales_report' || $this->router->fetch_method()=='reports_list' || $this->router->fetch_method()=='driverReport')) { echo 'active';} ?>">

                    <a href="#">
                        <i class="fa fa-filter"></i> 
                        <span class="nav-label">REPORTS</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-right pull-right"></i>
                        </span>
                    </a>   
                    <ul class="list-unstyled">
                        <li class="<?php if($this->router->fetch_class()=='Reports' && $this->router->fetch_method()=='sales_report'){ echo 'active';} ?>">
                            <a href="<?php echo site_url('Reports/sales_report'); ?>">
                               Sales Report
                            </a>
                        </li>

                        <li class="<?php if($this->router->fetch_class()=='Reports' && $this->router->fetch_method()=='reports_list'){ echo 'active';} ?>">
                            <a href="<?php echo site_url('Reports/reports_list'); ?>">
                               Report
                            </a>
                        </li>

                        <li class="<?php if($this->router->fetch_class()=='Reports' && $this->router->fetch_method()=='driverReport'){ echo 'active';} ?>">
                            <a href="<?php echo site_url('Reports/driverReport'); ?>">
                               Driver Report
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="<?php if($this->router->fetch_class()=='Rating' && ($this->router->fetch_method()=='index' || $this->router->fetch_method()=='getDriverRating')){ echo 'active';} ?>">
                    <a href="<?php echo site_url('Rating/index'); ?>">
                        <i class="fa fa-star-o"></i> 
                        <span class="nav-label">RATING</span>
                        <!-- <span class="pull-right-container">
                        </span> -->
                    </a>
                </li>


            </ul>
        </nav>
    </aside>    
