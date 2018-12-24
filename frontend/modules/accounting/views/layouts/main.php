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
<?php $this->beginPage() ?>
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
                $("#modalWindow").modal("hide");
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
    <!DOCTYPE html>
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title
            ) ?></title>
        <?php $this->head() ?>
    </head>

<?php $this->beginBody() ?>


    <div class="row main">


        <?php if (!Yii::$app->user->isGuest):?>


        <div class="container">
            <h3 class="alert">Stroy Sale</h3>
            <?php endif; ?>
            <div class="col">
                <?php if (!Yii::$app->user->isGuest):?>
                    <ul class="nav justify-content-end">
                        <li class="nav-item"><?php echo Html::beginForm(['/site/logout'], 'post')
                                . Html::submitButton(
                                    'Выйти (' . Yii::$app->user->identity->username . ')',
                                    ['class' => 'btn btn-info']
                                )
                                .'<span class="oi oi-account-logout"></span>'
                                . Html::endForm()?> </li>
                    </ul>
                <?php endif; ?>
                <? echo \yii\widgets\Menu::widget([
                    'activateItems' => true,
                    'activeCssClass' => 'active',
                    'items' => [
                        // Important: you need to specify url as 'controller/action',
                        // not just as 'controller' even if default action is used.
                        ['label' => 'Расход по дате', 'url' => ['/accounting/report/costs'],'options'=>['class'=>'nav-item']],
                        ['label' => 'Приход по дате', 'url' => ['/accounting/report/coming'],'options'=>['class'=>'nav-item']],
                        ['label' => 'Остаток по дате', 'url' => ['/accounting/report/balance'],'options'=>['class'=>'nav-item']],
                        ['label' => 'Дабавить', 'url' => '#','options'=>['class'=>'nav-item',"data-toggle"=>"modal", "data-target"=>".bd-example-modal-lg"]],
                    ],
                    'options' => [
                        'class'=>"nav nav-tabs",
                    ],
                    'linkTemplate' => '<a href="{url}" class="nav-link">{label}</a>',

                ]);?>
                <?= $content ?>

            </div>

            <?php if (!Yii::$app->user->isGuest): ?>
        </div>
    <?php endif; ?>
    </div>
<?php $this->endBody() ?>
<?php $this->endPage() ?>
<div id="modalWindow" class="modal fade bd-example-modal-lg col-lg-12" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                                </div>
                                или
                                <select name="clientId" id="clientList">
                                    <? foreach (\app\models\Clients::findAll() as $item){?>
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
</div>

