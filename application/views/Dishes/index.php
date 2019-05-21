 
<?php
$value="";
if($this->uri->segment(2)=='searchDish'){
  $value=($this->input->post('search'))?$this->input->post('search'):$this->uri->segment(3); } ?>
  <div class="warper container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="page-header f-left">
          <h1>Dishes</h1>
        </div>

      </div>
    </div>

    <?php $successMsg=$this->session->flashdata('success_msg'); ?>

    <div class="alert alert-success alert-dismissible" id="success_notification" style="display:<?php echo ($successMsg)?"block":"none"; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <p id="success_message"><?php echo $successMsg; ?></p>
    </div>

    <div class="alert alert-danger alert-dismissible" id="error_notification" style="display:none;">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
      <h4><i class="icon fa fa-warning"></i> Failed!</h4>
      <p id="error_message"></p>
    </div>

    <div class="row">
      <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">

      </div>
      <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12">
        <div class="page-header f-right">
          <a href="<?php echo site_url('Dishes/addDishDetail'); ?>" type="button" class="btn btn-info">Add Dish</a>
        </div>

      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">Dishes List</div>
          <div class="panel-body table-responsive">

            <table class="table table-striped table-bordered" id="basic-datatable">
              <thead>
                <tr>
                  <th>Dish Category</th>
                  <th>Dish Name</th>
                  <th>Description</th>
                  <th>Image</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                if(count($dishlist)>0){
                  foreach ($dishlist as $key => $value) { 
                   $ImgLoc1="./assets/images/front-endold/dishes/".$value->dish_image;
                   if(file_exists($ImgLoc1) && !empty($value->dish_image)){

                    $photo = $value->dish_image;
                  }
                  else{
                    $photo = 'no_image.png';
                  } ?>

                  <tr id="dish_details_<?php echo $value->product_id; ?>">

                    <td><?php echo $value->category_name; ?></td>

                    <td><?php echo $value->product_en_name; ?></td>
                    <td><?php echo $value->en_description; ?></td>
                    <td align="center">
                      <div class="img-height">
                        <img style="width:80px;" src="<?php echo base_url()."assets/images/front-endold/dishes/".$photo; ?>">
                      </div>
                    </td>
                    <td class="center">
                      <input type="checkbox" class="activeSwitch<?php echo $value->product_id; ?> availibility" <?php echo ($value->is_active == 1)?"checked":"";  ?> data-toggle="toggle" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" id="<?php echo $value->product_id; ?>"> 
                    </td>
                    <td class="center"><a href="<?php echo site_url('Dishes/editDishDetail/'.$value->product_id); ?>"><i class="fa fa-edit"> </i></a> <!-- |<a title="Delete" data-toggle="modal" data-target="#confirmationModal" data-backdrop="static" data-keyboard="false" onclick="deleteDishDetail(<?php echo $value->product_id; ?>)"  id="delete"><i class="fa fa-trash"> </i></a> --></td>

                  </tr>
                  <?php
                }
              }
              ?>
            </tbody>
          </table>
               <!--  <div class="row">
                  <div class="col-sm-12"> 
                    <ul class="pagination showcase-pagination pull-right">
                      <?php foreach ($links as $link) {
                        echo "<li>".$link."</li>";
                      } ?>
                    </ul>
                  </div>
                </div> -->

              </div>
            </div>
          </div>
        </div>
      </div>

      <script type="text/javascript">
        var deleteDishDetailUrl    = "<?php echo site_url('Dishes/deleteDishDetail')?>";
        var cuisineId              = ""; 
        var categoryId             = "";
        var dishStatusUpdate       = "<?php echo site_url('Dishes/dishStatusUpdate')?>";
      </script>
      <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dishdetail.js"></script>