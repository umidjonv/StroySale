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
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    
    <?php $this->beginBody() ?>
    <div class="container">
    <div class="row">
        
        <div class="col">
            <h3 class="alert">Stroy Sale - продажа строительных изделий</h3>
            <ul class="nav">
                <li class="nav-item"><a href="/provider" class="nav-link active">Поставщики</a></li>
                <li class="nav-item"><a href="/invoice" class="nav-link">Приход товаров</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Баланс</a></li>
                <li class="nav-item"><div class="dropdown"><a href="#" role="button" class="nav-link  dropdown-toggle" id="dropdownProduct" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Продукция</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownProduct">
                      <a class="dropdown-item" href="/calc/product">Продукты</a>
                      <a class="dropdown-item" href="/calc/measure">Ед.Изм.</a>
                      <a class="dropdown-item" href="/calc/category">Категории</a>
                      <a class="dropdown-item " href="/calc/stuff">Рецепты</a>
                    </div>
                  </div>
                </li>
            </ul>
            
        </div>
    </div>
    <div class="row main">
        
        
        <?php if (!Yii::$app->user->isGuest):?> 
        
        
        
        <?php else: ?>
        <div class="container">
        <?php endif; ?>
        <div class="col">
            <?php if (!Yii::$app->user->isGuest):?> 
                <ul class="nav justify-content-end">
                        <li class="nav-item"><?php echo Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Выйти (' . Yii::$app->user->identity->username . ')<span class="oi oi-account-logout"></span>',
                        ['class' => 'btn btn-info']
                    )
                    .''
                    . Html::endForm()?> </li>
                        </ul>
            <?php endif; ?>
            
            <?= $content ?>
        
        </div>
            
            <?php if (Yii::$app->user->isGuest): ?>
            </div>
            <?php endif; ?>
            </div>
    </div>
    <?php $this->endBody() ?>
<?php $this->endPage() ?>