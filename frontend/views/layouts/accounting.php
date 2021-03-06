<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginContent('@app/views/layouts/stroy.php'); ?>
<?
$js = <<<JS
// menuActive('accpunting/clinet');
function menuActive(controller)

{
console.log(controller);
    //All links begining with the controller parameter
console.log($('a[href'+'^="/'+controller+'"]'));
    $('a[href'+'^="/'+controller+'"]').addClass('active');

}
     $('#clientList').chosen({width: "35%"});
     $('#accountList').chosen({width: "70%"});
$(document).on('click','#btnSaveCard', function(){
        var text = '<div>\
                        <div class="form-group row">\
                            <div class="col-sm-1 text-center">\
                                <h4>1</h4>\
                            </div>\
                            <div class="col-sm-10">\
                                <input type="text" step="any" class="form-control" placeholder="Комментарий" name="comment[]" autocomplete="off">\
                            </div>\
                            <div class="">\
                                <button class="btn btn-primary addRow" type="button"><span class="oi oi-plus"></span></button>\
                            </div>\
                        </div>\
                        <div class="form-group row">\
                            <div class="col-sm-4">\
                                <input type="number" step="any" class="form-control" placeholder="Кол-во" name="cnt[]" autocomplete="off">\
                            </div>\
                            <div class="col-sm-4">\
                                <input type="number" step="any" class="form-control" placeholder="Цена за одну" name="byone[]" autocomplete="off">\
                            </div>\
                            <div class="col-sm-4">\
                                <input type="number" step="any" class="form-control" placeholder="Сумма" name="summ[]">\
                            </div>\
                        </div>\
                    </div>';
        var url1 = "/accounting/account/new";
        var data = $("#costForm").serialize();
        console.log(data);
        $.ajax({
            url: url1,
            type: 'POST',
            data: data+"&_csrf="+yii.getCsrfToken()+"&type=counting",
            success: function(res){       
                $("#uslugiWindow").modal("hide");
                console.log(res);
                $(".severalRows").html(text);
                row = 1;
                $("#costForm")[0].reset();
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
        
        return false; 
    });    
var row = 1;
$(document).on('click','.addRow', function(){
    row = row + 1;
        var text = '<div>\
                        <div class="form-group row">\
                            <div class="col-sm-1 text-center">\
                                <h4>'+row+'</h4>\
                            </div>\
                            <div class="col-sm-10">\
                                <input type="text" step="any" class="form-control" placeholder="Комментарий" name="comment[]" autocomplete="off">\
                            </div>\
                        </div>\
                        <div class="form-group row">\
                            <div class="col-sm-4">\
                                <input type="number" step="any" class="form-control" placeholder="Кол-во" name="cnt[]" autocomplete="off">\
                            </div>\
                            <div class="col-sm-4">\
                                <input type="number" step="any" class="form-control" placeholder="Цена за одну" name="byone[]" autocomplete="off">\
                            </div>\
                            <div class="col-sm-4">\
                                <input type="number" step="any" class="form-control" placeholder="Сумма" name="summ[]">\
                            </div>\
                        </div>\
                    </div>';
        $(".severalRows").append(text);
    });    
JS;

$this->registerJs($js);
?>
<div class="row">
    <div class="col">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><span class="oi oi-magnifying-glass"></span> </span>
            </div>

            <input type="text" class="form-control" id="searchInput" placeholder="Поиск">
        </div>

    </div>
    <div class="col-2">

        <? if(Yii::$app->controller->id =='expense'||  (Yii::$app->controller->id =='report'&&Yii::$app->controller->module->id =='accounting' )): ?>
        <a href="javascript:;" class="btn btn-info" data-toggle="modal"  data-target="#uslugiWindow">добавить услугу</a>
        <? endif;?>
    </div>

</div>
<br/>
<?php echo $content ?>


    <div id="uslugiWindow" class="modal fade bd-example-modal-lg col-lg-12" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg col-lg-12">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавить приход/расход</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="">
                        <form action="" id="costForm"  method="POST" >
                        <div class="form-group row">
                            <div class="col-sm-5">
                                <input type="number" class="form-control" placeholder="Номер накладной" name="expenseId" autocomplete="off">
                            </div> или  &nbsp;
                            <label for="" class="col-sm-2">Клиент</label>
                            <select name="clientId" class="form-control" id="clientList">
                                <? foreach (\app\models\Clients::find()->all() as $item){?>
                                    <option value="<?=$item->clientId ?>"><?=$item->clientName ?></option>
                                <?}?>
                            </select>
                        </div>
                        <div class="severalRows">
                            <div>
                                <div class="form-group row">
                                    <div class="col-sm-1 text-center">
                                        <h4>1</h4>
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="text" step="any" class="form-control" placeholder="Комментарий" name="comment[]" autocomplete="off">
                                    </div>
                                    <div class="">
                                        <button class="btn btn-primary addRow" type="button" ><span class="oi oi-plus"></span></button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <input type="number" step="any" class="form-control" placeholder="Кол-во" name="cnt[]" autocomplete="off">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" step="any" class="form-control" placeholder="Цена за одну" name="byone[]" autocomplete="off">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" step="any" class="form-control" placeholder="Сумма" name="summ[]">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3">Связать с услугой</label>
                            <select name="accountId" class="form-control" id="accountList">
                                <option value="">Выберите</option>
                                <? foreach (\app\modules\accounting\models\Account::find()->where("accountType = 1 and (connect is null or connect ='')")->groupBy("identity")->all() as $item){?>
                                    <option value="<?=$item->identity ?>"><?=$item->identity?></option>
                                <?}?>
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <input type="date" class="form-control" placeholder="Дата услуги" name="serviceDate" autocomplete="off">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="Идентификация" name="identity" autocomplete="off">
                            </div>
                            <div class="col-sm-3">
                                <button id="btnSaveCard" type="button" class="btn btn-primary"><span class="oi oi-check"></span> Сохранить</button>
                            </div>
                        </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $this->endContent(); ?>
