<?php 
$js = <<<JS

    $("#mainTable").Custom({
    Columns:[
            {"data":'expenseId'},
            {"data":'expenseDate'},
            {"data":'comment',},
            {"data":'clientId'},
            {"data":'expType'},
            {"data":'inCash'},
            {"data":'terminal'},
            {"data":'transfer'},
            {"data":'expSum'},
             
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

        ],
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
<div class="col">
                    
                </div>
                <a href="/sold/expense/step1" class="btn btn-info">Оформить заказ</a>
            </div>
            <br/>
            <br/>
            
            
                <div class="col">
                    <table class="table" id="mainTable">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Дата реализации</th>
                          <th scope="col">Примечание</th>
                          <th scope="col">КлиентИД</th>
                          <th scope="col">Тип</th>
                          <th scope="col">Наличные</th>
                          <th scope="col">Терминал</th>
                          <th scope="col">Перечисление</th>
                          <th scope="col">Сумма</th>
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
                        </tr>
                       
                      
                      </tbody>
                      
                    </table>
                   
                </div>
