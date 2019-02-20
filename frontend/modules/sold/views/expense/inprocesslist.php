<?php 
$js = <<<JS

    var table  = $("#mainTable").Custom({
    Columns:[
            {"data":'expenseId'},
            {"data":'expenseDate'},
            
            {"data":'clientId'},
            {"data":'clientName'},
            {"data":'from'},
            {"data":'dogNum'},
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
                        return '<a href="/sold/orders/list/'+source.expenseId+'" id="orderRecord' + source.expenseId + '" class="btn btn-info btn-sm"><span class="oi oi-list"></span> изменить</a>';
                    }

                },
                "sDefaultContent": '<a href="/sold/orders/list/0" class="btn btn-default" id="orderRecord">корректировка</a>',
                
            },
            
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="/sold/expense/existsold/' + source.expenseId + '" name="soldRecord" class="btn btn-info  btn-sm"><span class="oi oi-loop"></span> процесс</a>';
                    }

                },
                "sDefaultContent": '<a href="/sold/expense/existsold/0" class="btn btn-default" id="soldRecord">Список</a>',
                
            },
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="/sold/expense/solded/'+source.expenseId +'" name="statusRecord" class="btn btn-warning btn-sm" id="stRecord' + source.expenseId + '">закрыть</a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-warning" name="statusRecord"><span class="oi oi-x"></span></a>'
            },
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="javascript:;" class="btn btn-danger btn-sm" name="deleteRecord" id="delRecord' + source.expenseId + '"><span class="oi oi-x"></span></a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
            },

        ],
        columnDefs: [
            
            {
                "targets": [ 2 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 7 ],
                "visible": false,
                "searchable": false
            },
            
            
            
        ],
        
        order:[[ 0, "desc" ]],
        tableId:"#expenseTable",
        refreshUrl:"/sold/expense/refreshdlist",
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

<h3>Продажи (не закрытые)</h3>
<div id="error"></div>       
<div class="row">
<div class="col">
                    
                </div>
                <a href="/sold/expense/step1" class="btn btn-info">Оформить заказ</a>
            </div>
            <br/>
            <br/>
            
            
                <div class="col">
                    <table class="table" id="mainTable" style="font-size: 14px;">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Дата реализации</th>

                          <th scope="col">КлиентИД</th>
                            <th scope="col">Клиент</th>
                            <th scope="col">ОТ</th>
                            <th scope="col">№ дог.</th>
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
                          <td></td>
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
