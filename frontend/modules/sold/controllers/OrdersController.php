<?php

namespace app\modules\sold\controllers;
use app\components\BaseController;
use app\models\Balance;
use app\modules\calc\models\Product;
use app\modules\calc\models\Stuff;
use app\modules\sold\models\Delivery;
use app\modules\sold\models\Expense;
use app\modules\sold\models\Orders;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class OrdersController extends BaseController
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
    
    public function actionIndex()
    {
        
        $models = Orders::find();
        //$this->redirect("site/login");
        return $this->render('index', ['models'=>$models]);
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    


    public function actionSave()
    {
        $isAjax = false;
        try{
            if(\Yii::$app->request->isAjax){
                $isAjax = TRUE;


            }
            $session = \Yii::$app->session;
            $model = new Orders();
            //var_dump(Yii::$app->request->post());
            if ($model->load(Yii::$app->request->post(), '')) {
                $model->orderId = 0;
                $model->expenseId = $session->get('expenseId');
                $model->stuffProdId = Yii::$app->request->post()['stuffProdId'];
                $model->packCount = Yii::$app->request->post()['packCount'];
                $model->faktCount = $model->packCount;
                $model->addition = Yii::$app->request->post()['addition'];
                $model->additionCnt = Yii::$app->request->post()['additionCnt'];
                


                $model->idType = Yii::$app->request->post()['idType'];
                $mProduct = new Product();
                $mStuff = new Stuff();
                if($model->idType==0)
                {
                    if(Yii::$app->request->post()['addition'] != 0) {
                        $mProduct = Product::find()->where(['productId' => $model->stuffProdId])->one();
                        $addition = Product::find()->where(['productId' => $model->addition])->one();
                        $model->orderSumm = $model->packCount * ($mProduct->price + Yii::$app->request->post()["additionCnt"]*$addition->price);
                    }
                    else{
                        $mProduct = Product::find()->where(['productId' => $model->stuffProdId])->one();
                        $model->orderSumm = $model->packCount * $mProduct->price;
                    }
                }
                else
                {
                    if(Yii::$app->request->post()['addition'] != 0) {
                        $mStuff = Stuff::find()->where(['stuffId' => $model->stuffProdId])->one();
                        $addition = Product::find()->where(['productId' => $model->addition])->one();
                        $model->orderSumm = $model->packCount * ($mStuff->price + Yii::$app->request->post()["additionCnt"]*$addition->price);
                    }
                    else{
                        $mStuff = Stuff::find()->where(['stuffId' => $model->stuffProdId])->one();
                        $model->orderSumm = $model->packCount * $mStuff->price;
                    }
                }

                //$model->orderSumm = $model->packCount *  ($model->idType==0?$model->product->price:$model->stuffProdId;
                if($model->validate())
                {
                    $modelBalance = new Balance();
                    $modelBalance->removeFromBalance($model->stuffProdId,$model->idType, $model->faktCount);
                    $model->save();
                    return $this->actionRefreshd($model->expenseId);
                }else
                {
                    return var_dump($model->errorSummary());
                }



            }
        }catch(\yii\db\Exception $ex)
        {
            echo $ex->getMessage();
        }
        //var_dump($form_model);
        //return $this->render('index', ['model'=> $model]);
    }

    public function actionSavelist($id)
    {
        $model = new Orders();
        $model = Orders::find()->where(['orderId'=>$id])->one();

        $mainForm = null;
        //return var_dump(Yii::$app->request->post());
        if(isset(Yii::$app->request->post()['mainform'])) {
            $mainForm = Yii::$app->request->post()['mainform'];
            $deliveryPrice = '';
            if(isset(Yii::$app->request->post()['form2']))
            {

                $form2 = Yii::$app->request->post()['form2'];

                $deliveryPrice = $form2['deliveryPrice'];
                $modelDelivery = Delivery::find()->where(['expenseId'=>$model->expenseId])->one();


                $modelDelivery->price = $deliveryPrice;

                if($modelDelivery->validate())
                {
                    $modelDelivery->save();



                } else {
                    return var_dump($modelDelivery->errors);
                }
               // return 'sav2';
            }



            $cena = $mainForm['newCena'];
            $fakt = $mainForm['fakt'];
            $pack = $mainForm['packCount'];
            //$expSum = $mainForm['expSum'];

            $ostatok = 0;
            if ($model->faktCount != $fakt)
                $ostatok = $model->faktCount - $fakt;

            $model->orderSumm = $cena * $pack;

            $model->faktCount = $fakt;
            if ($model->validate()) {
                if ($ostatok > 0) {
                    $modelBalance = new Balance();
                    if ($fakt > 0)
                        $modelBalance->addToBalance($model->stuffProdId, $model->idType, $ostatok);
                    else if ($fakt < 0) {
                        $modelBalance->removeFromBalance($model->stuffProdId, $model->idType, $ostatok);
                    }
                }
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                //$modelEx = Expense::find()->where(['expenseId'=>$id])->one();
                //$modelEx->expSum = $expSum;
                //$modelEx->save();
                $model->save();
                $modelEx = Expense::find()->where(['expenseId'=>$model->expenseId])->one();
                $expSumm = 0;
                foreach ($modelEx->orders as $order)
                {
                    $expSumm += $order->orderSumm;
                }
                $expSumm += $modelDelivery->price;
                //$modelEx->expsum;
                $modelEx->expSum = $expSumm;

                $modelEx->save(false);
                return [ 'status'=>'OK' , 'expSum'=> $expSumm ];
            } else {
                return var_dump($model->errors);
            }
        }



    }

    public function actionList($id)
    {
        $expenseModel = Expense::find()->where(['expenseId'=>$id])->one();

        return $this->render('list', ['expenseId'=>$id, 'exModel'=>$expenseModel]);
    }

    

    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];

        try {

            $modelOrders = Orders::find()->where(['orderId'=>$id])->one();
            //$rowCnt = Orders::deleteAll('orderId='.$id);

            $modelBalance = new Balance();
            $modelBalance->addToBalance($modelOrders->stuffProdId,$modelOrders->idType, $modelOrders->faktCount);
            $modelOrders->delete();

            return 1;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }

    }
    public function actionRefreshd($id)
    {

        $models = Orders::find()->where(['expenseId'=>$id])->all();
        
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        $arr = ArrayHelper::toArray($models,[
                    Orders::class =>[
                'orderId',
                'expenseId',
                'stuffProdId',
                'packCount',
                'faktCount',
                'orderSumm',
                'idType',
                'productName'=>function($data){
                    $addition = " ";
                    if($data->addition != 0){
                        $prod = Product::findOne(["productId"=>$data->addition]);
                        $addition .= "c ".$prod->name."-".$data->additionCnt;
                    }
                    if($data->idType==0) {
                        return $data->product->name.$addition;
                    }else
                    {
                        return $data->stuff->name.$addition;
                    }
                },
                //'category'=>function($data){
                //    return $data->category->name;
                //},
            ],

        ]);
        return ['datas' =>$arr];
    }
}
