
<?php
$js = <<<JS

    $("#mainTable").Custom({
    Columns:[
            {"data":'expenseId'},
            {"data":'deliveryType'},
            {"data":'name'},
            {"data":'bank'},
            {"data":'description'},
            {"data":'price'},
            
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
    tableId:"#delivery",
    refreshUrl:"/sold/delivery/refreshd",
    deleteUrl:"/sold/delivery/delete",
    saveUrl:"/sold/delivery/save",
    newUrl:"/sold/delivery/new"

});
       
    
        
JS;
 
$this->registerJs($js);
?>

<h3>Клиенты</h3>
<div id="error"></div>            


            
                <div class="col">
                    <table class="table" id="mainTable">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col"># продажи</th>
                          <th scope="col">Тип</th>
                          <th scope="col">Имя</th>
                          <th scope="col">Описание</th>
                          <th scope="col">Цена</th>
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
                        </tr>
                       
                          
                      </tbody>
                      
                    </table>
                   
                </div>
            
 
