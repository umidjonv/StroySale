<?php

/* @var $this yii\web\View */

$this->title = 'StroySale';
?>

    <?php if (Yii::$app->user->isGuest):?> 
<div class="row">
    <div class="col">
         
        <h1> Главная страница (экран приветствия) </h1>
            <a href="/site/login" class="btn btn-info">Авторизация</a>
            </div>
        </div>
    <?php else:?>
            <h1> Отображается малая статистика</h1>
            <?php endif;?>