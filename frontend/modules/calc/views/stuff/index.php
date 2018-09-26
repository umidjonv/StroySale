
<?php
$js = <<<JS
$("#mainTable").Custom({
    Columns:[
            {"data":'stuffId'},
            {"data":'name'},
            {"data":'energy'},
            {"data":'salary'},
            {"data":'measureId'},
            {"data":'measure'},
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="#" class="btn btn-info" name="struct" id="struct' + source.stuffId + '" data-toggle="modal" data-target=".bd-example-modal-lg">Карточка рецепта</a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-info" name="struct">Карточка рецепта</a>'
            },
            
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="#" class="btn btn-default" name="deleteRecord" id="delRecord' + source.stuffId + '"><span class="oi oi-x"></span></a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
            },

        ],
    columnDefs: [
            {
                "targets": [ 6 ],
                "visible": true,
                "sortable": false,
                "searchable": false
        
            },
            {
                "targets": [ 7 ],
                "visible": true,
                "sortable": false,
                "searchable": false
        
            },
            
        ],
    refreshUrl:"/calc/stuff/refreshd",
    deleteUrl:"/calc/stuff/delete",
    saveUrl:"/calc/stuff/save",
    newUrl:"/calc/stuff/new"

});

$.resetCard = function() {
                $("#cardForm")[0].reset();
                $("#structID").val(0);
                refreshProdType();
};
$(document).on("click","#btnNewCard", function() {
  $.resetCard();
});

