<?php

namespace frontend\controllers;
use app\models\Balance;
use app\models\InvoiceEx;
use app\modules\accounting\models\Account;
use app\modules\accounting\models\Balancesum;
use Yii;
use app\modules\calc\models\Product;
use app\components;
use yii\helpers\ArrayHelper;
use frontend\models\Invoice;

class InvoiceController extends components\BaseController
{
    public function actionIndex()
    {
        $modelProduct = Product::find()->all();
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
        return "invoice";
    }

    
    public function actionNew()
    {
        $isAjax = false;
        $form_model =  new \app\models\Invoice();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        //$form_model->load(\Yii::$app->request->post());
//        echo "<pre>";
//        print_r(Yii::$app->request->post());
//        echo "</pre>";
        if ($form_model->load(Yii::$app->request->post(), '')) {
            $form_model->invoiceId = Yii::$app->request->post()['invoiceId'];
            $form_model->invoiceDate = date("Y-m-d H:i:s");;
            $form_model->deliveryDate = Yii::$app->request->post()['deliveryDate'];
            $form_model->transportType = Yii::$app->request->post()['transportType'];
            $form_model->description = Yii::$app->request->post()['description'];
            $form_model->providerId = Yii::$app->request->post()['clientId'];
            $form_model->driver = Yii::$app->request->post()['driver'];
            $form_model->phone = Yii::$app->request->post()['phone'];
            $form_model->carNumber = Yii::$app->request->post()['carNumber'];
            $form_model->invoiceSumm = Yii::$app->request->post()['invoiceSum'];
            $form_model->expNum = Yii::$app->request->post()['expNum'];
            if($form_model->validate()){
                $form_model->save();
                $account = new Account();
                $account->addClientSum(Yii::$app->request->post()['clientId'],Yii::$app->request->post()['invoiceSum']*(-1));
                $model = new InvoiceEx();
                $model->invoiceId = $form_model->invoiceId;
                $model->cnt = Yii::$app->request->post()['cnt'];
                $model->productId = Yii::$app->request->post()['stuffProdId'];
                $model->invoiceExSum = Yii::$app->request->post()['cnt']*Yii::$app->request->post()['sum'];
                $model->save();
                $balance = new Balance();
                $balance->addToBalance(Yii::$app->request->post()['stuffProdId'],0,Yii::$app->request->post()['cnt']);
            };
            $models = \app\models\Invoice::find();
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
            $model = \app\models\Invoice::findOne(['invoiceId'=>$id]);
            $account = new Account();
            $account->addClientSum($model->providerId,$model->invoiceSumm);

            $balance = new Balance();
            $cnt = InvoiceEx::findAll(['invoiceId'=>$id]);
            foreach ($cnt as $item) {
                \app\models\InvoiceEx::deleteAll('invoiceExId='.$item->invoiceExId);
                $balance->removeFromBalance($item->productId,0,$item->cnt);
            }
            $rowCnt = \app\models\Invoice::deleteAll('invoiceId='.$id);
            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }
         
    }
    
    public function actionRefreshd()
    {
        try{

            $model = Yii::$app->db->createCommand("select m.name as mName,i.expNum,i.deliveryDate,i.carNumber, ie.cnt,ie.invoiceId, ie.productId, i.description,i.invoiceDate,i.transportType, p.name,cl.clientName,i.invoiceSumm,i.driver,i.phone from invoiceEx ie
INNER JOIN invoice i on i.invoiceId = ie.invoiceId
INNER JOIN product p on p.productId = ie.productId
INNER JOIN measure m on p.measureId = m.measureId
INNER JOIN clients cl on cl.clientId = i.providerId")->queryAll();
//        $models = \app\models\InvoiceEx::find()->all()->invoice;
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        //$models
        
//        $arr = ArrayHelper::toArray($model, [
//                    \app\models\Invoice::class => [
//                'invoiceId',
//                'invoiceDate',
//                'deliveryDate',
//                'transportType',
//                'description',
//                'providerId',
//                'providerName'=> function ($data) {
//                    return $data->provider->clientName;
//                },
//                'product' => function($data){
//                    return $data->invoiceExes->product->name;
//                }
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
        
        
        $invoiceExes = \app\models\Invoice::findOne(['invoiceId'=>$id])->invoiceExes;
        $arr = ArrayHelper::toArray($invoiceExes, [
                    \app\models\InvoiceEx::class => [
                'invoiceExId',
                'productId',
                'cnt',
                'invoiceId',
                'productName'=> function ($data) {
                    return $data->product->name;
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
