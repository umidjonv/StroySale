<?php

namespace frontend\controllers;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
//use \app\models\Provider;
use frontend\models\Invoice;

class InvoiceController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    
    
    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }
    
    public function actionCurrent()
    {
        //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;               
        return "invoice";        
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
        $model = new \app\models\Invoice();
        if ($model->load(Yii::$app->request->post(), '')) {
            $id = Yii::$app->request->post()['invoiceId'];
            $model = \app\models\Invoice::findOne(['invoiceId'=>$id]);
            
            //$model->providerId = Yii::$app->request->post()['providerId'];
            $model->invoiceDate = Yii::$app->request->post()['invoiceDate'];
            $model->transportType = Yii::$app->request->post()['transportType'];
            $model->description = Yii::$app->request->post()['description'];
            $model->providerId = Yii::$app->request->post()['providerId'];
            $model->save();
            $models = \app\models\Invoice::find()->all();
            // var_dump($model);
            if($isAjax)
            {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;               
                return $model->toArray();
                
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
        $form_model =  new \app\models\Invoice();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        //$form_model->load(\Yii::$app->request->post());
        
        if ($form_model->load(Yii::$app->request->post(), '')) {
            $form_model->invoiceId = Yii::$app->request->post()['invoiceId'];
            $form_model->invoiceDate = Yii::$app->request->post()['invoiceDate'];
            $form_model->transportType = Yii::$app->request->post()['transportType'];
            $form_model->description = Yii::$app->request->post()['description'];
            $form_model->providerId = Yii::$app->request->post()['providerId'];
            $form_model->save();
            $models = \app\models\Invoice::find();
            if($isAjax)
            {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $form_model->toArray();
                
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
            $rowCnt = \app\models\Invoice::deleteAll('invoiceId='.$id);
            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }
         
    }
    
    public function actionRefreshd()
    {
        try{
        $models = \app\models\Invoice::find()->all();
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        //$models
        
        $arr = ArrayHelper::toArray($models, [
                    \app\models\Invoice::class => [
                'invoiceId',
                'invoiceDate',
                'transportType',
                'description',
                'providerId',
                'providerName'=> function ($data) {
                    return $data->provider->name;
                },
            ],
        ]);
            return ['datas' =>$arr];   
        }
        catch(yii\db\Exception $ex)
        {
            return $ex->getMessage();
        }

        
        
        
        //return ['datas' =>$models];
    }
    public function actionInvoiceexes()
    {
        $id = Yii::$app->request->post()['id'];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $invoceExes = \app\models\Invoice::findOne(['invoceId'=>$id])->invoiceExes;
        $models = $invoiceExes->toArray();
        return ['datas' => $models];
    }
    

}
