<style type="text/css">
	#removeaddress .modal-body,#removeaddress #myModalLabel{text-align: center; color: #666;}
	#removeaddress .btn-success{    background-color: #00AE4A;}
</style>
<div class="sub_page_main">
	<div class="profile_page">
		<div class="container">
			<div class="page_title">
				<h2><?php  echo $this->lang->line('message_MyProfile'); ?></h2>
			</div>
		</div>
		<div class="alert alert-success alert-dismissible" id="success_notification" style="display:none;margin:10px 70px;">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i></h4>
            <p id="success_message"></p>
        </div>

    	<div class="alert alert-danger alert-dismissible" id="error_notification" style="display:none;margin:10px 70px;">
		    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	        <h4><i class="icon fa fa-warning"></i><?php  echo $this->lang->line('message_Failed'); ?></h4>
	        <p id="error_message"></p>
	    </div>
		<div class="profile_page_btm">
			<div id="profiletab">
				<div class="container">
					<ul class="resp-tabs-list">
						<li><?php  echo $this->lang->line('message_General'); ?></li>
						<li id="li_addAddress"><?php  echo $this->lang->line('message_Addresses'); ?></li>
						<!-- <li>Payment</li> -->
					</ul>
				</div>
				<div class="resp-tabs-container">
					<div>
						<div class="container">
							<div class="general_pro">
								<ul>
									<li>
										<div class="pro_left">
											<p><?php  echo $this->lang->line('message_Photo'); ?></p>
										</div>
										<div class="uploaded_file" style="display:none;text-align: center;" ></div>

										<div class="pro_right">
											<?php 
													 if($userdata[0]->profile_photo!='')
													 { 
													 	if(substr($userdata[0]->profile_photo, 0,4 ) === "http")
													 	{
													 		$profile_photos = $userdata[0]->profile_photo;
													 	}
													 	else{
													 		$profile_photos = base_url('assets/uploads/users/customers/'.$userdata[0]->profile_photo);
													 	}
													 }
													 else{
													 	$profile_photos = base_url('assets/uploads/users/customers/user.png');
													 }
												?>
											<p class="txt_box "><img src="<?php echo $profile_photos;?>" alt=""></p>
											<div class="fileUpload txt_box_edit" style="display:none;">
										      	
												<p class="txt_box_edit "><img id="selectedIMG" src="<?php echo $profile_photos ?>" alt=""></p>

                                					<span class="upload_btn"><?php  echo $this->lang->line('message_UploadPhoto'); ?></span>
                                					<input type="file" class="upload" name="upload" onchange="$('.uploadImage').attr('event','update'); uploadphoto(this)" id="upload"/>

                            				</div>

										</div>
										<div class="pro_edit">
											<a href="javascript:void(0)" event="edit" success="1" class="edit_pro uploadImage"><?php  echo $this->lang->line('message_Edit'); ?></a>
										</div>
										
									</li>
									<li>
										<div class="pro_left">
											<p><?php  echo $this->lang->line('message_Name'); ?></p>
										</div>
										<div class="pro_right">
											<p class="txt_box"><?php echo $userdata[0]->first_name.' '.$userdata[0]->last_name; ?></p>
											<p class="txt_box_edit" style="display:none;">
												<input type="text" name="fname" id="editfname" value="<?php echo $userdata[0]->first_name; ?>"> <input type="text" name="lname" id="editlname" value="<?php echo $userdata[0]->last_name; ?>"></p>
										</div>
										<div class="pro_edit">
											<a href="javascript:void(0)" success="1" updatefield="name" class="updatepro edit_pro"><?php  echo $this->lang->line('message_Edit'); ?></a>
										</div>
									</li>
									<li>
										<div class="pro_left">
											<p><?php  echo $this->lang->line('message_Email'); ?></p>
										</div>
										<div class="pro_right">
											<p class="txt_box"><?php echo $userdata[0]->email?></p>
											<p class="txt_box_edit" style="display:none;"><input type="text" name="editemail" id="editemail" value="<?php echo $userdata[0]->email?>"></p>
										</div>
										<div class="pro_edit">
											<a href="javascript:void(0)" success="1" updatefield="email" class="edit_pro updatepro"><?php  echo $this->lang->line('message_Edit'); ?></a>
										</div>
									</li>
									<li>
										<div class="pro_left">
											<p><?php  echo $this->lang->line('message_Phone'); ?></p>
										</div>
										<div class="pro_right">
											<p class="txt_box"><?php echo $userdata[0]->country_code.' '.$userdata[0]->contact_no; ?></p>
											<p class="txt_box_edit" style="display:none;"><?php echo $userdata[0]->country_code ?><input type="text" name="editmobile" id="editmobile" maxlength="8" value="<?php echo $userdata[0]->contact_no; ?>"></p>
										</div>
										<div class="pro_edit">
											<a href="javascript:void(0)" success="1" updatefield="mobile" class="edit_pro updatepro"><?php  echo $this->lang->line('message_Edit'); ?></a>
										</div>
									</li>
								</ul>
								<div class="pass_change">
									<div class="pass_change_title">
										<h3><?php  echo $this->lang->line('message_Password'); ?></h3>
									</div>
									<ul>
										<li>
											<div class="pass_change_left">
												<p><?php  echo $this->lang->line('message_Password'); ?></p>
											</div>
											<div class="pass_change_right">
												<a href="#change_pass" aria-label="open"><?php  echo $this->lang->line('message_ChangePassword'); ?></a>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div>
						<div class="container">
							<div class="address_pro">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div class="address_box add_address">
											<a href="#address" aria-label="open" onclick="$('#address_id').val('');document.getElementById('addressadd').reset(); $('#addaddFromPro').val('y');initMap('29.3518587','47.9836915')">
												<div class="align_center">
													<span><i class="fa fa-plus" aria-hidden="true"></i></span>
												<?php  echo $this->lang->line('message_AddAddress'); ?>
												</div>
											</a>
										</div>
									</div>
									<?php foreach ($addressDetail as $key => $add) { ?>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 custDilAddress<?php echo $add->address_id;  ?>">
											<div class="address_box">
												<?php
													if($add->address_type ==1){
														$addType =$this->lang->line('message_Home');
													}else if($add->address_type ==2){
														$addType =$this->lang->line('message_Office');
													}else{
														$addType =$this->lang->line('message_Other');
													}
													$addType .=" ".$this->lang->line('message_Addres');
												?>
												<div class="address_box_mid">
													<p><span><?php echo $add->customer_name; ?></span></p>
													<p><b><?php echo $addType; ?></b>: <?php echo $add->address1."(".$add->name.")"; ?></p>
													<p><b><?php echo $this->lang->line('message_Email'); ?></b>: <?php echo $add->email; ?></p>
													<p><b><?php echo $this->lang->line('message_Phone'); ?></b>:  (+965) <?php echo $add->contact_no; ?></p>
												</div>
												<div class="address_box_btm">
													
													<p><a  href="#address" aria-label="open" onclick="editAddressData(<?php echo $add->address_id; ?>)"><?php  echo $this->lang->line('message_Edit'); ?></a> | <a data-toggle="modal" data-target="#removeaddress"  aria-label="open"  class="deleteAddress" onclick="deleteAddressData(<?php echo $add->address_id; ?>)"><?php  echo $this->lang->line('message_Delete'); ?></a> </p>  
												</div>
											</div>
										</div>	
									 <?php } ?>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>

