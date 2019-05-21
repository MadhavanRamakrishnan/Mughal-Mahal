<?php 
//echo "<pre>";
//print_r($userInfo);?>

<div class="warper container-fluid">
   <div class="row">
        <div class="col-md-12">
            <div class="page-header f-left">
                <h1>User Profile</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo isset($userInfo[0]->role_name)?$userInfo[0]->role_name:"Admin";?></div>
                <div class="panel-body">
                    <div class="row" style="padding-top:30px; padding-bottom:30px;">
                        <div class="col-lg-5">
                            <div align="center">

                          <?php 
                              if($userInfo[0]->photo != ""){
                                  if($userInfo[0]->register_via == "0"){
                                      $photos = base_url()."assets/uploads/users/".$userInfo[0]->photo;
                                  }
                                  else{
                                      $photos = $userInfo[0]->photo;
                                  }
                              }
                              else{
                                $photos = base_url()."assets/images/userPhoto/not_img.png";
                              }
                          ?>

                                <img src="<?php echo $photos ?>" class="img-rounded img-responsive media-object1"></img>
                         
                            </div>
                      <br>
                            <div align="center" style="color:#000" >
                                <h2 ><?php echo isset($userInfo[0]->user_name)?$userInfo[0]->user_name:"" ;?></h2>
                            </div>
                        </div>
                    <div class="col-lg-7">
                        <table class="table table-striped no-margn">
                            <tbody>
                              <tr>
                                <td class="col-sm-3 lable_color">
                                  <label >Email</label>
                                  </td>
                                <td class="col-sm-9 ">
                                  <label ><?php echo isset($userInfo[0]->email)?$userInfo[0]->email:""; ?></label>
                                  </td>
                              </tr>

                              <tr>
                                <td class="col-sm-3 lable_color ">
                                  <label >Contact No.</label>
                                </td>
                                <td class="col-sm-9 ">
                                  <label ><?php echo isset($userInfo[0]->contact_no)?$userInfo[0]->contact_no:""; ?></label>
                                </td>
                              </tr>

                              <tr>
                                <td class="col-sm-3 lable_color">
                                  <label >Date of Birth</label>
                                  </td>
                                <td class="col-sm-9 ">
                                  <label ><?php 
                                        if($userInfo[0]->dob != 0){
                                          $date=date_create($userInfo[0]->dob);
                                          $dob=date_format($date,'d M Y');
                                        }else{
                                          $dob="";
                                        }
                                        echo $dob; ?>
                                  </label>
                                </td>
                              </tr>

                              <tr>
                                <td class="col-sm-3 lable_color">
                                 <label>Gender</label>
                                  </td>
                                <td class="col-sm-9">
                                 <label >
                                <?php 
                                    if($userInfo[0]->gender==1){
                                      echo "Male";
                                    }else if($userInfo[0]->gender==2){
                                      echo "Female";
                                    }else{
                                      echo "";
                                    }


                                  ?></label>
                                </td>
                              </tr>
                              <?php
                                if($userInfo[0]->register_via==1){?>
                              <tr>
                                <td class="col-sm-3 lable_color">
                                  <label >Facebook Id</label>
                                  </td>
                                <td>
                                  <label ><?php echo isset($userInfo[0]->facebook_id)?$userInfo[0]->facebook_id:"" ?></label>
                                </td>
                              </tr>
                              <?php }if($userInfo[0]->register_via==3){?>
                                  
                              <tr>
                                <td class="col-sm-9 ">
                                  <label >Google Id</label>
                                  </td>
                                <td>
                                  <label ><?php echo isset($userInfo[0]->google_id)?$userInfo[0]->google_id:""; } ?></label>
                                </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                        
                        <!-- <div class="col-lg-7 ">
                        <p>
                          <label class="col-sm-3 lable_color">Email</label>
                          <label class="col-sm-9 "><?php echo isset($userInfo[0]->email)?$userInfo[0]->email:""; ?></label>
                        </p>
                     
                        <p class="top-padding">
                          <label class="col-sm-3 lable_color">Contact No.</label>
                          <label class="col-sm-9 "><?php echo isset($userInfo[0]->contact_no)?$userInfo[0]->contact_no:""; ?></label>
                        </p>
                        <p class="top-padding">
                          <label class="col-sm-3 lable_color">Date Of Birth</label>
                          <label class="col-sm-9"><?php 
                                if(isset($userInfo[0]->dob)){
                                  $date=date_create($userInfo[0]->dob);
                                  $dob=date_format($date,'d M Y');
                                }
                                echo $dob; ?>
                          </label>
                        </p>
                        <p class="top-padding">
                          <label class="col-sm-3 lable_color">Gender</label>
                          <label class="col-sm-9 "><?php echo ($userInfo[0]->gender==1)?"Male":"Female" ?></label>
                        </p>
                        <?php
                         if($userInfo[0]->register_via==1){?>
                         <p class="top-padding">
                            <label class="col-sm-3 lable_color">Facebook Id</label>
                            <label class="col-sm-9 "><?php echo isset($userInfo[0]->facebook_id)?$userInfo[0]->facebook_id:"" ?></label>
                          </p>
                         <?php }if($userInfo[0]->register_via==3){?>
                          <p class="top-padding">
                            <label class="col-sm-3 lable_color">Google Id</label>
                            <label class="col-sm-9 "><?php echo isset($userInfo[0]->google_id)?$userInfo[0]->google_id:"" ?></label>
                          </p>
                         <?php }?>
                         
                         <p class="top-padding">
                          <label class="col-sm-3 lable_color">Created Date</label>
                          <label class="col-sm-9 "><?php 
                                if(isset($userInfo[0]->created_date)){
                                  $date=date_create($userInfo[0]->created_date);
                                  $created_date=date_format($date,'d M Y');
                                }
                                echo $created_date; ?>
                          </label>
                        </p>
                                
                        </div> -->
                        
                   
                </div>
            </div>
        </div>
    </div>
</div>