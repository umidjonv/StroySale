
<?php
$js = <<<JS


       $("#mainTable").Custom({
    Columns:[
            {"data":'invoiceId'},
            {"data":'invoiceDate'},
            {"data":'transportType'},
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        if(source.transportType==1)
                        return "Ж/Д" ;
                        else
                        return "Автотранспорт";
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-info" name="transportType">Автотранспорт</a>'
            },
            {"data":'description'},
            {"data":'providerId'},
            {"data": 'providerName'},
            
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="#" class="btn btn-info" name="invoiceEx" id="invoice' + source.invoiceId + '" data-toggle="modal" data-target="#mainModal">Список товаров</a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-info" name="invoiceEx">Список товаров</a>'
            },
            
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="#" class="btn btn-default" name="deleteRecord" id="delRecord' + source.invoiceId + '"><span class="oi oi-x"></span></a>';
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
                "targets": [ 6 ],
                "visible": true,
                "searchable": false
            },
            {
                "targets": [ 7 ],
                "visible": true,
                "sortable": false,
                "searchable": false
        
            },
            {
                "targets": [ 8 ],
                "visible": true,
                "sortable": false,
                "searchable": false
        
            },
            
        ],
    tableId:"#invoiceTable",
    refreshUrl:"/invoice/refreshd",
    deleteUrl:"/invoice/delete",
    saveUrl:"/invoice/save",
    newUrl:"/invoice/new"

});
  
        $.ajax({
                url: '/provider/refreshd',
                type: 'POST',
                
                success: function(res){
                    $('#providers').empty();
                    
                $.each(res['datas'], function( index, value ){
                    $('#providers').append('<option value="'+value.providerId+'">'+value.name+'</option>');
                });
                

                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        
JS;
 
$this->registerJs($js);
?>
      <div class="modal fade" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="mainModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Список наименований</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                              <div class="container-fluid">
                              
                                  <div class="row">
                                    <div class="col-md-6">
                                        <table id="modalTable">
                                            <thead>
                                                <tr
                                                    <th>ProductId</th>
                                                    <th>Name</th>
                                                    <th>Count</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                  </div>
                              </div>
                              
                            <div id="contentModal">
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button type="button" class="btn btn-primary">Сохранить</button>
                          </div>
                        </div>
                      </div>
                    </div>       
        </div>
<h3>Приход</h3>
<div id="error"></div>            
<?php// print_r($models->all()[0]->name) ?>
<div class="row">
                <div class="col">
                    <form class="mainForm" id="mainForm1" action="/invoice/save" method="POST">
                        <div class="form-group row">
                        <label for="staticId" class="col-sm-2 col-form-label">ID</label>
                        <div class="col-sm-6">
                          <input type="text" readonly class="form-control-plaintext" name="invoiceId" id="formID" value="0">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="staticProvider" class="col-sm-2 col-form-label">Дата поставки</label>
                        <div class="col-sm-6">
                          <input type="text"  class="form-control" name="invoiceDate" id="inputinvoiceDate" placeholder="введите дату">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputAdress" class="col-sm-2 col-form-label">Тип транспорта</label>
                        <div class="col-sm-6">
                            
                            <select class="form-control" name="transportType" id="inputTransportType" >
                                <option value="Ж/Д">Ж/Д</option>
                                <option value="Автотранспорт">Автотранспорт</option>
                            </select>
                            
                        </div>
                      </div>
                      <div class="form-group row">
                          <label for="inputAdress" class="col-sm-2 col-form-label">Описание</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="description" id="inputDescription" placeholder="описание">
                        </div>
                        
                      </div>
                        <div class="form-group row">
                        <label for="staticProvider" class="col-sm-2 col-form-label">Поставщик</label>
                        <div class="col-sm-6">
                          
                          <select  class="form-control" id="providers" name="providerId">
                              
                          </select>
                          <input type="hidden"  class="form-control" name="ProviderName" id="inputProviderName" />
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
                          <th scope="col">Дата прихода</th>
                          <th>transportType</th>
                          <th scope="col">Тип транспорта</th>
                          <th scope="col">Описание</th>
                          <th scope="col">#Поставщика</th>
                          <th scope="col">Поставщик</th>
                          
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>
                      <?php if(isset($models)):?>
                      <tbody>
                          
                          <?php foreach($models->all() as $model){?>
                        <tr class="tableRow">
                          <td scope="row"><?= $model->invoiceId ?></td>
                          <td><?= $model->invoiceDate ?></td>
                          <td><?= $model->transportType ?></td>
                          <td></td>
                          <td><?= $model->description ?></td>
                          <td><?= $model->providerId ?></td>
                          <td><?= $model->providerName ?></td>
                          <td></td>
                          <td></td>
                        </tr>
                       
                          <?php } ?>
                      </tbody>
                      <?php endif;?>
                    </table>
                   
                </div>
            
 
