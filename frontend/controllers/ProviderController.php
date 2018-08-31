<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
//use \app\models\Provider;
use frontend\models\Provider;


/**
 * Site controller
 */
class ProviderController extends Controller
{
    public function actionIndex()
    {
        
        $models = \app\models\Provider::find();
        //$this->redirect("site/login");
        return $this->render('index', ['models'=>$models]);
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
        $model = new \app\models\Provider();
        if ($model->load(Yii::$app->request->post(), '')) {
            $id = Yii::$app->request->post()['providerId'];
            $model = \app\models\Provider::findOne(['providerId'=>$id]);
            
            //$model->providerId = Yii::$app->request->post()['providerId'];
            $model->name = Yii::$app->request->post()['name'];
            $model->address = Yii::$app->request->post()['address'];
            $model->save();
            $models = \app\models\Provider::find()->all();
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
        $form_model =  new \app\models\Provider();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        //$form_model->load(\Yii::$app->request->post());
        
        if ($form_model->load(Yii::$app->request->post(), '')) {
            $form_model->providerId = Yii::$app->request->post()['providerId'];
            $form_model->name = Yii::$app->request->post()['name'];
            $form_model->address = Yii::$app->request->post()['address'];
            $form_model->save();
            $models = \app\models\Provider::find();
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
            $rowCnt = \app\models\Provider::deleteAll('providerId='.$id);
            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }
         
    }
    
    public function actionRefreshd()
    {
        
        $models = \app\models\Provider::find()->all();
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        return ['datas' =>$models];
    }
    
    
    
}