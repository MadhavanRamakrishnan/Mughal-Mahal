
  var linkName    = link_name;
  var newLinkName = link_name;
  var disLinkName = link_name;
  var lastOrderId = '';
  
  $(document).ready(function(){
      $('#date,#date1,#new_date,#new_date1,#dis_date,#dis_date1').datepicker({
          autoclose: true,
          changeYear: true,
          endDate: '+0d',
          todayHighlight:true,
      });
      $('#new_date').datepicker().on('changeDate', function (e) {
            var minDate = new Date(e.date.valueOf());
            console.log(minDate);
            $('#new_date1').datepicker('setStartDate', minDate);
      });

      $('#date').datepicker().on('changeDate', function (e) {
            var minDate = new Date(e.date.valueOf());
            console.log(minDate);
            $('#date1').datepicker('setStartDate', minDate);
      });
       $('#dis_date').datepicker().on('changeDate', function (e) {
            var minDate = new Date(e.date.valueOf());
            console.log(minDate);
            $('#dis_date1').datepicker('setStartDate', minDate);
      });
   
        
      $("#tapButton").click();
  });
  (function(d){
        var badge = document.getElementById('newOrders');  
        var badgeNum = document.createElement('div'); 
        var int = window.setInterval(function(){
            var i =$('.new_orders').attr('total');
            badgeNum.innerText = i;
            badgeNum.setAttribute('class','badge-num');
            var insertedElement = badge.insertBefore(badgeNum,badge.firstChild);    
        },1000);
    })(document);
    (function(d){
        var badge = document.getElementById('newDisputed');  
        var badgeNum = document.createElement('div'); 
        var int = window.setInterval(function(){
        var i =$('.disputed_order').attr('total');
            badgeNum.innerText = i;
            badgeNum.setAttribute('class','badge-num');
            var insertedElement = badge.insertBefore(badgeNum,badge.firstChild);    
        },1000);
    })(document);
    

  $(document).ready(function(){
    setPagiLinkDisable();
  })
  $(document).on('click','.new_link',function(){
      newLinkName=$(this).attr('href');
     getOrderData(newLinkName,getSearchData('new'));
  });
  $(document).on('click','.punched_link',function(){
      linkName=$(this).attr('href');
      getOrderData(linkName,getSearchData('all'));
  });
  $(document).on('click','.disputed_link',function(){
      disLinkName=$(this).attr('href');
      getOrderData(disLinkName,getSearchData('dis'));
  });

  function setPagiLinkDisable(){
      var nlink =$('#datatable-buttons_paginate').find('.paginate_button').find('a');
      var plink  =$('.punched_ord_pagi').find('.paginate_button').find('a');
      var dlink  =$('.dispued_ord_pagi').find('.paginate_button').find('a');
     $.each(nlink,function(key,val){
        $(val).attr('onclick','return false;');
        $(val).addClass('new_link');
     });
     $.each(plink,function(key,val){
        $(val).attr('onclick','return false;');
        $(val).addClass('punched_link');
     });
     $.each(dlink,function(key,val){
        $(val).attr('onclick','return false;');
        $(val).addClass('disputed_link');
     });
    
  }

  function getOrderData(link,datas)
  {
    
    $.ajax({
        type:"POST",
        url:link,
        data:datas,
        success:function(response){
            var obj =$.parseJSON(response);
            var html="";
            var links ="";
            var link ="<li class='paginate_button previous' aria-controls='datatable-buttons' tabindex='0' id='datatable-buttons_previous'>";
            if(obj.Orders.length>0){
                html +=showOrderData(obj.Orders,obj.offset);
            }else{
               html +="<tr><td colspan='10' class='orderNotFound'>";
               html +="<h3  class='label label-success '>Orders not found.</h3>";
               html +="</td></tr>";
            }

            //set links for pagination
            $.each(obj.links,function(k,v){
                links +=link+v+"</li>";
            });

            if(datas.status =="1")
            {
              $('.new_ordrers').find('tbody').html(html);
              $('.new_ord_pagi').html(links);

              if(lastOrderId != ''){
                if(obj.Orders.length > 0){
                  if(lastOrderId != obj.Orders[0].order_id && obj.Orders[0].order_id > lastOrderId){
                    if(/*lastOrderId == obj.Orders[0].order_id && */obj.Orders[0].status_id == "1"){
                      var sound = document.getElementById("notifySound");
                      sound.play();
                    }
                  }
                  lastOrderId = obj.Orders[0].order_id;
                }
              }
              
            }
            else if(datas.status =="8")
            {
              $('.disputed_ordrers').find('tbody').html(html);
              $('.dispued_ord_pagi').html(links);
            }
            else
            {
              $('.punched_ordrers').find('tbody').html(html);
              $('.punched_ord_pagi').html(links);
            }
            setPagiLinkDisable();
        }
    });
  }
  setInterval(function(){ 
    $.post(getNewOrderCounts,function(data){
      var obj =$.parseJSON(data);
      $(".totalOrders").find('span').text(obj.total);
      $('.new_orders').attr('total',obj.totalNew);
      $('.disputed_order').attr('total',obj.totalDisputed);
    })
  },1000);
  
  

  function getSearchData(type="new")
  {
    if(type =='new')
    {
          var orderId   =$('#new_order_id').val();
          var phone     =$('#new_phone').val();
          var restaurant=$('#new_restaurant').val();
          var date      =$('#new_date').val();
          var date1     =$('#new_date1').val();
          var status    ="1";
    }
    else if(type =='dis')
    {
          var orderId   =$('#dis_order_id').val();
          var phone     =$('#dis_phone').val();
          var restaurant=$('#dis_restaurant').val();
          var date      =$('#dis_date').val();
          var date1     =$('#dis_date1').val();
          var status    ="8";
    }
     else if(type =='all'){
        var orderId   =$('#order_id').val();
        var phone     =$('#phone').val();
        var restaurant=$('#restaurant').val();
        var date      =$('#date').val();
        var date1     =$('#date1').val();
        var status    =$('#status').val();
    }
    return datas     ={order_id:orderId,phone:phone,restaurant:restaurant,date:date,date1:date1,status:status};
  }

