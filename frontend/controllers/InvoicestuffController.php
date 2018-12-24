<?php

namespace frontend\controllers;
use app\models\Balance;
use app\models\InvoiceExStuff;
use Yii;
use app\modules\calc\models\Stuff;
use app\components;
use yii\helpers\ArrayHelper;;

class InvoicestuffController extends components\BaseController
{
    public function actionIndex()
    {
        $modelProduct = Stuff::find()->all();
        return $this->render('index',array(
            'mProduct' => $modelProduct
        ));
    }



    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionCurrent()
    {
        //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return "invoicestuff";
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
        $model = new \app\models\InvoiceStuff();
        if ($model->load(Yii::$app->request->post(), '')) {
            $id = Yii::$app->request->post()['invoiceSuffId'];
            $model = \app\models\InvoiceStuff::findOne(['invoiceStuffId'=>$id]);

            $model->invoiceDate = date("Y-m-d H:i:s");
            $model->description = Yii::$app->request->post()['description'];
            if($model->validate()){
                $model->save();
            }
            $models = \app\models\InvoiceStuff::find()->all();
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
        $form_model =  new \app\models\InvoiceStuff();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        //$form_model->load(\Yii::$app->request->post());

        if ($form_model->load(Yii::$app->request->post(), '')) {
            $form_model->invoiceStuffId = Yii::$app->request->post()['invoiceStuffId'];
            $form_model->invoiceDate = date("Y-m-d H:i:s");
            $form_model->description = Yii::$app->request->post()['description'];
            if($form_model->validate()){
                $form_model->save();
                $model = new InvoiceExStuff();
                $model->invoiceStuffId = $form_model->invoiceStuffId;
                $model->cnt = Yii::$app->request->post()['cnt'];
                $model->stuffId = Yii::$app->request->post()['stuffProdId'];
                $model->save();
            };
            $models = \app\models\InvoiceStuff::find();
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

    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];

        try {
            $balance = new Balance();
            $model = \app\models\InvoiceExStuff::findAll('invoiceStuffId='.$id);
            foreach ($model as $val) {
                $balance->removeFromBalance($val->stuffId,2,$val->cnt);
            }
            \app\models\InvoiceExStuff::deleteAll('invoiceStuffId='.$id);
            $rowCnt = \app\models\InvoiceStuff::deleteAll('invoiceStuffId='.$id);
            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function actionRefreshd()
    {
        try{
            $model = Yii::$app->db->createCommand("select i.invoiceStuffId,i.description,i.invoiceDate,s.name,ie.cnt,m.name as mName from invoiceExStuff ie
INNER JOIN invoiceStuff i on i.invoiceStuffId = ie.invoiceStuffId
INNER JOIN stuff s on s.stuffId = ie.stuffId
INNER JOIN measure m on s.measureId = m.measureId")->queryAll();
//        $models = \app\models\InvoiceStuff::find()->all();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        //$models

//        $arr = ArrayHelper::toArray($models, [
//                    \app\models\InvoiceStuff::class => [
//                'invoiceStuffId',
//                'invoiceDate',
//                'description',
//            ],
//        ]);
            return ['datas' =>$model];
        }
        catch(yii\db\Exception $ex)
        {
            return $ex->getMessage();
        }




        //return ['datas' =>$models];
    }
    public function actionInvoiceexes()
    {
        try{
        $id = Yii::$app->request->post()['id'];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $invoiceExes = \app\models\InvoiceStuff::findOne(['invoiceStuffId'=>$id])->invoiceExStuffs;
        $arr = ArrayHelper::toArray($invoiceExes, [
                    \app\models\InvoiceExStuff::class => [
                'invoiceExStuffId',
                'stuffId',
                'cnt',
                'invoiceStuffId',
                'productName'=> function ($data) {
                    return $data->stuff->name;
                },
            ],
        ]);
            return ['datas' =>$arr];
        //return ['datas'=>$invoceExes];
        }
        catch(yii\db\Exception $ex)
        {
            return $ex->getMessage();
        }


        //return $invoceExes->toArray();
//$models = $invoiceExes->toArray();
        //return ['datas' => $models];
    }
    

}
