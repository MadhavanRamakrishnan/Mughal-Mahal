<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/datatables/jquery.dataTables.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order.js"></script>
<style>

table thead tr th, .center{
    text-align: center;
}
.checked {
color: orange;
}
.fsize{
    font-size:17px;
}
.r_rating {
    padding: 10px !important;
}
.r_rating img{
    width: 60px;
    border-radius: 50%;
    height: 60px;
    position: relative;
    top: -18px;
}
.r_rating_cnt{
    width: auto;
    display: inline-block;
    padding-left: 20px;
}
.r_rating.dashboard-stats:hover h3, .r_rating.dashboard-stats:hover p{ opacity: 1 !important; }
</style>
<div class="warper container-fluid">

<div class="row">
    <div class="col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
            
            <li class="active"><a href="<?php echo site_url("Rating/"); ?>" role="tab" >Restaurant Rating</a></li>
            <li><a href="<?php echo site_url("Rating/getDriverRating"); ?>">Driver's Rating</a></li>
        </ul>
        <br> 
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Restaurant rating</h1>
            </div>
        </div>
    </div> 
    <?php if(is_array($restoruntData ) && count($restoruntData)>0){
               foreach($restoruntData as $key => $value){ ?>   
    <div class="col-md-4 col-sm-3">
        <div class="panel panel-default clearfix dashboard-stats rounded r_rating">
            <!-- <span id="" class="sparkline transit"></span> -->
            <a href="<?php echo base_url('index.php/Rating/RestaurantsRatingUserWise/'.$value->restaurant_id) ?>">
                <img src="<?php echo base_url('/assets/uploads/restaurants/'.$value->banner_image);?>" hight='100' width='100' class=" "> 
                <div class="r_rating_cnt">
                <?php if(isset($value->avg_rate)) {
                ?>
                    <h3 class="transit">
                        <?php for($i=1;$i<= $value->avg_rate;$i++) { ?>
                        <span  class="fa fa-star fsize checked "></span>
                        <?php } ?>
                    </h3>

                    <?php } else{?>
                        <h3 class="transit"><span class="fa fa-o "></span></h3>
                    <?php } ?>
                    <p class=""><?php echo $value->restaurant_name; ?></p>
                </div>
            </a>
        </div>
    </div>
    <?php } } ?>
</div> 

