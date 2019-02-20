
<?php
$js = <<<JS


       $("#mainTable").Custom({
    Columns:[
            {"data":'invoiceId'},
            {"data":'deliveryDate'},
            {"data":'transportType'},
            {"data":'description'},
            {"data": 'clientName'},
            {"data":'expNum'},
            {"data":'dogNum'},
            {"data":'name'},
            {"data":'cnt'},
            {"data":'mName'},
            {"data":'invoiceDate'},
            {"data":'driver'},
            {"data":'phone'},
            {"data":'carNumber'},
            {"data":'invoiceSumm'},
            {"data":'byOne'},
            
            /*{
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="invoice/invoicelist?id=' + source.invoiceId + '" target="_blank" class="btn btn-info" name="extendModal" id="invoice' + source.invoiceId + '" ">Список товаров</a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-info" name="invoiceEx">Список товаров</a>'
            },*/
            
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
        RowClick: function() {
          console.log("rowclick");
        },
    columnDefs: [
            {
                "targets": [ 2 ],
                "visible": true,
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
    newUrl:"/invoice/new",
    order: [[ 1, "desc" ]]

});

        $.ajax({
                url: '/clients/refreshd',
                type: 'POST',
                
                success: function(res){
                    $('#providers').empty();
                    
                $.each(res['datas'], function( index, value ){
                    $('#providers').append('<option value="'+value.clientId+'">'+value.clientName+'</option>');
                });
                
                $('#product').chosen({width: "100%"});
                $('#providers').chosen({width: "100%"});

                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        var element;
        
        $.customModal = function(elem){
            element = elem;
            id = $(elem).attr('id').replace('invoice', ''); 
            $.ajax({
                url: '/invoice/invoiceexes',
                type: 'POST',
                data:{'id':id},
                success: function(res){
                    $('#modalTblBody').empty();
                    tbl = "";
                    $.each(res['datas'], function(index, value){
                        tbl += "<tr><td>"+value.invoiceExId+"</td>";
                        tbl += "<td>"+value.productName+"</td>";
                        tbl += "<td>"+value.cnt+"</td>";
                        tbl += '<td><a href="#" class="btn btn-default" name="deleteRecordInv" id="delRecord' + value.invoiceExId + '"><span class="oi oi-x"></span></a></td></tr>';
                        
                        
                    });
                    $('#modalTblBody').append(tbl);
                    // $('#product').chosen({width: "100%"});
                    $('[name="deleteRecordInv"]').on('click', function(){
                        
                        $.DeleteRecordAll(this, '/invoiceex/delete', false);
                    });
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        };
                           
     $('#product').on('change', function(evt, params) {
         var target = $(evt.target);
            $('#stuffProdId').val(target.val());
            
            $('#idType').val(0);
   
        });
        $(document).on('change','#cnt', function() {
         var target = $(this);
         var sum = parseFloat($('#sum').val())*parseFloat(target.val());
         if(Number.isNaN(sum)){
             sum = 0;
         }
            $("#invoiceSum").val(sum);
            console.log(parseFloat($('#sum').val()));
        });
        $(document).on('change','#sum', function() {
         var target = $(this);
         if(Number.isNaN(sum)){
             sum = 0;
         }
          var sum = parseFloat($('#cnt').val())*parseFloat(target.val());

            $("#invoiceSum").val(sum);
        });
     
     
$('#cardForm').on('submit', function(){
        var id = $("#formID").val();
        var url1 = "/invoiceex/new";
        var data = $(this).serialize();
        $.ajax({
            url: url1,
            type: 'POST',
            data: data+"&id="+id+"&_csrf="+yii.getCsrfToken(),
            success: function(res){
                 if(Object.keys(res).length != 0){
                    $("#alertBox").remove();
                    var str = '<div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertBox">';
                    $.each(res, function (index,val) {
                        str += val[0]+"<br>";
                        $("[name='"+index+"']").css("border-color","#dc3545");
                    });
                    str += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                        '<span aria-hidden="true">&times;</span>'+
                        '</button>'+
                        '</div>';
                    $('#cardForm').prepend(str);
                }
                else {
                    $.customModal(element);
                    $("#cardForm")[0].reset();
                    $("#alertBox").remove();
                }
                console.log(res);
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
        
        return false; 
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
                                  <form class="" id="cardForm">
                                     <div class="">
                                         <div class="form-group row">
                                             <div class="col-sm-1">
<!--                                                 <input type="text" name="stuffProdId" id="stuffProdId" style="display: none;">-->
                                             </div>
                                         </div>
                                         <div class="form-group row">

<!--                                             <div class="col-sm-5">-->
<!--                                                 <select id="product">-->
<!--                                                     <option value="">Выберите продукцию</option>-->
<!--                                                     --><?php
//                                                     $str = "";
//
//                                                     foreach($mProduct as $tone){
//                                                         $str .= '<option value="'.$tone->productId.'">'. $tone->name.'</option>';
//                                                     }
//                                                     echo $str;
//                                                     ?>
<!--                                                 </select>-->
<!--                                             </div>-->
                                             <div class="col-sm-3">
<!--                                                 <input type="number" step="any" class="form-control" placeholder="Кол-во" name="cnt">-->
                                             </div>

                                             <div class="col-sm-4">
                                                 <button id="btnSaveCard" type="submit" class="btn btn-primary"><span class="oi oi-check"></span> Сохранить</button>

                                             </div>
                                         </div>
                                        
                                      </div>
                                  </form>
                                  <div class="row">
                                    <div class="col">
                                        <table id="modalTable" class="table" name="">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col" style="width:250px;" name="m_prductId" id="productId">Name</th>
                                                    <th scope="col">Count</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody id="modalTblBody">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                  </div>
                              </div>
                              
                            <div id="contentModal">
                          </div>
                        </div>
                      </div>
                    </div>       
        </div>
<h3>Приход продуктов</h3>
<div id="error"></div>            

<div class="row">
                <div class="col collapse" id="formBlock" >
                    <form class="mainForm" id="mainForm1" action="/invoice/save" method="POST">
                        <div class="form-group row">
                            <label for="staticId" class="col-sm-2 col-form-label">ID</label>
                            <div class="col-sm-5">
                              <input type="text" readonly class="form-control-plaintext" name="invoiceId" id="formID" value="0">
                            </div>
                            <label for="staticId" class="col-sm-1 col-form-label"># продукта</label>
                            <div class="col-sm-1">
                                <input type="text" readonly class="form-control-plaintext" name="stuffProdId" id="stuffProdId" value="0">
                            </div>
                            <label for="staticId" class="col-sm-1 col-form-label">Сумма</label>
                            <div class="col-sm-2">
                                <input type="text" readonly class="form-control-plaintext" name="invoiceSum" id="invoiceSum" value="0">
                            </div>
                         </div>

                      <div class="form-group row">
                        <label for="staticProvider" class="col-sm-2 col-form-label">Дата поставки</label>
                        <div class="col-sm-5">
                          <input type="date"  class="form-control" name="deliveryDate" id="inputinvoiceDate" placeholder="введите дату">
                        </div>

                          <div class="col-sm-5">
                              <select id="product" class="form-control">
                                  <option value="">Выберите продукцию</option>
                                  <?php
                                  $str = "";

                                  foreach($mProduct as $tone){
                                      $str .= '<option value="'.$tone->productId.'">'. $tone->name." (".$tone->measure->name.")".'</option>';
                                  }
                                  echo $str;
                                  ?>
                              </select>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputAdress" class="col-sm-2 col-form-label">Тип транспорта</label>
                        <div class="col-sm-5">

                            <select class="form-control" name="transportType" id="inputTransportType" >
                                <option value="Ж/Д">Ж/Д</option>
                                <option value="Автотранспорт">Автотранспорт</option>
                            </select>

                        </div>

                          <div class="col-sm-3">
                              <input type="number" step="any" class="form-control" placeholder="Кол-во в тоннах" id="cnt" name="cnt">
                          </div>
                          <div class="col-sm-2">
                              <input type="number" step="any" class="form-control" placeholder="Сумма одной" id="sum" name="sum">
                          </div>
                      </div>

                      <div class="form-group row">
                          <label for="inputAdress" class="col-sm-2 col-form-label">Описание</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" name="description" id="inputDescription" placeholder="описание">
                        </div>

                          <label for="expNum" class="col-sm-1 col-form-label">№ накладной</label>
                          <div class="col-sm-1">
                              <input type="text" class="form-control" name="expNum" id="expNum" placeholder="№">
                          </div>

                          <label for="dogNum" class="col-sm-1 col-form-label">№ договора</label>
                          <div class="col-sm-1">
                              <input type="text" class="form-control" name="dogNum" id="expNum" placeholder="№">
                          </div>
                      </div>

                        <div class="form-group row">
                            <label for="driver" class="col-sm-2 col-form-label">Водитель</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="driver" id="driver" placeholder="Ф.И.О.">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="phone" id="" placeholder="Телефон">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="carNumber" id="" placeholder="Номер машины">
                            </div>

                        </div>

                        <div class="form-group row">
                        </div>
                        <div class="form-group row">
                        <label for="staticProvider" class="col-sm-2 col-form-label">Поставщик</label>
                        <div class="col-sm-6">

                          <select  class="form-control" id="providers" name="clientId">

                          </select>
                          <input type="hidden"  class="form-control" name="ProviderName" id="inputProviderName" />
                        </div>

                          <div class="col-sm-2">
                              <button id="btnSave" type="submit" class="btn btn-primary"><span class="oi oi-check"></span> Сохранить</button>

                        </div>

                            <div class="col-sm-2">
                                <a href="#" id="btnCencel" class="btn btn-primary"  > Отмена</a>

                            </div>
                      </div>
                    </form>
                </div>

                <div class="col-sm-2">
                    <a href="#" id="btnNew" class="btn btn-primary "  ><span class="oi oi-plus"></span> Новый</a>

                </div>
            </div>

            <br/>
            
            
                <div class="col">
                    <table class="table" id="mainTable">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Дата поставки</th>
                          <th scope="col">Тип транспорта</th>
                          <th scope="col">Описание</th>
                          <th scope="col">Поставщик</th>
                            <th scope="col">№ накладной</th>
                            <th scope="col">№ договора</th>
                          <th scope="col">Наименование продукта</th>
                          <th scope="col">Кол-во</th>
                            <th scope="col">Ед.изм.</th>
                            <th scope="col">Дата прихода</th>
                            <th scope="col">Водитель</th>
                            <th scope="col">Номер</th>
                            <th scope="col">Номер машины</th>
                            <th scope="col">Сумма</th>
                            <th scope="col">Сумма за ед.</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                   
                </div>
            
 