<div id="change_pass" class="yourModalClass change_pass" role="dialog" aria-labelledby="yourModalHeading" style="display:none;">
    <div class="change_pass_popup">
    	<h2><?php  echo $this->lang->line('message_ChangePassword'); ?></h2>
    	<div class="change_pass_detail">

    		<div class="change_pass_detail_form">
    			<div class="alert alert-danger alert-dismissible" id="pswderror_notification" style="display:none;">
	                <button aria-hidden="true" data-dismiss="alert" class="close closealert" type="button">×</button>
	                <h4><i class="icon fa fa-warning"></i><?php  echo $this->lang->line('message_Failed'); ?></h4>
	                <p id="pswderror_message"></p>
	          	</div>

	          	<div class="alert alert-success alert-dismissible" id="pswdsuccess_notification" style="display:none;">
		            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		            <h4><i class="icon fa fa-check"></i><?php  echo $this->lang->line('message_Success'); ?></h4>
		            <p id="pswdsuccess_message"></p>
		        </div>
    			<div class="form_fild_deatil">
    				<input type="password" name="old_password" id="old_password" placeholder="<?php  echo $this->lang->line('message_OldPassword'); ?>">
    			</div>
    			<div class="form_fild_deatil">
    				<input type="password" name="new_password" id="new_password" placeholder="<?php  echo $this->lang->line('message_NewPassword'); ?>">
    			</div>
    			<div class="form_fild_deatil">
    				<input type="password" name="cfn_password" id="cfn_password" placeholder="<?php  echo $this->lang->line('message_ConfirmPassword'); ?>">
    			</div>
    			<div class="change_pass_btn">
            		<button  id="updatepswd" onclick="updatepswd()"><?php  echo $this->lang->line('message_UpdatePassword'); ?></button>
            	</div>
    		</div>
    	</div>
    	<div class="popup_close">
            <a href="#change_pass" aria-label="close"><img src="<?php echo base_url('assets/images/front-end/icon/ic_cancel.svg') ?>"></a>
        </div>
	</div>
	<div id="loading-div-background" style="display:none">
        <div id="loading-div" class="ui-corner-all">
            <img src="<?php echo base_url('assets/images/front-end/loading1.gif'); ?>" alt="Loading..">
        </div>
    </div>
