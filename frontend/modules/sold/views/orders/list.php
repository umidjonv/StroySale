<?php

$js = <<<JS

    $("#mainTable").Custom({
    Columns:[
            {"data":'orderId'},
            {"data":'expenseId'},
            {"data":'stuffProdId'},
            {"data":'productName'},
            {"data":'packCount'},
            {"data":'faktCount'},
            {"data":'idType'},
            {"data":'orderSumm'},
            

        ],
        columnDefs: [
            
            {
                "targets": [ 1 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 0 ],
                "visible": true,
                "searchable": false
            },
            {
                "targets": [ 6 ],
                "visible": false,
                "sortable": false
                
        
            },
            
            
        ],
        rowClass:'tableRowOrder',
        RowClick:function(){
            $("tr.tableRowOrder").on("click", function () {
                var tableData = $(this).children("td").map(function() {
                    return $(this).text();
                }).get();
        
                console.log(tableData);
        
                var inputs = $(".mainForm :input");
        
                var values = {};
                var i=0;
                inputs.each(function() {
                    //alert($(this).attr('name')+''+tableData[i]);
                    $(this).val(tableData[i]);
                    i++;
                });
                summ = $('#orderSumm').val();
                kol = $('#packCount').val();
                $('#cena').text(Math.round(summ/kol*100)/100);
                $('#newCena').val(Math.round(summ/kol*100)/100);
                
            });
        },
        
    tableId:"#ordersTable",
    refreshUrl:"/sold/orders/refreshd/"+$expenseId,
    deleteUrl:"/sold/orders/delete",
    saveUrl:"/sold/orders/save",
    newUrl:"/sold/orders/new"

});
$.ajax({
                url: '/clients/refreshd/',
                type: 'POST',
                
                success: function(res){
                    client = $('#clientHidden').val();
                    str = '<option value="0">Прямая продажа</option>';
                    $.each(res['datas'], function(index, val){
                        if(client!=val.clientId)
                        str += '<option value="'+val.clientId+'">'+val.clientName+'</option>';
                        else
                            str += '<option value="'+val.clientId+'" selected>'+val.clientName+'</option>';
                    });
                    $('#clients').html(str);
                    
                    $('#clients').chosen({width: "100%"});
                   
                    //$.emptyValues();
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
$.ajax({                                                                                     
                url: '/sold/delivery/getdrivers',                                                      
                type: 'POST',                                                                   
                                                                                               
                success: function(res){              
                    driver = $('#driverHidden').val();
                    str = '<option value=""></option>';
                    $.each(res, function(index, val){
                        if(val.driver != driver)
                        str += '<option value="'+val.driver+'">'+val.driver+'</option>';
                        else
                            str += '<option value="'+val.driver+'" selected >'+val.driver+'</option>';
                    });
                    $('#driverSelect').html(str);
                    
                    $('#driverSelect').chosen({width: "100%"});
                },                                                                              
                error: function(xhr){                                                           
                    console.log(xhr.responseText);                                              
                }                                                                               
            });

        $('#fakt').on('change', function(){
            
            ostatok = $('#packCount').val() - $('#fakt').val();
            //alert(ostatok);
            $('#ostatok').text(ostatok);
        });
        
        $('#submitData').on('click', function(){
            var dataF2 = $('#form2').serialize();
            var dataF = $('#mainForm').serialize();
            var orderId = $('#orderId').val();
            if(orderId==''||$('#newCena').val()=='')
            {
                orderId = 0;
            }
                $.ajax({                                                                                     
                    url: '/sold/orders/savelist/'+orderId,                                                      
                    type: 'POST',                                                                   
                    data:{'mainform': getFormData($('#mainForm')), 'form2':getFormData($('#form2'))},
                    dataType:'json',                                                                                        
                    success: function(res){
                        //alert(res);
                            if(res.status == 'OK')
                            {
                                $('#mainTable').DataTable().ajax.reload();
                                //$('#mainTable').popover({delay:1,title:'Сохранено'});
                                $('#cena').text($('#newCena').val());
                                
                            }else
                            {
                                $('#expSum').val(res.expSum);
                                alert('Запись сохранена');
                            }
                             $('#expSum').val(res.expSum);
                        console.log(res);
                    },                                                                              
                    error: function(xhr){                                                           
                        alert(xhr.responseText);
                        console.log(xhr.responseText);                                              
                    }                                                                               
                });
                function getFormData(form){
                    var unindexed_array = $(form).serializeArray();
                    var indexed_array = {};
                
                    $.map(unindexed_array, function(n, i){
                        indexed_array[n['name']] = n['value'];
                    });
                
                    return indexed_array;
                }
            //}
             
        });
        
     
JS;

$this->registerJs($js);
?>

<script type="text/javascript">

    </script>


<h3>Список по накладной <?=$expenseId?></h3>
<div id="error"></div>
<div class="row">
    <div class="col border border-success">


        <form id="form2">
            <input name="deliveryDriver" id="driverHidden" type="hidden" value="<?=$exModel->delivery->driver; ?>"/>
            <input name="expenseId" type="hidden" value="<?=$expenseId?>"/>
            <input name="client" id="clientHidden" type="hidden" value="<?=$exModel->clientId; ?>"/>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"  name="expSumLabel" ><b>Сумма продажи:</b></label>
                <div class="col-sm-3">
                    <b><input class="col-sm-4 form-control-plaintext" readonly id="expSum" name="expSum" value="<?=$exModel->expSum; ?>"/></b>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticCenaDostavki" class="col-sm-2 col-form-label">Клиент:</label>
                <div class="col-sm-3">
                    <select class="form-control" name="clientId" id="clients">

                    </select>

                </div>
            </div>
            <div class="form-group row">
                <label for="staticCenaDostavki" class="col-sm-2 col-form-label">Водитель:</label>
                <div class="col-sm-3">
                    <select id="driverSelect" name="driver" class="form-control">

                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticCenaDostavki" class="col-sm-2 col-form-label">Цена доставки:</label>
                <div class="col-sm-3">
                    <input name="deliveryPrice" class="form-control" value="<?=$exModel->delivery->price; ?>"/>
                </div>
            </div>


        </form>


        <form class="mainForm" id="mainForm" action="" method="POST">

            <div class="form-group row">
                <label for="staticId" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-7">
                    <input type="hidden" readonly class="form-control-plaintext" name="orderId" id="orderId" value="">
                    <input type="hidden" readonly class="form-control-plaintext" name="stuffProdId" value="">
                    <input type="text" readonly class="form-control-lg form-control-plaintext"  name="name" value="">
                </div>


            </div>
            <div class="form-group row">
                <label for="staticId" class="col-sm-2 col-form-label">Количество</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control" id="packCount" name="packCount" value="">
                </div>
                <label for="staticOstatok" class="col-sm-1 col-form-label">Остаток</label>
                <div class="col-sm-1">
                    <label id="ostatok" class="col-form-label">0</label>
                </div>


            </div>
            <div class="form-group row">
                <label for="staticId" class="col-sm-2 col-form-label">Фактически</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="fakt" name="fakt" value="">
                </div>
                x
                <label for="staticOstatok" class="col-sm-1 col-form-label">Цена:</label>
                <div class="col-sm-2">
                    <label id="cena" name="cena" class="col-form-label"></label>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticId" class="col-sm-2 col-form-label">Сумма (по одному товару)</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control" id="orderSumm" name="summ" value="">
                </div>


            </div>
            <div class="form-group row">

                <div class="col-sm-6">


                </div>

                <div class="col-sm-2">

                </div>

            </div>
            <hr/>




            <div class="form-group row">
                <label for="staticNewCena" class="col-sm-2 col-form-label">Назначить цену</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="newCena" name="newCena" value="">

                </div>
                <div class="col-sm-2">
                    <a href="javascript:;" class="btn btn-info" id="submitData">Сохранить</a>

                </div>
            </div>
        </form>

    </div>
</div>
    <div class="col">
    <table class="table" id="mainTable">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col">Наименование</th>
            <th scope="col">Количество</th>
            <th scope="col">По факту</th>
            <th scope="col"></th>
            <th scope="col">Сумма</th>

        </tr>
        </thead>

        <tbody>


        <tr class="tableRowOrder">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>


        </tbody>

    </table>

</div>

