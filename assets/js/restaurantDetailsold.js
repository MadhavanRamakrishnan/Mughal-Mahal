$(document).ready(function() {
    var table = $('#basic-datatable').DataTable();
     
    $(document).on('click', '.toggleDish', function () {
        var cat_id =$(this).attr('pid');
        var tr     = $(this).closest('tr');
        var row    = table.row( tr );
 
        if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
             var message ='<table class="table table-striped table-bordered" id="dishTable'+cat_id+'">';
                $.post(catDishUrl,{res_id:res_id,cat_id:cat_id,is_dish:"1"},
                function(response){
                 obj=$.parseJSON(response);
                    
                    if(obj.success==1){
                        message +='<tr><th></th><th style="width: 83px;text-align:center;">#</th><th style="width: 571px;">Dish Name</th><th style="text-align:center;">Dish Price</th></tr>';
                        $.each(obj.message,function(key,val){
                            message +='<tr>';
                            message +='<td></td><td class="togglechoice" did="'+key+'" cat_id="'+cat_id+'" style="width: 83px;text-align:center;font-size: 16px;"><i class="fa fa-play-circle-o"></i></td>';
                            message +='<td style="width: 571px;">'+val.dish_name+'</td>';
                            message +='<td style="text-align:center;">'+val.dish_price+'</td>';
                            message +='</tr>';
                        });

                    }else{
                        message +='<tr><td colspan="4">'+obj.message+'</td></tr>';
                    }
                    message +='</table>';
                    row.child(message).show();
                });
            
            tr.addClass('shown');
            
        }
    } );

    $(document).on('click', 'td.togglechoice', function () {

        var dish_id = $(this).attr('did');
        var cat_id  = $(this).attr('cat_id');
        var eleId   ="#dishTable"+cat_id;
        var table1  =$("#dishTable"+cat_id).DataTable();

        var tr1     = $(this).closest('tr');
        var row1    = table1.row(tr1);
        
        if (row1.child.isShown()) {
            row1.child.hide();
            tr1.removeClass('shown');
        }
        else {
                var choices ='<table  >';
                       // choices +='<tr><th style="width: 83px;text-align:center;">#</th><th style="width: 571px;">Choice Name</th><th style="text-align:center;">Choice Category</th><th style="text-align:center;">Choice Price</th></tr>';
                        
                        choices +='<tr><td>wedwrwerew</td><td>wedwrwerew</td><td>wedwrwerew</td><td>wedwrwerew</td></tr>';
                   
                    choices +='</table>';
                     
                    row1.child(choices).show();
                     tr1.addClass('shown');
                /*$.post(catDishUrl,{res_id:res_id,dish_id:dish_id,is_dish:"0"},
                    function(response){
                    obj = $.parseJSON(response);
                    var choices ='<table class="table table-striped table-bordered" >';

                    if(obj.success == "1"){
                        choices +='<tr><th style="width: 83px;text-align:center;">#</th><th style="width: 571px;">Choice Name</th><th style="text-align:center;">Choice Category</th><th style="text-align:center;">Choice Price</th></tr>';
                        $.each(obj.message,function(key,val){
                            choices +='<tr>';
                            choices +='<td  style="width: 83px;text-align:center;"></td>';
                            choices +='<td style="width: 571px;">'+val.choice_name+'</td>';
                            choices +='<td style="text-align:center;">'+val.choice_category_name+'</td>';
                            choices +='<td style="text-align:center;">'+val.choice_price+'</td>';
                            choices +='</tr>';
                        });

                    }else{
                        choices +='<tr><td colspan="4">'+obj.message+'</td></tr>';
                    }
                    choices +='</table>';

                });*/
            
           
        }
    } );



} );