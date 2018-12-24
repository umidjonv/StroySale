<?php

namespace frontend\controllers;
use app\components;
use Yii;
use app\models\Balance;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class BalanceController extends components\BaseController
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionRefreshd(){

        try{
            $models = Balance::find()->all();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            //$this->redirect("site/login");
            //$models

            $arr = ArrayHelper::toArray($models, [
                Balance::class => [
                    'stuffProdId',
                    'cnt',
                    'prodName'=>function ($data) {
                        return ($data->idType == 0) ? $data->stuffProd->name : $data->stuffStuff->name;
                    },
                    'measure'=>function ($data) {
                        return ($data->idType == 0) ? $data->stuffProd->measure->name : $data->stuffStuff->measure->name;
                    },
                    'idType',
                ],
            ]);
            return ['datas' =>$arr];
        }
        catch(yii\db\Exception $ex)
        {
            return $ex->getMessage();
        }
    }

}