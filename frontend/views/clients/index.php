
<?php
$js = <<<JS

    $("#mainTable").Custom({
    Columns:[
            {"data":'clientId'},
            {"data":'clientName'},
            {"data":'inn'},
            {"data":'bank'},
            {"data":'address'},
            {"data":'ogrn'},
            {"data":'schet'},
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="#" class="btn btn-default" name="deleteRecord" id="delRecord' + source.clientId + '"><span class="oi oi-x"></span></a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
            },

        ],
    tableId:"#clientsTable",
    refreshUrl:"/clients/refreshd",
    deleteUrl:"/clients/delete",
    saveUrl:"/clients/save",
    newUrl:"/clients/new"

});
       
    
        
JS;
 
$this->registerJs($js);
?>

<h3>Клиенты</h3>
<div id="error"></div>            

<div class="row">
                <div class="col">
                    <form class="mainForm" id="mainForm1" action="/clients/new" method="POST">
                        <div class="form-group row">
                        <label for="staticId" class="col-sm-2 col-form-label">ID</label>
                        <div class="col-sm-6">
                          <input type="text" readonly class="form-control-plaintext" name="clientId" id="formID" value="0">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="clientName" class="col-sm-2 col-form-label">Имя клиента</label>
                        <div class="col-sm-6">
                          <input type="text"  class="form-control" name="clientName" id="inputClientName" placeholder="введите имя клиента">
                        </div>
                      </div>
                        <div class="form-group row">
                        <label for="inn" class="col-sm-2 col-form-label">ИНН/КПП</label>
                        <div class="col-sm-6">
                          <input type="text"  class="form-control" name="inn" id="inputInn" placeholder="введите ИНН">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="bank" class="col-sm-2 col-form-label">Банк</label>
                        <div class="col-sm-6">
                          <input type="text"  class="form-control" name="bank" id="inputBank" placeholder="введите имя банка">
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="inputAdress" class="col-sm-2 col-form-label">Адрес</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="address" id="inputAdress" placeholder="введите адрес клиента">
                        </div>
                          
                      </div>
                      <div class="form-group row">
                        <label for="ogrn" class="col-sm-2 col-form-label">ОГРН</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="ogrn" id="inputAdress" placeholder="введите ОГРН">
                        </div>
                          
                      </div>
                      <div class="form-group row">
                        <label for="schet" class="col-sm-2 col-form-label">Расчётный счёт</label>
                        <div class="col-sm-6">
                          <input type="text"  class="form-control" name="schet" id="inputInn" placeholder="введите расчётный счёт">
                        </div>
                        <div class="col-sm-2">
                              <button id="btnSave" type="submit" class="btn btn-primary"><span class="oi oi-check"></span> Сохранить</button>
                              
                        </div>
                          <div class="col-sm-2">
                              <a href="#" id="btnNew" class="btn btn-primary"><span class="oi oi-plus"></span> Новый</a>
                              
                        </div>
                          
                      </div>
                    </form>
                </div>
                
            </div>
            <br/>
            <br/>
            
            
                <div class="col">
                    <table class="table" id="mainTable">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Клиент</th>
                          <th scope="col">ИНН/КПП</th>
                          <th scope="col">Банк</th>
                          <th scope="col">Адрес</th>
                          <th scope="col">ОГРН</th>
                          <th scope="col">Счёт</th>
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
                        </tr>
                       
                          
                      </tbody>
                      
                    </table>
                   
                </div>
            
 
