
<?php
$js = <<<JS


       $("#mainTable").Custom({
    Columns:[
            {"data":'invoiceStuffId'},
            {"data":'description'},
            {"data":'invoiceDate'},
            {"data":'name'},
            {"data":'cnt'},
            /*
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="#" class="btn btn-info" name="extendModal" id="invoiceStuff' + source.invoiceStuffId + '" data-toggle="modal" data-target="#mainModal">Список товаров</a>';
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
                        return '<a href="#" class="btn btn-default" name="deleteRecord" id="delRecord' + source.invoiceStuffId + '"><span class="oi oi-x"></span></a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
            },

        ],
        RowClick: function() {
          console.log("rowclick");
        },
    tableId:"#invoiceTable",
    refreshUrl:"/invoicestuff/refreshd",
    deleteUrl:"/invoicestuff/delete",
    newUrl:"/invoicestuff/new"

});

                $('#product').chosen({width: "100%"});
        var element;
        
        $.customModal = function(elem){
            element = elem;
            id = $(elem).attr('id').replace('invoiceStuff', ''); 
            $.ajax({
                url: '/invoicestuff/invoiceexes',
                type: 'POST',
                data:{'id':id},
                success: function(res){
                    $('#modalTblBody').empty();
                    tbl = "";
                    $.each(res['datas'], function(index, value){
                        tbl += "<tr><td>"+value.invoiceExStuffId+"</td>";
                        tbl += "<td>"+value.productName+"</td>";
                        tbl += "<td>"+value.cnt+"</td>";
                        tbl += '<td><a href="#" class="btn btn-default" name="deleteRecordInv" id="delRecord' + value.invoiceExStuffId + '"><span class="oi oi-x"></span></a></td></tr>';
                        
                        
                    });
                    $('#modalTblBody').append(tbl);
                    $('#product').chosen({width: "100%"});
                    $('[name="deleteRecordInv"]').on('click', function(){
                        
                        $.DeleteRecordAll(this, '/invoiceexstuff/delete', false);
                    });
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        };
                           
     $('#product').on('change', function(evt, params) {
         target = $(evt.target),
            $('#stuffProdId').val(target.val());
         
            $('#idType').val(0);
   
        });
     
     
$('#cardForm').on('submit', function(){
        var id = $("#formID").val();
        var url1 = "/invoiceexstuff/new";
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

                                             <div class="col-sm-5">
<!--                                                 <select id="product">-->
<!--                                                     <option value="">Выберите продукцию</option>-->
<!--                                                     --><?php
//                                                     $str = "";
//
//                                                     foreach($mProduct as $tone){
//                                                         $str .= '<option value="'.$tone->stuffId.'">'. $tone->name.'</option>';
//                                                     }
//                                                     echo $str;
//                                                     ?>
<!--                                                 </select>-->
                                             </div>
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
<h3>Приход продукции</h3>
<div id="error"></div>            

<div class="row">
                <div class="col collapse" id="formBlock" >
                    <form class="mainForm" id="mainForm1" action="/invoicestuff/save" method="POST">
                        <div class="form-group row">
                        <label for="staticId" class="col-sm-2 col-form-label">ID</label>
                        <div class="col-sm-1">
                          <input type="text" readonly class="form-control-plaintext" name="invoiceStuffId" id="formID" value="0">
                        </div>

                      </div>
                        <div class="form-group row">

                            <label for="inputAdress" class="col-sm-2 col-form-label">Описание</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="description" id="inputDescription" placeholder="описание">
                            </div>
                      </div>
                        <div class="form-group row">

                            <label for="staticId" class="col-sm-2 col-form-label"># продукта</label>
                            <div class="col-sm-2">
                                <input type="text" readonly class="form-control-plaintext" name="stuffProdId" id="stuffProdId" value="0">
                            </div>
                            <div class="col-sm-4">
                                <select id="product" class="form-control">
                                    <option value="">Выберите продукцию</option>
                                    <?php
                                    $str = "";

                                    foreach($mProduct as $tone){
                                        $str .= '<option value="'.$tone->stuffId.'">'. $tone->name.'</option>';
                                    }
                                    echo $str;
                                    ?>
                                </select>
                            </div>

                        </div>
                        <div class="form-group row">

                            <label  class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-3">
                                <input type="number" step="any" class="form-control" placeholder="Кол-во" name="cnt">
                            </div>
                            <label  class="col-sm-3 col-form-label"></label>
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
                            <th scope="col">Описание</th>
                            <th scope="col">Дата прихода</th>
                            <th scope="col">Наименование продукции</th>
                            <th scope="col">Кол-во</th>
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                   
                </div>
            
 