function showOrderData(orders,offset)
{
    //set table data
    var html="";
    $.each(orders,function(key,val){
         var oId=Number(val.status_id);
         var nextst =(oId==5)?oId+2:oId+1;
         html +="<tr>";                       
         html +="<td>";                       
         html +=offset;                       
         html +="</td>";                       
         html +="<td>";                       
         html +="<a href='"+ordDetailLink+"/"+Number(val.order_id)+"' >"+val.order_id+"</a></td>";                       
         html +="<td>"+val.name+"</td>";                       
         html +="<td>"+val.contact_no+"</td>";                       
         html +="<td>"+val.restaurant_name+"</td>";
         html +="<td>"+val.area+"</td>";                       
         html +="<td>"+val.time+"</td>";                       
         html +="<td>"+val.paymnet+"</td>";                       
         html +="<td>"+val.amount+"</td>";                       
         html +="<td>";   
        html +=(val.status_id>1)?"<span class='active label "+val.lbl+" '>"+val.status_vla+"</span>":"";
       
        if(nextst == 4){
            html +="&nbsp&nbsp<i class='fa fa-arrow-right'></i>";
            html +="<span class=' label "+val.nextlbl+" changeOrderStatusAndDriver ' oid='"+Number(val.oId)+"' os='"+nextst+"' data-toggle='modal' data-target='#modal-form' data-backdrop='static' data-keyboard='false' style='cursor: pointer;' title='Change Order Status' oid='"+Number(val.order_id)+"'>"+val.nxtstatus+"</span>";
        }else if(val.status_id<7 ){
             var cl =(val.status_id ==1)?'flashit changeOrder':'';
             var lbl =(val.status_id ==1)?val.lbl:val.nextlbl;
             var sts =(val.status_id ==1)?val.status_vla:val.nxtstatus;
             html +=(val.status_id>1)?"&nbsp&nbsp<i class='fa fa-arrow-right'></i>":"";
             html +=" <span class='label "+lbl+" changeOrder "+cl+"' oid='"+Number(val.order_id)+"' os='"+nextst+"' data-toggle='modal' data-target='#cngStatusmodal' data-backdrop='static' data-keyboard='false' style='cursor: pointer;' title='Change Order Status'>"+sts+"</span>";
        }
        html +="</td>";   
        html +="</tr>";
        offset++;
    });
    return html;
}
var pagLink ="";
$(document).on('keyup','#order_id,#phone',function(){
    var pagLink =linkName;
   getOrderData(pagLink,getSearchData('all'));
})
$(document).on('change','#date,#date1,#status,#restaurant',function(){
    var pagLink =linkName;
    getOrderData(pagLink,getSearchData('all'));
})


$(document).on('keyup','#new_order_id,#new_phone',function(){
     newLinkName =link_name;
})
$(document).on('change','#new_date,#new_date1,#new_status,#new_restaurant',function(){
     newLinkName =link_name;
})
$(document).on('keyup','#dis_order_id,#dis_phone',function(){
     disLinkName =link_name;
})
$(document).on('change','#dis_date,#dis_date1,#dis_status,#dis_restaurant',function(){
     disLinkName =link_name;
})

setInterval(function(){
   getOrderData(newLinkName,getSearchData('new'));
   getOrderData(disLinkName,getSearchData('dis'));
   getOrderData(disLinkName,getSearchData('all'));
},1000);