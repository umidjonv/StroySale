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
use frontend\models\invoiceEx;

class InvoiceexController extends \yii\web\Controller
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
    
    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];
        //return "Ola";
        try {
            $rowCnt = \app\models\InvoiceEx::deleteAll('invoiceExId='.$id);
            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }
         
    }
    
    public function actionNew()
    {
        $isAjax = false;
        $form_model =  new InvoiceEx();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        //$form_model->load(\Yii::$app->request->post());

        if ($form_model->load(Yii::$app->request->post(), '')) {
            $form_model->stuffId = Yii::$app->request->post()['stuffId'];
            $form_model->name = Yii::$app->request->post()['name'];
            $form_model->measureId = Yii::$app->request->post()['measureId'];
            $form_model->salary = Yii::$app->request->post()['salary'];
            $form_model->energy = Yii::$app->request->post()['energy'];
            $form_model->save();
            $models = Stuff::find();
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
    

}
