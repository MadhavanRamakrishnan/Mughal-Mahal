$(document).ready(function() {
    //Horizontal Tab
    $('#parentHorizontalTab').easyResponsiveTabs({
        type: 'default', //Types: default, vertical, accordion
        width: 'auto', //auto or any width like 600px
        height:'auto',
        fit: true, // 100% fit in a container
        tabidentify: 'hor_1', // The tab groups identifier
        activate: function(event) { // Callback function if tab is switched
            var $tab = $(this);
            var $info = $('#nested-tabInfo');
            var $name = $('span', $info);
            $name.text($tab.text());
            $info.show();
        }
    });

    // Child Tab
    $('#ChildVerticalTab_1').easyResponsiveTabs({
        type: 'vertical',
        width: 'auto',
        height:'auto',
        fit: false,
        tabidentify: 'ver_1', // The tab groups identifier
        activetab_bg: '#fff', // background color for active tabs in this group
        inactive_bg: '#F5F5F5', // background color for inactive tabs in this group
        active_border_color: '#c1c1c1', // border color for active tabs heads in this group
        active_content_border_color: '#f9404b' // border color for active tabs contect in this group so that it matches the tab head border
    });
    
});

$(document).ready(function(){
       setTimePicker();
    });

$(".day_tab").find(".removeTime").show();
$(".day_tab").find('.removeTime:first').hide();
$(".day_tab").find('.saveTime').hide();
$(document).on("click",".addTime",function(){
    var day     =$(this).attr('day');
    var findEle =$('.allData'+day);
    var lastFromtime  =findEle.find('.fromTimePicker:last').val();
    var lastToTime    =findEle.find('.toTimePicker:last').val();
    var firstTime     =new Date("November 13, 2013 " + lastFromtime);
    var lastTime      =new Date("November 13, 2013 " + lastToTime);
    
    if(lastFromtime ==""){
        findEle.find('.invalidTime:last').text("From time is required");
    }else if(lastToTime ==""){
       findEle.find('.invalidTime:last').text("To time is required");
    }
    else{
        if(findEle.find('.saveTime:last').attr('id') != ''){
            var appendTo ='.addMoreTimeDiv'+day;
            findEle.find('.invalidTime:last').text("");
            findEle.find('.invalidTime:last').text("");
            findEle.find(".addMoreTime:first").clone().appendTo(appendTo);
            $(appendTo).find('.fromTimePicker:last').val('');
            $(appendTo).find('.toTimePicker:last').val('');
            $(appendTo).find('.fromTimePicker:last').attr('disabled',false);
            $(appendTo).find('.toTimePicker:last').attr('disabled',false);
            findEle.find('.saveTime:last').attr('id','');
            findEle.find('.saveTime:last').show();
            findEle.find('.editTime:last').hide();
            findEle.find('.is_approved:last').hide();
            findEle.find('.removeTime:last').attr('data-target','');
            findEle.find(".removeTime").show();
            findEle.find('.removeTime:first').hide();
            setTimePicker();
        }else{
            findEle.find('.invalidTime:last').text("Please save this time duration then add another.");
        }
        
    }

})

$(document).on("click",".removeTime",function(){   
    var id=$(this).attr('id');
    $("#errDeleteDish").text('');

    $("#deleteTimeData").unbind().click(function(){
        $.post(deleteTime,{id:id},function(response){
            var data =$.parseJSON(response);
            if(data.success =="1"){
                location.reload();
            }else{
                $("#errDeleteDish").text('Somethin went wrong please try again');

            }
         });
     });
     
 });



//edit time
$(document).on("click",".editTime",function(){
    $(this).parent().find('.saveTime').show();
    $(this).hide();
    $(this).parent().parent().parent().find('.fromTimePicker').attr('disabled',false);
    $(this).parent().parent().parent().find('.toTimePicker').attr('disabled',false);
})

$(document).on("click",".saveTime",function(){
    var thisEle =$(this).parent().parent().parent();
    var day     =$(this).attr('day');
    var from    = thisEle.find('.fromTimePicker').val();
    var to      = thisEle.find('.toTimePicker').val();
    var id      =$(this).attr('id');
    var res_id  =$('.day_tab').attr('res_id');
   
    var firstTime     =new Date("November 13, 2013 " + from);
    var lastTime      =new Date("November 13, 2013 " + to);
   
    if(from ==""){

        thisEle.find('.invalidTime').text("From time is required");
        
    }else if(to ==""){
       thisEle.find('.invalidTime').text("To time is required");
    }else if(lastTime.getTime() <= firstTime.getTime()){
        thisEle.find('.invalidTime').text("To time must be grater than From time");
    }
    else{
         $.ajax({
                type:'POST',
                url:checkFromTime,
                data:{from:from,to:to,res_id:res_id,day:day,id:id},
                success:function(response){
                       
                        var obj =$.parseJSON(response);
                        if(obj.success == "1"){
                           
                            $.post(updateTime,{id:id,from_time:from,to_time:to,res_id:res_id,day:day},function(response){
                                var obj =$.parseJSON(response);
                                if(obj.success == "1"){
                                   
                                    if(resId != "")
                                    {
                                        location.reload();
                                    }else
                                    {
                                        var resID  =$('.day_tab').attr('res_id');
                                        window.location.href=resDetails+"/"+resID;
                                    }
                                }else{

                                }
                             });
                        }else{
                            thisEle.find('.invalidTime').text("The given time duration is not valid ,Please check all added time duration and try again.");
                        }
                    }
               });

       
        
    }

})


function setTimePicker(){
  
   $('.toTimePicker,.fromTimePicker').bootstrapMaterialDatePicker({ date: false ,format:'HH:mm'});
}


$(document).on('click','.approveTime',function(){
    var time_id =$(this).attr('time_id');
    $("#errapproveDish").text('');

    $("#approveTimeData").unbind().click(function(){
           $.post(approveTime,{time_id:time_id},function(response){
            
            var obj =$.parseJSON(response);
            if(obj.success == "1"){
                location.reload();
            }else{
                $("#errapproveDish").text('Somethin went wrong please try again');

            }
         });
     });

    
})
