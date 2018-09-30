<?php

namespace app\modules\sold\controllers;
use app\modules\calc\models\Product;
use app\modules\calc\models\Stuff;
use app\modules\sold\models\Orders;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class OrdersController extends Controller
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
                $model->idType = Yii::$app->request->post()['idType'];
                if($model->idType)
                {
                    $mProduct = Product::find()->where(['productId'=>$model->stuffProdId])->one();
                    $model->orderSumm = $model->packCount * $mProduct->price;
                }
                else
                {
                    $mProduct = Stuff::find()->where(['stuffId'=>$model->stuffProdId])->one();
                    $model->orderSumm = $model->packCount * $mProduct->price;
                }

                //$model->orderSumm = $model->packCount *  ($model->idType==0?$model->product->price:$model->stuffProdId;
                if($model->validate())
                {
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

    public function actionNew()
    {
        $form_model =  new Expense();
        
        //if ($form_model->load(Yii::$app->request->post(), '')) {
        $date = new \DateTime();
        
        
        $form_model->expenseDate = $date->format('Y-m-d H:i:s');
        $form_model->debt = 0;
        $form_model->comment = '';
        $form_model->clientId = null;//Yii::$app->request->post()['clientId'];
        $form_model->fakt = 0;
        $form_model->expType = 0;
        $form_model->transfer = 0;
        $form_model->inCash = 0;
        $form_model->terminal = 0;
        $form_model->expSum = 0;
        $form_model->status = 1;
        $form_model->userId = \Yii::$app->user->id;
        $form_model->paidType = 0;
        $form_model->charge = 0;

        $form_model->save(false);
        //var_dump($form_model);
            
            
        return $this->render('create', ['model'=> $form_model]);

        //}
        
        //return $this->render('create', ['model'=> $form_model]);
    }

    

    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];

        try {
            $rowCnt = Orders::deleteAll('orderId='.$id);
            return $rowCnt;
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
                    if($data->idType==0) {
                        return $data->product->name;
                    }else
                    {
                        return $data->stuff->name;
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
