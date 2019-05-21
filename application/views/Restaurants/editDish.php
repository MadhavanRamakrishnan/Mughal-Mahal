<style type="text/css">
    .dish_choices{padding-top:5%;}
    .dish_btn{margin-bottom:1%;float:right;}
    .addDishes{padding-right:2%;padding-left:9px;}
    .error,.dishError,.dishPriceError{color:red;}
    .dish_btn{bottom: 5px; float: right; position: relative;}
</style>
<link href="<?php echo base_url(); ?>assets/chosen/css/chosen.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/chosen/js/chosen.jquery.min.js"></script>
<div class="warper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>Restaurant Dishes</h1>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-12">
      <?php $successMsg=$this->session->flashdata('success_msg'); ?>
      <?php $errorMsg  =$this->session->flashdata('error_msg'); ?>
      
       <div class="alert alert-success alert-dismissible" id="success_notification" style="display:<?php echo ($successMsg)?"block":"none"; ?>">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            <p id="success_message"><?php echo $successMsg; ?></p>
        </div>

      <div class="alert alert-danger alert-dismissible" id="error_notification" style="display:<?php echo ($errorMsg)?"block":"none"; ?>">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-warning"></i> Failed!</h4>
        <p id="error_message"><?php echo $errorMsg; ?></p>
      </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="post"  enctype="multipart/form-data" action="<?php echo site_url("Restaurants/editDish/".$resDish[0]->fk_dish_id."/".$res_id); ?>">
                <div class="addDishes">
                    <div class="row">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <span class="nav-label">edit Dish</span>
                            </div>
                            <div class="panel-body">
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-7">
                                                <label class="control-label">Dishes <i class="reustarred">*</i></label>
                                               
                                                <select class="form-control" name="dish" readonly>
                                                    <option value="<?php echo $resDish[0]->product_id; ?>"><?php echo $resDish[0]->product_en_name; ?></option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3" style="padding-top:10px;">
                                                <label class="control-label">Dish Price</label>
                                                <span class="col-sm-12 dishPriceError"><?php echo $error; ?></span>
                                                <input type="text" class="form-control price" name="dish_price" value="<?php echo $resDish[0]->dish_price; ?>">
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="col-sm-7 control-label" >Dish choices</label>
                                            <label class="col-lg-3 control-label" >Choice Price</label>
                                            <div class="choise_div">
                                                <?php if(!empty($dishChoice)){

                                                  //echo "<pre>";  print_r($dishChoice);exit;
                                                    foreach ($dishChoice as $key1 => $value1) {?>
                                                        <div class="dish_choices" >
                                                            <div class="col-lg-7">
                                                                <select  class="form-control chosen-select choice choices" name="choice[]" data-placeholder="Select dish choice" >
                                                                    <option value="">Select Choices</option>
                                                                    <?php foreach ($choiceList as $key => $value) {?>
                                                                        <option value="<?php echo $value->choice_id; ?>" <?php echo ($value->choice_id == $value1->choice_id)?"selected":""; ?>><?php echo $value->choice_name; ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            </div>
                                                            <div class="col-lg-3">
                                                                <input type="text" class="form-control name" name="choice_price[]" value="<?php echo $value1->ch_price; ?>">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <button class="btn btn-primary add_choice" type="button" >+</button>
                                                                <button class="btn btn-primary remove_choice" type="button" style="<?php echo ($key1 == 0)?'display: none;':""; ?>"  >-</button>
                                                            </div>
                                                        </div>
                                                <?php } }else{ ?>
                                                        <div class="dish_choices" >
                                                            <div class="col-lg-7">
                                                                <select  class="form-control chosen-select choice choices" name="choice[]" data-placeholder="Select dish choice" >
                                                                    <option value="">Select Choices</option>
                                                                    <?php foreach ($choiceList as $key => $value) {?>
                                                                        <option value="<?php echo $value->choice_id; ?>"><?php echo $value->choice_name; ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            </div>
                                                            <div class="col-lg-3">
                                                                <input type="text" class="form-control name" name="choice_price[]" >
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <button class="btn btn-primary add_choice" type="button" >+</button>
                                                                <button class="btn btn-primary remove_choice" type="button" style="display: none;" >-</button>
                                                            </div>
                                                        </div>
                                                <?php } ?>
                                                <div class="choice_append"></div> 
                                                <span class="col-sm-12 error"></span>
                                            </div>
                                        </div>
                                        
                                                
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12"> 
                                        <div class="form-group">
                                             <input type="submit" class="btn btn-success" name="add" value="Save">

                                              <input type="submit" class="btn btn-success" name="update" value="Update">

                                            <a href="<?php echo site_url('Restaurants/restaurantDishes/'.$res_id); ?>" type="button" class="btn btn-success ">Cancel</a>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script type="text/javascript">

    setTimeout(function () { 
        $("#success_notification").hide();
        $("#error_notification").hide();
     },3000,'slow');
    $(document).ready(function(){ $(".chosen-select").chosen();});
    $(document).on("click",".add_choice",function(){
        var parent  = $(this).parent().parent();
        var choice  =$(".choice:last").val();
        var choices =$(".choice");
        var ch  =[];
        $.each(choices,function(key,val){
            ch.push($(val).val());
        });
        ch.pop();
        var price  =$(".name:last").val();
        
         if(choice ==''){
            $(".error").text("Dish choice is required.");

         }else if(jQuery.inArray(choice,ch) != -1){

            $(".error").text("Dish choice must be unique.");

         }else if(price !="" && !$.isNumeric(price)){
            $(".error").text("Dish choice price must be numeric.");
        }
        else{

            $('.choise_div').find(".error").text("");
            var append=$('.choise_div').find(".choice_append");
            $('.choise_div').find(".dish_choices:first").clone().appendTo(append);
            $(".chosen-select:last").chosen({width: "100%"});
            $(".chosen-container:last").remove();
            $('.choise_div').find(".name:last").val("");
            $('.choise_div').find(".choices:last").val("").trigger("chosen:updated");

            $('.choise_div').find( ".choice_append .remove_choice:last" ).show();
        
         }

    })

    $(document).on("click",".remove_choice",function(){   
         $(this).closest('.dish_choices').remove();
     });


    $("input[name=add]").click(function(e){
        var price =$("form").find(".price:last").val();

        if(price !="" && !$.isNumeric(price)){
             $("form").find(".dishPriceError:last").text("Dish price must be numeric.");
              e.preventDefault();
        }
       
    });
</script>

