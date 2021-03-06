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
$('#costForm').on('submit', function(){
        var stuffId = $("#formID").val();
        var formId = $('#structID').val();
        var url1 = "/accounting/account/new";
        var data = $(this).serialize();
        $.ajax({
            url: url1,
            type: 'POST',
            data: data+"&_csrf="+yii.getCsrfToken(),
            success: function(res){       
                $("#uslugiWindow").modal("hide");
                $("#costForm")[0].reset();
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
<div class="row">
    <div class="col">
        <input type="text" class="form-control" id="searchInput" placeholder="Поиск">

    </div>
    <div class="col-2">
        <a href="javascript:;" class="btn btn-info" data-toggle="modal", data-target="#uslugiWindow">добавить услугу</a>
    </div>

</div>
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
                        <div class="col-sm-12">
                            <form action="" id="costForm"  method="POST" >
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2">Номер накладной</label>
                            <div class="col-sm-5">
                                <input type="text" step="any" class="form-control" placeholder="Номер накладной" name="expenseId" autocomplete="off">
                            </div> или  
                            <select name="clientId" id="clientList">
                                <? foreach (\app\models\Clients::find()->all() as $item){?>
                                    <option value="<?=$item->clientId ?>"><?=$item->clientName ?></option>
                                <?}?>
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-2">Комментарий</label>
                            <div class="col-sm-5">
                                <input type="text" step="any" class="form-control" placeholder="Комментарий" name="comment" autocomplete="off">
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

<?php $this->endContent(); ?>
