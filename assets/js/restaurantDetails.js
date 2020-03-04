$(document).ready(function() {
    
    var table = $('#basic-datatable').DataTable({'columnDefs': [{
         'targets': 0,
         'className': 'dt-body-center'}],
         "deferRender": true,
         "processing": true,
         "pageLength": 100,
         "paging":   false,
        
     });

    $(document).on('click', 'td.toggleDish', function () {
        var cat_id       =$(this).attr('pid');
        var tr           = $(this).closest('tr');
        var row          = table.row( tr );
 
        if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
          
                var dcc = [{
                        title: "#"
                      }, {
                        title: "Dish Name"
                      }, {
                        title: "Dish Price"
                      },{
                        title: "Action"
                      }];

                $.post(catDishUrl,{res_id:res_id,cat_id:cat_id,is_dish:"1",is_best:0},
                function(response){
                    obj=$.parseJSON(response);
                    var dishesDatas =[];
                                
                        if(obj.success==1){
                            
                            $.each(obj.message,function(key,val){
                                if(cat_id =='1')
                                {
                                    dishesData =['<i class="fa fa-play-circle-o" dishId='+key+'></i>',val.dish_name,val.dish_price,'-'];
                                }else{

                                    var showIcon =(val.is_show=="1")?"eye":"eye-slash";
                                    dishesData =['<i class="fa fa-play-circle-o" dishId='+key+'></i>',val.dish_name,val.dish_price,' <a href="'+editDishUrl+'/'+key+'/'+res_id+'" ><i class="fa fa-pencil" title="Edit Dish" ></i></a> |<div id="deleteDish'+key+'" class="deleteDish"  onclick="deleteDish('+key+');" style="width:100%;"><i class="fa fa-trash deleteDish" data-toggle="modal" data-target="#deleteDish" data-backdrop="static" data-keyboard="false" title="Delete Dish" ></i>|<i class="fa fa-'+showIcon+' hideDish'+key+res_id+'" data-toggle="modal" data-target="#hideDish" data-backdrop="static" data-keyboard="false" title="Hide Dish" onclick="hideShowDish('+key+','+res_id+','+val.is_show+')" ></i></div>'];
                                }
                                dishesDatas.push(dishesData);
                            });

                        }
                    
                    var table2 = $('#example2').clone().attr('id', "tableSecondLevel").dataTable( {
                        "sDom": 'ti',
                        scrollY: '70vh',
                        scrollCollapse: true,
                        "columnDefs": [{
                            'targets': 0,
                            'className': 'details-control',
                        }],
                        "deferRender": true,
                        "processing": true,
                        columns: dcc,
                        iDisplayLength: -1,
                        data:dishesDatas,
                    });
        
                    table2.removeClass("childtable_hidden");
                    var child = row.child(table2);
                    var table_td =$(table2).closest('tr td td');
                    table_td.addClass("nestedtable");
                    table2.addClass("secondlevel");
                    child.show();
                

                    //list choices 
                    table2.on({
                          click: function(e){
                                var dish_id =$(this).find('i').attr('dishid');
                                var index = $(this).index();

                                e.stopPropagation();
                                
                                var nestedTable;
                                var tr;
                                var row;
                                
                                // nested table exist
                                var nestedExist = $(this).parent().hasClass('shown');
                                var x=nestedExist.length;
                                if(x){
                                 
                                  $('coucou').DataTable().destroy();
                                  
                                  return;
                                  
                                } else {
                                    // we must create a nested table
                                 
                                    nestedTable = $(this).closest('table.childtable');
                                    tr = $(this).closest('tr');
                                    row = $(nestedTable).DataTable().row(tr);
                                }
                                
                                if (row.child.isShown()) {
                                    // This row is already open - close it
                                    row.child.hide();
                                    tr.removeClass('shown');
                                    
                                } else {
                                    var columnDefs = [{
                                                title: "#"
                                              },
                                              {
                                                title: "Choice Name"
                                              }, {
                                                title: "Choice Category"
                                              }, {
                                                title: "Choice Price"
                                              }];
                                    var disheChoices =[];
                                      $.post(catDishUrl,{res_id:res_id,dish_id:dish_id,is_dish:"0",is_best:0},
                                        function(response){
                                        obj = $.parseJSON(response);
                                        if(obj.success == "1"){
                                            $.each(obj.message,function(key,val){
                                                if(cat_id =='1')
                                                {
                                                    dishesChoice =['',val.choice_name,val.choice_category_name,'<span class="choice_price">'+val.choice_price];
                                                }
                                                else
                                                {
                                                    dishesChoice =['',val.choice_name,val.choice_category_name,'<span class="choice_price">'+val.choice_price+'</span><span class="choice_delete" >&nbsp&nbsp&nbsp&nbsp<i class="fa fa-trash" id="deleteChoice'+val.choice_id+dish_id+'" dishId='+dish_id+' choId='+val.choice_id+' onclick="deleteChoice('+dish_id+','+val.choice_id+')" data-toggle="modal" data-target="#deleteDish" data-backdrop="static" data-keyboard="false" title="Delete Dish choice"></i></span>'];
                                                }
                                                 disheChoices.push(dishesChoice);
                                            });

                                             var table3 = $('#example2').clone().attr('id', "tableThirdLevel").dataTable( {
                                                    "sDom": 'ti',
                                                    "columnDefs": [{
                                                            'targets': 0,
                                                            'className': 'ch_name'}],
                                                    "deferRender": true,
                                                    "processing": true,
                                                     columns: columnDefs,
                                                    data:disheChoices,
                                                    iDisplayLength: -1,
                                                    
                                                });
                                                
                                            table3.attr('id', "coucou");
                                            table3.removeClass("childtable_hidden");
                                                
                                            var child = row.child(table3);
                                            var table_td =$(table3).closest('tr td div');
                                               
                                               
                                            table_td.addClass("nestedtable");
                                            table3.removeClass("secondlevel");
                                            table3.addClass("thirdlevel");
                                            child.show();
                                            tr.addClass('shown');
                                        }

                                    });
                                    var table3 = $('#example2').clone().attr('id', "tableThirdLevel").dataTable( {
                                                    "sDom": 'ti',
                                                    "columnDefs": [{
                                                            'targets': 0,
                                                            'className': 'ch_name'}],
                                                    "deferRender": true,
                                                    "processing": true,
                                                     columns: columnDefs,
                                                    data:disheChoices,
                                                    iDisplayLength: -1,
                                                    
                                                });
                                                
                                    table3.attr('id', "coucou");
                                    table3.removeClass("childtable_hidden");
                                    
                                    var child = row.child(table3);
                                   var table_td =$(table3).closest('tr td');
                                   
                                   
                                   table_td.addClass("nestedtable");
                                   table3.removeClass("secondlevel");
                                   table3.addClass("thirdlevel");
                                   
                                   //console.log(table_td);
                                   child.show();

                                 tr.addClass('shown');
                                }
                                
                                
                        }
                    }, 'td.details-control');


                });
            
            
        }
    } );

   



} );

