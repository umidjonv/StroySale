<?php
namespace frontend\controllers;
use app\models\Balance;
use app\models\InvoiceEx;
use app\models\InvoiceExStuff;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use app\components;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
//use \app\models\Provider;

class InvoiceexstuffController extends components\BaseController
{
    public function actionIndex()
    {
        $balance = new Balance();
//        $balance->removeStuffProd(5,3.3);
        return $this->render('../invoiceEx/index');
    }
    
    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }
    
    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];
        try {
            $cnt = InvoiceExStuff::findOne(['invoiceExStuffId'=>$id]);
            $rowCnt = \app\models\InvoiceExStuff::deleteAll('invoiceExStuffId='.$id);
            $balance = new Balance();
            $balance->addStuffProd($cnt->stuffId,$cnt->cnt);

            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }
         
    }
    
    public function actionNew()
    {
        $isAjax = false;
        $form_model =  new InvoiceExStuff();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        //$form_model->load(\Yii::$app->request->post());

        if ($form_model->load(Yii::$app->request->post(), '')) {
            $form_model->stuffId = Yii::$app->request->post()['stuffProdId'];
            $form_model->cnt = Yii::$app->request->post()['cnt'];
            $form_model->invoiceStuffId = Yii::$app->request->post()['id'];
            if($form_model->validate()){
                $form_model->save();

                $balance = new Balance();
                $balance->removeStuffProd(Yii::$app->request->post()['stuffProdId'],Yii::$app->request->post()['cnt']);
            }
            $models = \app\models\InvoiceExStuff::find();
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
    

}
