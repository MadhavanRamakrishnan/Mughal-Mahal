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
                
                <?php if (is_array($menu) && count($menu)>0) {
                    foreach ($menu as $key => $value) {
                ?>
                <li  data="<?php echo $value->page_id; ?>" title="<?php echo $value->page_title; ?>" class="<?php if(isset($submenu[$value->page_id]) && count($submenu[$value->page_id])>0){echo 'has-active'; } ?> <?php if($url==$value->page_url || $class==$value->page_class) { echo 'active'; } ?>" >
                    <a href="<?php if(isset($submenu[$value->page_id]) && count($submenu[$value->page_id])>0){echo site_url($value->page_url); } else { echo site_url($value->page_url); } ?>" class="waves-effect">
                      <i class="<?php echo $value->page_icon; ?>"></i>
                      <span class="nav-label"> <?php echo $value->page_title; ?> </span>
                      <?php if($value->page_title=='Orders'){ ?>
                        <span class="orderCount1" id="newBadge1"><div></div></span>
                    <?php } ?>
                    </a>
                    <?php if(isset($submenu[$value->page_id]) && count($submenu[$value->page_id])>0){ ?>
                        <ul class="list-unstyled submenuCustom submenuCustom_<?php echo $value->page_id; ?>" pid="<?php echo $value->page_id; ?>" style="display: <?php echo ($url==$value->page_url)?'block':'none'; ?>;">
                    <?php if(isset($submenu[$value->page_id])){ 
                        foreach ($submenu[$value->page_id] as $submenuKey => $submenuValue){
                    ?>
                        <li class="<?php if($url==$submenuValue->page_url){echo 'active'; } ?>"><a href="<?php echo site_url($submenuValue->page_url); ?>"><?php echo $submenuValue->page_title; ?></a></li>
                    <?php } ?>
                        </ul>
                    <?php } } ?>
                </li>
                <?php } } ?>
            </ul>
        </nav>
    </aside>    