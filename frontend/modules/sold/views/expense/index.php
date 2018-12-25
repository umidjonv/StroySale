<?php 
$js = <<<JS

    var table = $("#mainTable").Custom({
    Columns:[
            {"data":'expenseId'},
            {"data":'expenseDate'},
            
            {"data":'clientId'},
            {"data":'clientName'},
            {"data":'from'},
            {"data":'comment'},
            {"data":'paidType'},
            {"data":'paidTypeName'},
            {"data":'expSum'},
            {"data":'deliveryType'},
            {"data":'deliveryPrice'},
            {"data":'delivery'},
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="/sold/orders/list/'+source.expenseId+'" class="btn btn-default" id="orderRecord' + source.expenseId + '">Список</a>';
                    }

                },
                "sDefaultContent": '<a href="/sold/orders/list/0" class="btn btn-default" id="orderRecord">Список</a>'
            }, 
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="/sold/expense/nakladnaya/' + source.expenseId + '" class="btn btn-default" name="nakladnayaRecord">Накладная</a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
            },
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="#" class="btn btn-default" name="deleteRecord" id="delRecord' + source.expenseId + '"><span class="oi oi-x"></span></a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
            },
            {"data":'status'},

        ],
        OnCreatedRow:function(row, data, dataIndex, cells){
             if (data.status == 0)
                    $(row).addClass('tableRow');
                else if(data.status==3)
                    $(row).addClass('rowApproved');

        },
        RowClick:function()
        {
            $(this).
        },
        columnDefs: [
            
            {
                "targets": [ 2 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 6 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 15 ],
                "visible": false,
                "searchable": false
            },
            
            
        ],
        order:[[ 0, "desc" ]],
    tableId:"#expenseTable",
    refreshUrl:"/sold/expense/refreshd",
    deleteUrl:"/sold/expense/delete",
    saveUrl:"/sold/expense/save",
    newUrl:"/sold/expense/new"

});

 
JS;
$this->registerJs($js);
$session = Yii::$app->session;
if($session->isActive)
{    unset($session["expenseId"]);}

?>

<h3>Продажи</h3>
<div id="error"></div>
<div class="row">
    <div class="col"></div>
    <div class="col-2"><a href="/sold/expense/step1" class="btn btn-info">Оформить заказ</a></div>
</div>
<div class="row">



            </div>
            <br/>
            <br/>
            
            
                <div class="col">
                    <table class="table" id="mainTable" >
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Дата реализации</th>

                          <th scope="col">КлиентИД</th>
                            <th scope="col">Клиент</th>
                            <th scope="col">ОТ</th>
                            <th scope="col">Примечание</th>
                            <th scope="col">Тип оплаты</th>
                            <th scope="col">Оплата</th>
                            <th scope="col">Сумма</th>
                            <th scope="col">Тип вывоза</th>
                            <th scope="col">Сумма доставки</th>
                            <th scope="col">Описание</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                      </thead>
                      
                      <tbody>
                          
                      
                        <tr class="tableRow">
                          <td scope="row"></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                            <th></th>
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