function deleteDish(dish_id){
    $("#message_text").text('Are you sure to delete this dish?');
    $("#errDeleteDish").text('');
    $("#deleteDishData").unbind().click(function(){
        $.post(deleteDishUrl,{res_id:res_id,dish_id:dish_id},function(response){
            var obj =$.parseJSON(response);
            if(obj.success =='1'){
                $('#deleteDish'+dish_id).parent().parent().remove();
                $("#deleteDish").modal('hide');
            }else{
                $("#errDeleteDish").text(obj.message);
            }
        }); 
    });
    
}

function deleteChoice(dish_id,choice_id){
     $("#message_text").text('Are you sure to delete this dish choice?');
     $("#deleteDishData").unbind().click(function(){
            $.post(deleteChoiceUrl,{res_id:res_id,dish_id:dish_id,choice_id:choice_id},function(response){
            var obj =$.parseJSON(response);
            if(obj.success =='1'){
               $('#deleteChoice'+choice_id+dish_id).parent().parent().parent().remove();
                $("#deleteDish").modal('hide');
            }else{
                $("#errDeleteDish").text(obj.message);
            }
        }); 
    });

}

function hideShowDish(dishId,resId,is_show)
{
     var msg =(is_show=="0")?"Are you sure to show the dish for the restaurant?":"Are you sure to hide the dish for the restaurant?";
    $("#hideResDish_message_text").text(msg);
     $("#hideResDishBtn").unbind().click(function(){
            $.post(hideShowDishUrl,{res_id:resId,dish_id:dishId,is_show,is_show},function(response){
            var obj =$.parseJSON(response);
            if(obj.success =='1'){
                
                var dishClass ="hideDish"+dishId+resId;
                var oldyeClass   =(obj.message.is_show=="0")?"fa-eye":"fa-eye-slash";
                var neweyeClass  =(obj.message.is_show=="1")?"fa-eye":"fa-eye-slash";

                $('.'+dishClass).attr("onclick","hideShowDish("+obj.message.fk_dish_id+","+obj.message.fk_restaurant_id+","+obj.message.is_show+")"  );
                $('.'+dishClass).removeClass(oldyeClass);
                $('.'+dishClass).addClass(neweyeClass);
                $("#hideDish").modal('hide');
            }else{
                 $("#errhideResDish").text(obj.message);
            }
        }); 
    });
}