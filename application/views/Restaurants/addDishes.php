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
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> 
                    <span class="nav-label">Create Restaurants Dishes</span></div>
                    <div class="col-md-12 col-lg-12" style="margin-top:10px;">
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
                    </div>
                    <div class="panel-body">
                        <form method="post"  enctype="multipart/form-data">
                            <div class="addDishes">
                                <div class="row">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <span class="nav-label">Add Dish</span>
                                            <span class="dish_btn">
                                                <button class="btn btn-success add_dish" type="button" >+</button>
                                             <button class="btn btn-success remove_dish" type="button" style="display: none;" >-</button>
                                            </span>
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-lg-10">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-7">
                                                            <label class="control-label">Dishes <i class="reustarred">*</i></label>
                                                           
                                                            <select class="form-control chosen-select chosen dishes" name="dish[]"  data-placeholder="Select dish name">
                                                                <option value="" >Select Dish</option>
                                                                <?php foreach ($dishList as $key => $value) {?>
                                                                      <option value="<?php echo $value->product_id ?>"><?php echo $value->product_en_name; ?></option></optgroup>
                                                                 <?php } ?> 
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3" style="padding-top:10px;">
                                                            <label class="control-label">Dish Price</label>
                                                            <span class="col-sm-12 dishPriceError"><?php echo $error; ?></span>
                                                            <input type="text" class="form-control price" name="dish_price[]" >
                                                        </div>
                                                        <span class="col-sm-12 dishError"><?php echo $error; ?></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="col-sm-7 control-label" >Dish choices</label>
                                                        <label class="col-lg-3 control-label" >Choice Price</label>
                                                        <div class="dish_choices" >
                                                            <div class="col-lg-7">
                                                                <select  class="form-control chosen-select choice choices" name="choice[0][]" data-placeholder="Select dish choice" >
                                                                    <option value="">Select Choices</option>
                                                                    <?php foreach ($choiceList as $key => $value) {?>
                                                                        <option value="<?php echo $value->choice_id; ?>"><?php echo $value->choice_name; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <input type="text" class="form-control name" name="choice_price[0][]" >
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <button class="btn btn-success add_choice" type="button" >+</button>
                                                                <button class="btn btn-success remove_choice" type="button" style="display: none;" >-</button>
                                                            </div>
                                                        </div>

                                                        <div class="choice_append"></div> 
                                                        <span class="col-sm-12 error"></span>        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="dish_append"></div>
                            <div class="col-lg-12 col-md-12 col-sm-12"> 
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                         <input type="submit" class="btn btn-success" name="add" value="Save">

                                           <a href="<?php echo site_url('Restaurants/restaurantDishes/'.$res_id); ?>" type="button" class="btn btn-success ">Cancel</a>
                                          
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">

    $(document).ready(function(){ $(".chosen-select").chosen();});
    $(document).on("click",".add_choice",function(){
        var parent  = $(this).parent().parent();
        var choice  =parent.parent().find(".choice:last").val();
        var choices =parent.parent().parent().find(".choice");
        var ch  =[];
        $.each(choices,function(key,val){
            ch.push($(val).val());
        });
        ch.pop();
        var price  =parent.parent().find(".name:last").val();
        
         if(choice ==''){
            parent.parent().parent().find(".error").text("Dish choice is required.");

         }else if(jQuery.inArray(choice,ch) != -1){

            parent.parent().parent().find(".error").text("Dish choice must be unique.");

         }else if(price !="" && !$.isNumeric(price)){
             parent.parent().parent().find(".error").text("Dish choice price must be numeric.");
        }
        else{

            parent.parent().parent().find(".error").text("");
            var append=parent.parent().parent().find(".choice_append");
            parent.parent().find(".dish_choices:last").clone().appendTo(append);
            $(".chosen-select:last").chosen({width: "100%"});
            $(".chosen-container:last").remove();
            parent.parent().find(".name:last").val("");
            parent.parent().find( ".choice_append .remove_choice" ).show();
        
         }

    })

    $(document).on("click",".remove_choice",function(){   
         $(this).closest('.dish_choices').remove();
     });

    $(document).on("click",".add_dish",function(){
        $(".error").text("");
         var firstdish =$("form").find(".chosen:last").val();
         var firstprice =$("form").find(".price:last").val();
    
        if(firstdish ==""){
            $("form").find(".dishError:last").text("Dish is required.");
        }else if(firstprice !="" && !$.isNumeric(firstprice)){
            $("form").find(".dishPriceError:last").text("Dish price must be numeric.");
        }
        else{
           
            $("form").find(".dishError:last").text("");
            $("form").find(".dishPriceError:last").text("");
            var dishes = $(".add_dish").length;
            $(".addDishes:first").clone().appendTo("#dish_append");
            $("#dish_append").find(".addDishes:last").find(".form-group").find(".chosen-container").remove();
            $("#dish_append").find(".addDishes:last").find(".form-group").find(".chosen-select").chosen({width: "100%"});
            var data =$("#dish_append").find(".addDishes:last").find('.dish_choices');
            
            $(".price:last").val("");
            $.each(data,function(key,val){
                
                if(key !=0){
                    val.remove();
                }
            });
            $("form").find(".dishError:last").text("");
            $("form").find(".dishPriceError:last").text("");
            $("#dish_append").find(".addDishes:last").find(".choice").attr('name','choice['+dishes+"][]");
            $("#dish_append").find(".addDishes:last").find(".name").attr('name','choice_price['+dishes+"][]");
            $( ".addDishes .remove_dish" ).first().hide();
            $(".addDishes .remove_dish:last").show();
        }  
         
        })

    $(document).on("click",".remove_dish",function(){   
         $(this).closest('.addDishes').remove();
     });

    $("input[name=add]").click(function(e){
        var firstdish =$("form").find(".chosen:last").val();
        var price =$("form").find(".price:last").val();

        if(firstdish ==""){
            $("form").find(".dishError:last").text("Dish is required.");
            e.preventDefault();
        }else if(price !="" && !$.isNumeric(price)){
             $("form").find(".dishPriceError:last").text("Dish price must be numeric.");
              e.preventDefault();
        }
       
    });
</script>

