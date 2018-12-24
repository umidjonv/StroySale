<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use app\components;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
//use \app\models\Provider;
use frontend\models\Clients;


/**
 * Site controller
 */
class ClientsController extends components\BaseController
{
    public $layout = '@app/views/layouts/accounting';
    public function actionIndex()
    {
        
        $models = \app\models\Clients::find();
        //$this->redirect("site/login");
        return $this->render('index', ['models'=>$models]);
    }
    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }
    
    public function actionCurrent()
    {
        //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;               
        return "clients";        
    }
    
    public function actionSave()
    {
        $isAjax = false;
        try{
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;
            
        
        }
        
        $model = new \app\models\Clients();
        if ($model->load(Yii::$app->request->post(), '')) {
            $id = Yii::$app->request->post()['clientId'];
            $model = \app\models\Clients::findOne(['clientId'=>$id]);
            
            $model->clientName =  Yii::$app->request->post()['clientName'];
            $model->inn = Yii::$app->request->post()['inn'];
            $model->bank = Yii::$app->request->post()['bank'];
            $model->address = Yii::$app->request->post()['address'];
            $model->ogrn = Yii::$app->request->post()['ogrn'];
            $model->schet = Yii::$app->request->post()['schet'];
            $model->faktaddress = Yii::$app->request->post()['faktaddress'];
            $model->phone = Yii::$app->request->post()['phone'];
            $model->email = Yii::$app->request->post()['email'];
            $model->response = Yii::$app->request->post()['response'];

            if($model->validate()){
                $model->save();
            }
            $models = \app\models\Clients::find()->all();
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
        $model =  new \app\models\Clients();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        
        //var_dump(Yii::$app->request->post());
        
        if ($model->load(Yii::$app->request->post(), '')) {
            $model->clientId = Yii::$app->request->post()['clientId'];
            $model->clientName = Yii::$app->request->post()['clientName'];
            $model->inn = Yii::$app->request->post()['inn'];
            $model->bank = Yii::$app->request->post()['bank'];
            $model->address = Yii::$app->request->post()['address'];
            $model->ogrn = Yii::$app->request->post()['ogrn'];
            $model->schet = Yii::$app->request->post()['schet'];
            $model->faktaddress = Yii::$app->request->post()['faktaddress'];
            $model->phone = Yii::$app->request->post()['phone'];
            $model->email = Yii::$app->request->post()['email'];
            $model->response = Yii::$app->request->post()['response'];
            if($model->validate()){
                $model->save();
            }
            $models = \app\models\Clients::find();
            if($isAjax)
            {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $model->errors;
                
            }else
            return $this->render('index', ['models'=> $models]);
            
        }
        //var_dump($form_model);
        return $this->render('index', ['model'=> $model]);
    }
    
    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];
        
        try {
            $rowCnt = \app\models\Clients::deleteAll('clientId='.$id);
            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }
         
    }
    
    public function actionRefreshd()
    {
        
        $models = \app\models\Clients::find()->all();
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        return ['datas' =>$models];
    }
    
    
    
}