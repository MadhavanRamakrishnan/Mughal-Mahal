
$(document).on('click','.updatepro',function()
{
  var clickthis = $(this);
  var updatefor = $(this).attr('updatefield');
 $('#error_message').text(""); 
  var flag    =0;
  var message ="";
  if(clickthis.text()=='Edit'){

    if(updatefor=='name'){
      var fname = $('#editfname').val();
      var lname = $('#editlname').val();
      if(fname ==""){
        flag =1;
        message =FirstNameReq;
      }else if(lname==""){
        flag =1;
        message =LastNameReq;
      }
      else{
        flag    =0;
        data = {first_name:fname,last_name:lname};
      }
    
    }
    else if(updatefor=='email')
    {
      var email = $('#editemail').val();
      data    = {email:email};
      if(email==""){
        flag =1;
        message =EmailReq;
      }else if(!validateEmail(email)){
        flag =1;
        message =ValidEmail;
      }
      else{
        flag    =0;
        data    = {email:email};
      }
      
    }
    else if(updatefor=='mobile')
    {
      var mobile = $('#editmobile').val();
      if(mobile==""){
        flag =1;
        message =PhoneReq;
      }else if(!$.isNumeric(mobile)){
        flag =1;
        message =phoneIsnumeric;
      }else if(mobile.length!=8 ){
        flag =1;
        message =phoneMinlenth;
      }else if(mobile.length>14){
        flag =1;
        message =phoneMaxlenth;
      }else{
        flag    =0;
        data = {contact_no:mobile};
      }
      
    }

    if(flag == 0){

         $.ajax({
                type:'post',
                url:updateurl,
                data:data,
                success:function(data){
                  var obj = JSON.parse(data);
                  if(obj.response=='true')
                  {
                    $('#success_notification').fadeIn("slow");
                    $('#success_message').text(obj.message);
                    if(updatefor=='name'){
                      clickthis.parent().parent('li').find('.txt_box').text(fname+' '+lname);
                      clickthis.parent().parent('li').find("input[name='fname']").attr('value',fname);
                      clickthis.parent().parent('li').find("input[name='lname']").attr('value',lname);
                      $(".dropbtn1").find('span').text(fname+' '+lname);
                    }
                    else if(updatefor=='email')
                    {
                      clickthis.parent().parent('li').find('.txt_box').text(email);
                      clickthis.parent().parent('li').find("input[name='editemail']").attr('value',email);
                    }
                    else if(updatefor=='mobile')
                    {
                      clickthis.parent().parent('li').find('.txt_box').text('+965 '+mobile); 
                      clickthis.parent().parent('li').find("input[name='editmobile']").attr('value',mobile); 
                    }
                    $(this).attr('success',1);
                  }
                  else
                  {
                    $(this).attr('success',0);
                    $('#error_notification').fadeIn("slow");
                    $('#error_message').text(obj.message); 
                   
                  }
                
                }
              });
    }else{
      $('#error_notification').fadeIn("slow");
      $('#error_message').text(message); 
      updateFields(clickthis);
    }
   setTimeout(function(){
      $('#success_notification').fadeOut("slow");
      $('#error_notification').fadeOut("slow");
      if($('#error_message').text() !=""){
        updateFields(clickthis);
      }
    },4000);
  }
});

$(".edit_pro").click(function(){
  updateFields($(this),'err');
});
function updatepswd(){
  var oldpswd = $('#old_password').val();
  var newpswd = $('#new_password').val();
  var cfnpswd = $('#cfn_password').val();
  var cookies = getCookie();
  //console.log(x.access_token);
  if(oldpswd==""){
    $('#pswderror_notification').fadeIn("slow");
    $('#pswderror_message').text(AllFieldsReq); 
  }
  else if (newpswd=='') {
    $('#pswderror_notification').fadeIn("slow");
    $('#pswderror_message').text(AllFieldsReq); 
  }
  else if (cfnpswd=='') {
    $('#pswderror_notification').fadeIn("slow");
    $('#pswderror_message').text(AllFieldsReq); 
  }
  else if (cfnpswd!=newpswd) {
    $('#pswderror_notification').fadeIn("slow");
    $('#pswderror_message').text(PasswordMatch); 
  }
  else if (oldpswd==newpswd) {
    $('#pswderror_notification').fadeIn("slow");
    $('#pswderror_message').text(PleaseDiffPassword); 
  }
  else
  {
    $("#loading-div-background").show();
    $.ajax({
      type:'post',
      url:chngpswdurl,
      data:{user_id:cookies.user_id,access_token:cookies.access_token,old_password:oldpswd,new_password:newpswd,default_language:'en'},
      success:function(data){
        var obj = JSON.parse(data);
        if(obj.response=='false')
        {
          $("#loading-div-background").hide();
          $('#pswderror_notification').fadeIn("slow");
          $('#pswderror_message').text(obj.message); 
        }
        else
        {
          $("#loading-div-background").hide();
          $('#pswdsuccess_notification').fadeIn("slow");
          $('#pswdsuccess_message').text(obj.message); 
          setTimeout(function(){
            location.reload();
          },3000);
        }
      }
    });
  }
  setTimeout(function(){
    $('#pswderror_notification').fadeOut("slow");
  }, 3000);
}



function uploadphoto(fromdata)
{
  
  files = event.target.files;
  var reader = new FileReader();

  reader.onload = function (e) {
     $('#selectedIMG').attr('src',event.target.result);
  }
   reader.readAsDataURL(fromdata.files[0]);

}


$(".uploadImage").click(function(){
    var event =$(this).attr("event");
    if(event == "update"){

          var data = new FormData();
           for (var i = 0; i < files.length; i++) {
              var file = files[i];
              data.append('uploadfile',file, file.name);
          }
          var xhr = new XMLHttpRequest();
          xhr.open('POST',uploadCustomerPhoto, true);
          xhr.send(data);
          xhr.onload = function () {
            var obj =$.parseJSON(xhr.response);
            if(obj.response=='0')
            {
              
              $("#loading-div-background").hide();
              $('#pswderror_notification').fadeIn("slow");
              $('#pswderror_message').text(obj.message); 
            }
            else
            {
              $(".uploaded_file").hide  ();
              $(this).attr("event","edit")
              $("#loading-div-background").hide();
              $('#pswdsuccess_notification').fadeIn("slow");
              $('#pswdsuccess_message').text(obj.message); 
              setTimeout(function(){
                location.reload();
              },100);
            }   
          };
    }
});

function updateFields(Ele,err=""){

  var thisELE = Ele.parent().parent('li');
    
    if(Ele.text()==Edit)
    {
      if(err !=""){
        var textEle =$('.txt_box_edit').find("input[type='text']");
        $.each(textEle,function(k,v){
          $(v).prop('value',$(v).attr('value'));
        });  
      }
      
      thisELE.find('.txt_box').hide();
      thisELE.find('.txt_box_edit').show();
      Ele.text(Done);
    }
    else
    {
      if(Ele.attr('success') == "1"){
        thisELE.find('.txt_box_edit').hide();
        thisELE.find('.txt_box_edit').hide();
        thisELE.find('.txt_box').show();
        Ele.text(Edit); 
      }else{
        thisELE.find('.txt_box').hide();
        thisELE.find('.txt_box_edit').show();
        Ele.text(Done);
      }
      
    }

}