<?php
use frontend\components\ProductWidget;
$js = <<<JS
var expenseId = $('#formID').val().replace(' ', '');
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
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="#" class="btn btn-default" name="deleteRecord" id="delRecord' + source.orderId + '"><span class="oi oi-x"></span></a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
            },

        ],
        columnDefs: [
            
            {
                "targets": [ 1 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 2 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 6 ],
                "visible": false,
                "sortable": false
                
        
            },
            
            
        ],
    tableId:"#ordersTable",
    refreshUrl:"/sold/orders/refreshd/"+expenseId,
    deleteUrl:"/sold/orders/delete",
    saveUrl:"/sold/orders/save",
    newUrl:"/sold/orders/new"

});
       
   $.ajax({
                url: '/clients/refreshd/',
                type: 'POST',
                
                success: function(res){
                    str = '<option value="0">Прямая продажа</option>';
                    $.each(res['datas'], function(index, val){
                        str += '<option value="'+val.clientId+'">'+val.clientName+'</option>';
                    });
                    $('#clients').html(str);
                    
                    $('#clients').chosen({width: "100%"});
                    $('#productList').chosen({width: "100%"});
                    $('#stuffList').chosen({width: "100%"});
                    //$.emptyValues();
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
    $('#saveModalForm').on('click', function(){
        var dataForm = $('#modalForm').serialize();
        $.ajax({                                                                                     
                url: '/sold/orders/save',                                                      
                type: 'POST',                                                                   
                data:dataForm,                                                                                        
                success: function(res){                                                         
                    $('#mainModal').modal('hide');   
                    $('#mainTable').DataTable().ajax.reload();
                },                                                                              
                error: function(xhr){                                                           
                    console.log(xhr.responseText);                                              
                }                                                                               
            });      
    });
                                                                                   
     $('#productList').on('change', function(evt, params) {
         target = $(evt.target),
            $('#stuffOrProdId').val(target.val());
         $('#tempName').val($("#productList option:selected").text());   
         
            $('#stuffOrProdType').val(0);
   
        });
     $('#stuffList').on('change', function(evt, params) {
         target = $(evt.target),
            $('#stuffOrProdId').val(target.val());
            $('#tempName').val($("#stuffList option:selected").text());   
            $('#stuffOrProdType').val(1);
   
        });
     
JS;

$this->registerJs($js);
?>
<?php //var_dump($model);?>
<div class="modal fade" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="mainModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Список продуктов</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                            
                            
                          <div class="modal-body">
                              <div class="container-fluid">
                                  <div class="row"><div class="col"><h3>Выберите </h3></div>
                                  </div>
                               <div class="row">

                                   <div class="col-6">
                                   <label>Продукты</label>
                                      <select id="productList">
                                          <?php
                                          $str = "";

                                          foreach($mProduct as $tone){
                                              $str .= '<option value="'.$tone->productId.'">'. $tone->name.'</option>';
                                          } 
                                          echo $str; 
                                          ?>
                                      </select>
                                  </div>                          
                                  <div class="col-6">
                                      <label>Продукция</label>
                                      <select id="stuffList">
                                          <?php
                                          $str2 = "";
                                          foreach($mStuff as $tone){
                                              $str2 .= '<option value="'.$tone->stuffId.'">'. $tone->name.'</option>';
                                          } 
                                          echo $str2;
                                          ?>
                                          
                                      </select>
                                  </div>
                               </div>
                               <div class="row">
                                   <div class="col">
                                       <form id="modalForm">
                                           <br>
                                           <input type="hidden" class="form-control" value="" id="stuffOrProdId" name="stuffProdId"/>
                                           <input type="hidden" class="form-control" value="" id="stuffOrProdType" name="idType"/>
                                           <div class="form-group row">
                                               <label for="staticId" class="col-sm-4 col-form-label">Наименование</label>
                                               <div class="col-sm-6">
                                                   <input type="text" class="form-control" id="tempName" value=""/>
                                               </div>
                                           </div>

                                           <div class="form-group row">
                                               <label for="staticId" class="col-sm-4 col-form-label">Количество</label>
                                               <div class="col-sm-6">
                                                   <input type="text" class="form-control" value="0" name="packCount"/>
                                               </div>
                                           </div>


                                           <button type="submit" class="btn btn-primary" id="saveModalForm" >Сохранить</button>

                                  </form>
                                   </div>
                               </div>
                              </div>
                              
                          
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>

                          </div>
                        </div>
                      </div>
                    </div>       
        </div>


<h3>Продажа</h3>
<div id="error"></div>       
<div class="row">
<div class="col">
    
                    <form class="mainForm" id="mainForm2" action="/sold/expense/step2" method="POST">
                        <div class="form-group row">
                        <label for="staticId" class="col-sm-2 col-form-label">Номер накладной</label>
                        <div class="col-sm-6">
                          <input type="text" readonly class="form-control-plaintext" name="expenseId" id="formID" value="<?=$model->expenseId ?> ">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="staticPaidType" class="col-sm-2 col-form-label">Тип оплаты</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="paidType">
                                <option <?=$model->paidType==0?:"selected";"" ?> value="1">Наличные</option>
                                <option <?=$model->paidType==1?:"selected";"" ?> value="0">Без наличный</option>
                                <option <?=$model->paidType==2?:"selected";"" ?> value="2">Перечисление</option>
                            </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="staticClients" class="col-sm-2 col-form-label">Клиент</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="clientId" id="clients">
                                
                            </select>
                        </div>
                      </div>
                        <div class="form-group row">
                            <label for="staticId" class="col-sm-2 col-form-label">Комментарий</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="comment" value="<?=$model->comment ?>" placeholder="комментарий если есть"/>
                            </div>
                        </div>
                      <div class="form-group row">

                          <div class="col">

                              <a href="/sold/expense" class="btn btn-success"><-Назад</a>

                              <button id="btnNext" type="submit" class="btn btn-warning">Продолжить-></button>

                        </div>
                          <div class="col-2">
                              <a id="btnModal" href="#" class="btn btn-primary" data-toggle="modal" data-target="#mainModal" >Добавить товар</a>
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
                          <th scope="col"></th>
                          <th scope="col"></th>
                          <th scope="col">Наименование</th>
                          <th scope="col">Количество</th>
                          <th scope="col">По факту</th>
                           <th scope="col"></th>
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
                        </tr>
                       
                      
                      </tbody>
                      
                    </table>
                   
                </div>

