<?php

namespace app\modules\calc\controllers;

use app\modules\calc\models\Product;
use app\modules\calc\models\Struct;
use app\modules\calc\models\Stuff;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use app\components;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class StructController extends components\BaseController
{
    /**
     * @inheritdoc
     */
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

    public function actionSave()
    {
        $isAjax = false;
        try{
            if(\Yii::$app->request->isAjax){
                $isAjax = TRUE;

// return 'Запрос принят!';
            }
            //$form_model->load(\Yii::$app->request->post());
            $model = new Struct();
            if ($model->load(Yii::$app->request->post(), '')) {
                $id = Yii::$app->request->post()['structId'];
                $model = Struct::findOne(['structId'=>$id]);

                $model->structId = Yii::$app->request->post()['structId'];
                $model->stuffId = Yii::$app->request->post()['stuffId'];
                $model->stuffProdId = Yii::$app->request->post()['stuffProdId'];
                $model->cnt = Yii::$app->request->post()['cnt'];
                $model->idType = Yii::$app->request->post()['idType'];
                if($model->validate()){
                    $model->save();
                }
                $models = Struct::find()->all();
                // var_dump($model);
                if($isAjax)
                {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return $model->errors;

                }else
                    return $this->render('index', ['models'=> $models]);

            }
        }catch(\yii\db\Exception $ex)
        {
            echo $ex->getMessage();
        }
        //var_dump($form_model);
        //return $this->render('index', ['model'=> $model]);
    }

    public function actionNew()
    {
        $isAjax = false;
        $form_model =  new Struct();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        //$form_model->load(\Yii::$app->request->post());

        if ($form_model->load(Yii::$app->request->post(), '')) {
            $form_model->structId = Yii::$app->request->post()['structId'];
            $form_model->stuffId = Yii::$app->request->post()['stuffId'];
            $form_model->stuffProdId = Yii::$app->request->post()['stuffProdId'];
            $form_model->cnt = Yii::$app->request->post()['cnt'];
            $form_model->idType = Yii::$app->request->post()['idType'];
            if($form_model->validate()){
                $form_model->save();
            }
            $models = Struct::find();
            if($isAjax)
            {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $form_model->errors;

            }else
                return $this->render('index', ['models'=> $models]);

        }
        //var_dump($form_model);
        return $this->render('index', ['model'=> $form_model]);
    }

    public function actionRefresh()
    {

        $models = Struct::find()->where(['stuffId'=> Yii::$app->request->post("id")])->all();
        try {
            \Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $arr=ArrayHelper::toArray($models, [
                Struct::class=>[
                    'structId',
                    'stuffProdId',
                    'prodName'=>function ($data) {
                        return ($data->idType == 0) ? $data->stuffProd->name : $data->stuffStuff->name;
                    },
                    'measure'=>function ($data) {
                        return ($data->idType == 0) ? $data->stuffProd->measure->name : $data->stuffStuff->measure->name;
                    },
                    'cnt',
                    'idType',
                ],
            ]);
        }
        catch (Exception $ex){
            echo $ex->getMessage();
        }
        return $this->asJson($arr);
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];

        try {
            $rowCnt = Struct::deleteAll('structId='.$id);
            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function actionRefreshProdType(){
        $list = array();
        try {
            if (Yii::$app->request->post("val") == "1") {
                $list=\yii\helpers\ArrayHelper::map(Stuff::find()->all(), 'stuffId', 'name');
            } else {
                $list=\yii\helpers\ArrayHelper::map(Product::find()->all(), 'productId', 'name');
            }
        }
        catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }
        return $this->asJson($list);
    }
}
