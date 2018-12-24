
<?php
$menuItems[] = [
    'label' => 'Клиенты',
    'url' => ['/accounting/client'],
    'active' => in_array(\Yii::$app->controller->id, ['index']),
];
$js = <<<JS
var clientId;
    $(document).on('click','#btnClientSave', function(){
        var url1 = "/accounting/report/get-client-balance";
        var data = $("#mainForm2").serialize();
        $.ajax({
            url: url1,
            type: 'POST',
            data: data+"&id="+clientId,
            success: function(res){
                var text = "";
                $.each(res.datas, function(key,index) {
                  text += "<tr class='tableRow'>" +
                        "<td>" + index.accountDate + "</td>" +
                        "<td>" + index.comment + "</td>" +
                        "<td>" + index.summ + "</td>" +
                        "<td>" + ((index.accountType == 1) ? "Приход" : "Расход") + "</td>" +
                    "</tr>";
                });
                text += "<tr>"+
                        "<td colspan='2'>Остаток</td>"+
                        "<td colspan='2'>"+res.balance+"</td>"+
                    "</tr>";
                $("#mainTable2 tbody").html(text);
            },            
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
        
        return false; 
    });   
$(document).on("click",".clientModal", function() {
        var url1 = "/accounting/report/client-balance";
  var id = $(this).attr("id").split("struct");
  clientId = id[1];
  $.ajax({
            url: url1,
            type: 'POST',
            data: {id:id[1]},
            success: function(res){
                $("#clientModal").modal("show");
                $("#clientModal .modal-body").html(res);
            },            
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
});
$("#mainTable").Custom({
    Columns:[
            {"data":'clientId'},
            {"data":'name'},
            {"data":'phone'},
            {"data":'summ'},
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="#" class="btn btn-info clientModal" name="struct" id="struct' + source.clientId + '" data-toggle="modal" >Остаток</a>';
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
                        return '<a href="#" class="btn btn-default" name="deleteRecord" id="delRecord' + source.clientId + '"><span class="oi oi-x"></span></a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
            },

        ],
    refreshUrl:"/accounting/client/refreshd",
    deleteUrl:"/accounting/client/delete",
    saveUrl:"/accounting/client/save",
    newUrl:"/accounting/client/new"

});

        
JS;

$this->registerJs($js);
?>

<h3>Точка</h3>
<div id="error"></div>
<div class="row">
    <div class="col">
        <form class="mainForm" id="mainForm1" action="/point/save" method="POST">
            <div class="form-group row">
                <label for="formID" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control-plaintext" name="clientId" id="formID" value="0">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPointName" class="col-sm-2 col-form-label">Наименование точки</label>
                <div class="col-sm-5">
                    <input type="text"  class="form-control" name="name" id="inputPointName" placeholder="введите наименование">
                </div>
            </div>
            <div class="form-group row pull-right">
                <label for="inputPhone" class="col-sm-2 col-form-label">Телефон</label>
                <div class="col-sm-5">
                    <input type="tel"  class="form-control" name="phone" id="inputPhone" placeholder="введите телефон">
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
            <th scope="col">Наименование</th>
            <th scope="col">Телефон</th>
            <th scope="col">Сумма</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <?php if(isset($models)):?>
            <tbody>

            <?php foreach($models->all() as $model){?>
                <tr class="tableRow">
                    <td scope="row"><?= $model->clientId ?></td>
                    <td><?= $model->name ?></td>
                    <td></td>
                    <td></td>
                </tr>

            <?php } ?>
            </tbody>
        <?php endif;?>
    </table>

</div>


<div id="clientModal" class="modal fade col-lg-12" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog col-lg-12">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить приход/расход</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div id="">
                    <div class="col-sm-12">
                        <form action="" id="costForm"  method="POST" >
                            <div class="form-group row">
                                <label for="" class="col-sm-2">Точка</label>
                                <div class="col-sm-5">
                                    <?=\yii\helpers\Html::dropDownList("pointId",'',\yii\helpers\ArrayHelper::map(\app\modules\accounting\models\Point::find()->where("status!=1")->all(),'pointId','name'),array('class'=>"form-control","id"=>"pointId"))?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-2">Дата</label>
                                <div class="col-sm-5">
                                    <input type="date" class="form-control" placeholder="Дата" name="date" value="<?=date("Y-m-d")?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-2">Срок</label>
                                <div class="col-sm-5">
                                    <input type="date" class="form-control" placeholder="Срок" name="dateLimit">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-2">Клиент</label>
                                <div class="col-sm-5">
                                    <?=\yii\helpers\Html::dropDownList("clientId",'',\yii\helpers\ArrayHelper::map(\app\modules\accounting\models\Client::find()->where("status!=1")->all(),'clientId','name'),array('class'=>"form-control","id"=>"clientId",'prompt'=>'Выберите клиента'))?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-2">Комментарий</label>
                                <div class="col-sm-5">
                                    <input type="text" step="any" class="form-control" placeholder="Комментарий" name="comment">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-2">Сумма</label>
                                <div class="col-sm-5">
                                    <input type="number" step="any" class="form-control" placeholder="Сумма" name="summ">
                                </div>
                                <div class="col-sm-3">
                                    <button id="btnSaveCard" type="submit" class="btn btn-primary"><span class="oi oi-check"></span> Сохранить</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

