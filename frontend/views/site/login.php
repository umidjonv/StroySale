<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

 <div class="col">
                    <div class="row">
                          <div class="col-3"></div>
                          <div class="col">
                          <img class="card-img-top" src="/images/login_key.png" alt="Card image cap" style="width: 150px;height: 150px;">
                          </div>
                          <div class="col-3"></div>
                          </div>
                    <div class="card" style="align-content: center; text-align: center;">
                      
                      <div class="card-body">
                        <h5 class="card-title">ВХОД</h5>
                        <p class="card-text">
                             <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                              <div class="form-group">
                                
                                <div class="col-sm-12">
                                    <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder'=>"введите ваш логин"])->hint('')->label('') ?>
                                  
                                </div>
                              </div>
                              <div class="form-group">
                                
                                <div class="col-sm-12">
                                    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>"введите ваш пароль"])->label('')->hint('') ?>

                                    <?= $form->field($model, 'rememberMe')->checkbox()->label('запомнить меня')  ?>
                                  
                                </div>


                              </div>
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                             <?php ActiveForm::end(); ?>
                        
                      </div>
                    </div>
                    
</div>

              