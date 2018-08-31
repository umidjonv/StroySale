<?php

namespace app\controllers;

class InvoiceController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionRefreshd()
    {
        
        $models = \app\models\Provider::find()->all();
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        return ['datas' =>$models];
    }
    
    

}
