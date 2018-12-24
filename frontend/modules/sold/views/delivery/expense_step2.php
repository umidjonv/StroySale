<?php
$js = <<<JS

$.ajax({                                                                                     
                url: '/sold/delivery/getnames',                                                      
                type: 'POST',                                                                   
                                                                                               
                success: function(res){              
                   
                    str = '<option value=""></option>';
                    $.each(res, function(index, val){
                        str += '<option value="'+val.name+'">'+val.name+'</option>';
                    });
                    $('#nameSelect').html(str);
                    
                    $('#nameSelect').chosen({width: "100%"});
                },                                                                              
                error: function(xhr){                                                           
                    console.log(xhr.responseText);                                              
                }                                                                               
            });
$.ajax({                                                                                     
                url: '/sold/delivery/getdrivers',                                                      
                type: 'POST',                                                                   
                                                                                               
                success: function(res){              
                    
                    str = '<option value=""></option>';
                    $.each(res, function(index, val){
                        str += '<option value="'+val.driver+'">'+val.driver+'</option>';
                    });
                    $('#driverSelect').html(str);
                    
                    $('#driverSelect').chosen({width: "100%"});
                },                                                                              
                error: function(xhr){                                                           
                    console.log(xhr.responseText);                                              
                }                                                                               
            });
$.ajax({                                                                                     
                url: '/sold/delivery/getaddresses',                                                      
                type: 'POST',                                                                   
                                                                                               
                success: function(res){              
                    
                    str = '<option value=""></option>';
                    $.each(res, function(index, val){
                        str += '<option value="'+val.address+'">'+val.address+'</option>';
                    });
                    $('#addressSelect').html(str);
                    
                    $('#addressSelect').chosen({width: "100%"});
                },                                                                              
                error: function(xhr){                                                           
                    console.log(xhr.responseText);                                              
                }                                                                               
            });
$('#nameSelect').on('change', function(){
     $('#nameInput').val($(this).val());
});
$('#driverSelect').on('change', function(){
     $('#driverInput').val($(this).val());
});
$('#addressSelect').on('change', function(){
     $('#addressInput').val($(this).val());
});

JS;
$this->registerJs($js);

?>
<h3>Продажа дополнительно</h3>
<div id="error"></div>
<div class="row">
    <div class="col-6">
        <form class="form">
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
            <div class="form-group row">
                <label for="staticId" class="col-sm-6 col-form-label">Номер накладной</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control-plaintext" id="vformID" value="<?=$model->expenseId; ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticId" class="col-sm-6 col-form-label">Тип оплаты</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control-plaintext" id="vformID" value="<?=$model->paidType==0?'Наличные':($model->paidType==1?'Без наличный':'Перечисление'); ?> ">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticId" class="col-sm-6 col-form-label">Сумма продажи</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control-plaintext" id="vformID" value="<?=$model->expSum; ?>" >
                </div>
            </div>
        </form>
    </div>
    <div class="col-6">

        <form class="mainForm" id="mainForm1" action="/sold/delivery/save" method="POST">

            <div class="form-group row">
                <label for="staticName" class="col-sm-4 col-form-label">Ответственное лицо</label>
                <div class="col-sm-6">
                    <input type="text" id="nameInput" class="form-control" name="name" value="<?=$modelDelivery->name; ?>" autocomplete="off">
                    <select id="nameSelect">

                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticName" class="col-sm-4 col-form-label">Водитель</label>
                <div class="col-sm-6">
                    <input type="text" id="driverInput" class="form-control" name="driver" value="<?=$modelDelivery->driver; ?>" autocomplete="off">
                    <select id="driverSelect">

                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticAddress" class="col-sm-4 col-form-label">Адрес</label>
                <div class="col-sm-6">
                    <input type="text" id="addressInput" class="form-control" name="address" id="formID" value="<?=$modelDelivery->address; ?>" autocomplete="off">
                    <select id="addressSelect">

                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticAddress" class="col-sm-4 col-form-label">Тип</label>
                <div class="col-sm-6">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="customRadio1" name="deliveryType" class="custom-control-input"  checked value="0">
                        <label class="custom-control-label" for="customRadio1">Самовывоз</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="customRadio2" name="deliveryType" class="custom-control-input" value="1">
                        <label class="custom-control-label" for="customRadio2">Доставка</label>
                    </div>


                </div>
            </div>


            <div class="form-group row">
                <label for="staticDescription" class="col-sm-4 col-form-label">Описание</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="description" value="<?=$modelDelivery->description; ?>" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticPrice" class="col-sm-4 col-form-label">Стоимость доставки</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="price" value="<?= (isset($modelDelivery->price)?$modelDelivery->price:0); ?>" autocomplete="off">

                </div>
            </div>


            <div class="form-group row">

                <div class="col">
                    <a id="btnPrev" href="/sold/expense/step1" class="btn btn-success"><-Назад</a>
                    <button type="submit" class="btn btn-warning">Сохранить-></button>

                </div>


            </div>
        </form>
    </div>

</div>
