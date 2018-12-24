<?php
namespace frontend\controllers;
use app\models\Balance;
use app\models\InvoiceEx;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use app\components;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
//use \app\models\Provider;

class InvoiceexController extends components\BaseController
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
        try {
            $cnt = InvoiceEx::findOne(['invoiceExId'=>$id]);
            $rowCnt = \app\models\InvoiceEx::deleteAll('invoiceExId='.$id);
            $balance = new Balance();
            $balance->removeFromBalance($cnt->productId,0,$cnt->cnt);

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
            $form_model->productId = Yii::$app->request->post()['stuffProdId'];
            $form_model->cnt = Yii::$app->request->post()['cnt'];
            $form_model->invoiceId = Yii::$app->request->post()['id'];
            if($form_model->validate()){
                $form_model->save();

                $balance = new Balance();
                $balance->addToBalance(Yii::$app->request->post()['stuffProdId'],0,Yii::$app->request->post()['cnt']);
            }
            $models = \app\models\InvoiceEx::find();
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
