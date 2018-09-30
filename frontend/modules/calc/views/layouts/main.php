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
    <!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title
            ) ?></title>
        <?php $this->head() ?>
    </head>
<body>

<?php $this->beginBody() ?>


    <div class="row main">


        <?php if (!Yii::$app->user->isGuest):?>


            <div class="col-3">
                <h3 class="alert">Stroy Sale</h3>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="/provider" class="nav-link active">Поставщики</a></li>
                    <li class="nav-item"><a href="/invoice" class="nav-link">Приход товаров</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Баланс</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Provider4</a></li>
                    <li class="nav-item"><a href="/calc" class="nav-link">Калькуляция</a></li>
                </ul>
            </div>
        <?php else: ?>
        <div class="container">
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
                <div class="col-sm-12">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/calc/product">Продукты</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/calc/measure">Ед.Изм.</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/calc/category">Категории</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="/calc/stuff">Продукция</a>
                        </li>
                    </ul>
                </div>
                <?= $content ?>

            </div>

            <?php if (Yii::$app->user->isGuest): ?>
        </div>
    <?php endif; ?>
    </div>
<?php $this->endBody() ?>
<?php $this->endPage() ?>