</div>
<div class="modal fade" id="removeaddress" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php  echo $this->lang->line('message_confirmation'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <p id="deleteMsg1"><h5><?php  echo $this->lang->line('message_removeAddress'); ?></h5></p>
                <span style="color: #ff0000 !important; " class="error_reason"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="remove_address"><?php  echo $this->lang->line('message_yes'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php  echo $this->lang->line('message_no'); ?></button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	var updateurl                = "<?php echo base_url('Home/updateusername'); ?>";
	var checkEmailExist          = "<?php echo base_url('Home/checkEmailExist'); ?>";
	var chngpswdurl              = "<?php echo base_url('Webservice_Customers/changePassword'); ?>";
	var addDelAddress            = "<?php echo base_url('Webservice_Customers/addDeliveryAddress'); ?>";;
	var citystate                = "<?php echo base_url('Webservice_Customers/getCityState'); ?>";
	var deleteCustomerAddress    = "<?php echo base_url("Home/deleteCustomerAddress"); ?>";
	
	var uploadCustomerPhoto      = "<?php echo site_url("Home/uploadCustomerPhoto"); ?>";

	
	var PasswordMatch            = "<?php  echo $this->lang->line('message_PasswordMatch'); ?>";
	var PleaseDiffPassword       = "<?php  echo $this->lang->line('message_PleaseDiffPassword'); ?>";
	var Edit                     = "<?php  echo $this->lang->line('message_Edit'); ?>";
	var Done                     = "<?php  echo $this->lang->line('message_Done'); ?>";
 	
   	var lat="29.3518587";
    var lon="47.9836915";
    initMap(lat,lon);
    
    function initMap(lat = 29.3518587, lon = 47.9836915) {
    var uluru = new google.maps.LatLng(lat, lon);
    var myOptions;
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 16,
        gestureHandling: 'greedy',
        center: uluru,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    var marker = new google.maps.Marker({
        draggable: true,
        scrollwheel: true,
        position: uluru,
        map: map,
        title: "Your location"
    });
    google.maps.event.addListener(marker, 'dragend', function(event) {
        infoWindow.open(map, marker);
    });
}
</script>
<script src="<?php echo base_url('assets/js/front-end/profile.js'); ?>"></script> 