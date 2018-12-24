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


        <div class="row">

            <div class="col">
                <h3 class="alert">Продажа строительных изделий</h3>
                <nav class="navbar navbar-expand-lg navbar-dark bg-info">
                    <a class="navbar-brand" href="#">StroySale</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDropdown" >
                        <ul class="navbar-nav  mr-auto">
                            <li class="nav-item"><a href="/clients" class="nav-link">Клиенты</a></li>
                            <li class="nav-item"><div class="dropdown"><a href="#" role="button" class="nav-link  dropdown-toggle" id="dropdownProduct" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Продукция</a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownProduct">
                                        <a class="dropdown-item" href="/calc/product">Продукты</a>
                                        <a class="dropdown-item" href="/calc/measure">Ед.Изм.</a>
                                        <a class="dropdown-item" href="/calc/category">Категории</a>
                                        <a class="dropdown-item " href="/calc/stuff">Рецепты</a>
                                        <a class="dropdown-item" href="/calc/default/add">Паспортные данные</a>
                                        <a class="dropdown-item " href="/calc/default/passport">Паспорт</a>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item active">
                                <div class="dropdown"><a href="#" role="button" class="nav-link  dropdown-toggle" id="dropdownInvoice" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Приходование</a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownInvoice">

                                        <a class="dropdown-item" href="/invoice">Приходовать товар</a>
                                        <a class="dropdown-item" href="/invoicestuff">Приходовать продукцию</a>


                                    </div>
                                </div>


                            </li>
                            <li class="nav-item"><a href="/balance" class="nav-link">Баланс</a></li>
                            <li class="nav-item"><div class="dropdown"><a href="#" role="button" class="nav-link  dropdown-toggle" id="dropdownExpense" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Продажа</a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownExpense">
                                        <a class="dropdown-item" href="/sold/expense/step1">Новый заказ</a>
                                        <a class="dropdown-item" href="/sold/expense">Данные по продажам (Проданные)</a>
                                        <a class="dropdown-item" href="/sold/expense/inprocesslist">Данные по продажам (не закрытые, в процессе)</a>
                                        <a class="dropdown-item" href="/sold/expense/refreshdbyclients">Фильтр по товарам и клиенту</a>

                                    </div>
                                </div>
                            </li>
                            <li class="nav-item"><div class="dropdown"><a href="#" role="button" class="nav-link  dropdown-toggle" id="dropdownExpense" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Услуги</a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownExpense">
                                        <a class="dropdown-item" href="/accounting/report/costs">Расходы услуг по дате</a>
                                        <a class="dropdown-item" href="/accounting/report/coming">Поступление по дате</a>
                                        <a class="dropdown-item" href="/accounting/report/balance">Поступления и расходы</a>

                                    </div>
                                </div>
                            </li>

                            <li class="nav-item"><div class="dropdown"><a href="#" role="button" class="nav-link  dropdown-toggle" id="dropdownExpense" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Отчеты</a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownExpense">
                                        <a class="dropdown-item" href="/reports/report/weekcountreport">Недельный отчет по приходу/уходу товаров</a>
                                        <a class="dropdown-item" href="/reports/report/clientreport">Отчет по клиенту</a>
                                        
                                    </div>
                                </div>
                            </li>



                        </ul>
                    </div>
                    <div class="collapse navbar-collapse" >
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item nav">
                                <?php if (!Yii::$app->user->isGuest)
                                {
                                    echo Html::beginForm(['/site/logout'], 'post')
                                        . Html::submitButton(
                                            '<span class="oi oi-account-logout"></span> ' . Yii::$app->user->identity->username . '',
                                            ['class' => 'btn btn-info']
                                        )
                                        .''
                                        . Html::endForm();
                                } ?>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    <div class="row main">
        
        
        <?php if (!Yii::$app->user->isGuest):?> 
        
        
        
        <?php else: ?>
        <div class="container">
        <?php endif; ?>
        <div class="col">
            <?php if (!Yii::$app->user->isGuest):?> 

            <?php endif; ?>
            
            <?= $content ?>
        
        </div>
            
            <?php if (Yii::$app->user->isGuest): ?>
            </div>
            <?php endif; ?>
            </div>


    <?php $this->endBody() ?>
<?php $this->endPage() ?>