
<?php
$js = <<<JS
$("#mainTable").Custom({
    Columns:[
            {"data":'stuffId'},
            {"data":'name'},
            {"data":'price'},
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
                        return '<a href="#" class="btn btn-info" name="struct" id="struct' + source.stuffId + '" data-toggle="modal" data-target=".bd-example-modal-lg">Карточка продукции</a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-info" name="struct">Карточка продукции</a>'
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
    refreshCard($("#formID").val());
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
        console.log(tableData[5]);
        if(tableData[5] == "Продукт"){
            $("#idType").val(0);
        }
        else{
            $("#idType").val(1);
        }
        $("#stuffProdId").val(tableData[1]);
        $("#tempName").val(tableData[2]);
        $("#structID").val(tableData[0]);
        $("#cnt").val(tableData[3]);
        
        
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
  
};
                                                      
     $('#productList').on('change', function(evt, params) {
         target = $(evt.target),
            $('#stuffProdId').val(target.val());
         $('#tempName').val($("#productList option:selected").text());   
         
            $('#idType').val(0);
   
        });
     $('#stuffList').on('change', function(evt, params) {
         target = $(evt.target),
            $('#stuffProdId').val(target.val());
            $('#tempName').val($("#stuffList option:selected").text());   
            $('#idType').val(1);
   
        });
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
               "<td class='d-none'>"+val.stuffProdId+"</td>"+
               "<td>"+val.prodName+"</td>"+
               "<td>"+val.cnt+"</td>"+
               "<td>"+val.measure+"</td>"+
               "<td>"+prodType+"</td>"+
               "<td><a href='#' class='btn btn-default deleteCard' id='delRecord"+val.structId+"'><span class='oi oi-x'></span></a></td>"+
               "</tr>";
            });
                    $('#productList').chosen({width: "100%"});
                    $('#stuffList').chosen({width: "100%"});
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
<h3>Продукция</h3>
<div id="error"></div>
<?php// print_r($models->all()[0]->name) ?>
<div class="row">
    <div class="col-sm-10">
        <form class="mainForm" id="mainForm1" action="" method="POST">
            <div class="form-group row">
                <label for="formID" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control-plaintext" name="stuffId" id="formID" value="0">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputStuffName" class="col-sm-2 col-form-label">Наименование</label>
                <div class="col-sm-5">
                    <input type="text"  class="form-control" name="name" id="inputStuffName" placeholder="Введите наименование">
                </div>
                <label for="inputStuffPrice" class="col-sm-1 col-form-label">Цена</label>
                <div class="col-sm-3">
                    <input type="number"  class="form-control" name="price" id="inputStuffPrice" placeholder="введите цену">
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
            <th scope="col">Цена</th>
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
                    <td><?= $model->price ?></td>
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
                <h5 class="modal-title" id="exampleModalLabel">Карточка продукции</h5>
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
                                <div class="col-sm-4">
                                    <input type="text" readonly class="form-control-plaintext" name="structId" id="structID" value="0">
                                </div>
                                <div class="col-sm-1">
                                    <input type="text" name="idType" id="idType" class="invisible">
                                </div><div class="col-sm-1">
                                    <input type="text" name="stuffProdId" id="stuffProdId" class="invisible">
                                </div>
                            </div>
                            <div class="form-group row">

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
                            <div class="form-group row">
                                <div class="col-sm-4" id="product">
                                    <input type="text" step="any" class="form-control" placeholder="Наименование" id="tempName" disabled>
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
                            <th scope="col" class="d-none">#</th>
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