$('#cardForm').on('submit', function(){
        var stuffId = $("#formID").val();
        var formId = $('#structID').val();
        var url1 = "/calc/struct/save";
        if(formId == 0)
        {
            url1 = "/calc/struct/new";
        }
        var data = $(this).serialize();
        $.ajax({
            url: url1,
            type: 'POST',
            data: data+"&stuffId="+stuffId+"&_csrf="+yii.getCsrfToken(),
            success: function(res){
                refreshCard($("#formID").val());  
                $.resetCard();
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
        
        return false; 
    });    

$(document).on("show.bs.modal","#modalWindow",function() {
    refreshProdType();
    refreshCard($("#formID").val());
});

$(document).on("change","#prodType",function() {
  refreshProdType();
});

$(document).on("click",".deleteCard",function() {
    $("#modalWindow").modal("hide");
  $.deleteCardRecord($(this));
});

$(document).on("click",".cardRow",function() {
    
  $.cardRowClick($(this));
});


$.cardRowClick = function(elem) {
        var tableData = $(elem).children("td").map(function() {
            return $(this).text();
        }).get();
        if(tableData[4] == "Продукт"){
            $("#prodType").val(0);
        }
        else{
            $("#prodType").val(1);
        }
        var inputs = $("#cardForm :input");
        $.when(refreshProdType()).then(function() {  
            var values = {};
            var i=0;
            inputs.each(function() {
                switch (i){
                    case 0 :
                        $(this).val(tableData[0]);
                        break;
                    case 2 :
                        $(this).children("option").filter(function () {
                            return this.text == tableData[1];
                        }).attr("selected",true);
                        break;
                    case 3:
                        $(this).val(tableData[2]);
                        break;
                        
                }
                i++;
            });
        });
        
        
    };
$.deleteCardRecord = function(elem) {
        eId = $(elem).attr('id');
        eId= eId.replace('delRecord', '');
        console.log(eId);
        yesFunc = function () {
        var formId = $('#structID').val();
        var url1 = "/calc/struct/delete";
        $.ajax({
            url: url1,
            type: 'POST',
            data: {id:eId},
            success: function(res){
                var tableRow = $("td").filter(function() {
                    return $(this).text() == eId;
                }).closest("tr");
                $(tableRow).remove();
                $.resetCard();
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
            $(this).dialog("close");
        };  
        $.ConfirmDialog('Удалить запись', yesFunc);
  
}
function  refreshProdType() {
    var val = $("#prodType").val();
    var text = "";
  $.ajax({
        url: "/calc/struct/refresh-prod-type",
        type: 'POST',
        data:{_csrf : yii.getCsrfToken(),val:val},
        dataType: 'json',
        success: function(res){
            text += "<select class='form-control' name='stuffProdId'>";
            $.each(res, function(index,val) {
                  text += "<option value='"+index+"'>"+val+"</option>";
            });
            text += "</select";
            $("#product").html(text);
        },
        error: function(xhr){
            console.log(xhr.responseText);
        }
    });
};
function refreshCard(stuffId) {
    $.ajax({
        url: "/calc/struct/refresh",
        type: 'POST',
        data:{_csrf : yii.getCsrfToken(),id:stuffId},
        success: function(res){
            var text = "";
            $.each(res, function(index, val) {
            var prodType = (val.idType == 1) ? "Продукция" : "Продукт";
              text += "<tr class='cardRow'>" +
               "<td>"+val.structId+"</td>"+
               "<td>"+val.prodName+"</td>"+
               "<td>"+val.cnt+"</td>"+
               "<td>"+val.measure+"</td>"+
               "<td>"+prodType+"</td>"+
               "<td><a href='#' class='btn btn-default deleteCard' id='delRecord"+val.structId+"'><span class='oi oi-x'></span></a></td>"+
               "</tr>";
            });
            $("#cardTable tbody").html(text);
        },
        error: function(xhr){
            console.log(xhr.responseText);
        }
    });
}
JS;

$this->registerJs($js);
$product = new \app\modules\calc\models\Stuff();
?>
<h3>Рецепты</h3>
<div id="error"></div>

<div class="row">
    <div class="col-sm-10">
        <form class="mainForm" id="mainForm1" action="" method="POST">
            <div class="form-group row">
                <label for="formID" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control-plaintext" name="productId" id="formID" value="0">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputStuffName" class="col-sm-2 col-form-label">Наименование</label>
                <div class="col-sm-5">
                    <input type="text"  class="form-control" name="name" id="inputStuffName" placeholder="Введите наименование">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputStuffEnergy" class="col-sm-2 col-form-label">Энергия</label>
                <div class="col-sm-5">
                    <input type="number"  class="form-control" name="energy" id="inputStuffEnergy" placeholder="Энергия">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputStuffSalary" class="col-sm-2 col-form-label">Зар. плата</label>
                <div class="col-sm-5">
                    <input type="number"  class="form-control" name="salary" id="inputStuffSalary" placeholder="Зар. плата">
                </div>
            </div>
            <div class="form-group row ">
                <label for="measureId" class="col-sm-2 col-form-label">Ед.Изм.</label>
                <div class="col-sm-5">
                    <?=\yii\helpers\Html::dropDownList("measureId",'',\yii\helpers\ArrayHelper::map(\app\modules\calc\models\Measure::find()->all(),'measureId','name'),array('class'=>"form-control","id"=>"measureId"))?>
                </div>
                <div class="col-sm-2">
                    <button id="btnSave" type="submit" class="btn btn-primary"><span class="oi oi-check"></span> Сохранить</button>

                </div>
                <div class="col-sm-2">
                    <a href="#" id="btnNew" class="btn btn-primary"><span class="oi oi-plus"></span>Новый</a>

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
            <th scope="col">Наиенование</th>
            <th scope="col">Энергия</th>
            <th scope="col">Зар.плата</th>
            <th scope="col">#</th>
            <th scope="col">Ед.Изм</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <?php if(isset($models)):?>
            <tbody>

            <?php foreach($models->all() as $model){?>
                <tr class="tableRow">
                    <td scope="row"><?= $model->stuffId ?></td>
                    <td><?= $model->name ?></td>
                    <td><?= $model->energy?></td>
                    <td><?= $model->salary?></td>
                    <td><?= $model->measureId?></td>
                    <td><?= $model->measure->name?></td>
                    <th></th>
                    <td></td>
                </tr>

            <?php } ?>
            </tbody>
        <?php endif;?>
    </table>

</div>

<div id="modalWindow" class="modal fade bd-example-modal-lg col-lg-12" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg col-lg-12">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Карточка рецепта</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div id="">
                    <div class="col-sm-12">
                        <form action="" id="cardForm"  method="POST">
                            <div class="form-group row">
                                <label for="structID" class="col-sm-2 col-form-label">ID</label>
                                <div class="col-sm-6">
                                    <input type="text" readonly class="form-control-plaintext" name="structId" id="structID" value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <select name="idType" id="prodType" class="form-control">
                                        <option value="0">Продукт</option>
                                        <option value="1">Продукция</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4" id="product">
                                </div>
                                <div class="col-sm-3">
                                    <input type="number" step="any" class="form-control" placeholder="Кол-во" name="cnt">
                                </div>
                                <div class="col-sm-3">
                                    <button id="btnSaveCard" type="submit" class="btn btn-primary"><span class="oi oi-check"></span> Сохранить</button>

                                </div>
                                <div class="col-sm-2">
                                    <a href="#" id="btnNewCard" class="btn btn-primary"><span class="oi oi-plus"></span>Новый</a>

                                </div>
                            </div>

                        </form>
                    </div>
                    <table class="table" id="cardTable">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Наиенование</th>
                            <th scope="col">Кол-во</th>
                            <th scope="col">Ед.Изм</th>
                            <th scope="col">Тип</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>