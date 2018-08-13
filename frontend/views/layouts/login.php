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
            <div class="row main">
            <div class="col-4"></div>
                
                <div class="col">
                    <div class="row">
                          <div class="col-3"></div>
                          <div class="col">
                          <img class="card-img-top" src="./images/login_key.png" alt="Card image cap" style="width: 150px;height: 150px;">
                          </div>
                          <div class="col-3"></div>
                          </div>
                    <div class="card" style="align-content: center; text-align: center;">
                      
                      <div class="card-body">
                        <h5 class="card-title">ВХОД</h5>
                        <p class="card-text">
                            <form class="">
                              <div class="form-group">
                                
                                <div class="col-sm-12">
                                  <input type="text"  class="form-control" id="loginInput" placeholder="введите ваш логин">
                                </div>
                              </div>
                              <div class="form-group">
                                
                                <div class="col-sm-12">
                                  <input type="text" class="form-control" id="inputAdress" placeholder="введите пароль">
                                </div>


                              </div>
                            </form>
                        <a href="#" class="btn btn-primary">ВОЙТИ</a>
                      </div>
                    </div>
                    


                </div>
                <div class="col-4"></div>
            </div>
        </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>