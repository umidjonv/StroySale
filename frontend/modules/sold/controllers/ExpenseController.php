<?php

namespace app\modules\sold\controllers;

use app\modules\sold\models\Expense;
use app\modules\calc\models\Product;
use app\modules\calc\models\Stuff;
use app\models\Clients;
use app\modules\sold\models\Orders;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ExpenseController extends Controller
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
        
        return $this->render('index');
    }

    /**
     * Lists all Product models.
     * @return mixed
     
    


    public function actionSave()
    {
        $isAjax = false;
        try{
            if(\Yii::$app->request->isAjax){
                $isAjax = TRUE;

// return 'Запрос принят!';
            }
            //$form_model->load(\Yii::$app->request->post());
            $model = new Product();
            if ($model->load(Yii::$app->request->post(), '')) {
                $id = Yii::$app->request->post()['productId'];
                $model = Product::findOne(['productId'=>$id]);

                $model->productId = Yii::$app->request->post()['productId'];
                $model->name = Yii::$app->request->post()['name'];
                $model->measureId = Yii::$app->request->post()['measureId'];
                $model->categoryId = Yii::$app->request->post()['categoryId'];
                $model->save();
                $models = Product::find()->all();
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
    */
    public function actionStep1()
    {
        
        $session = \Yii::$app->session;
 
        // проверяем наличие открытой сессии
        if ($session->isActive)
        {    
            $session->open();
        }

        $form_model =  new Expense();
        $modelProduct = new Product();
        $modelStuff = new Stuff();
        //if ($form_model->load(Yii::$app->request->post(), '')) {
        $date = new \DateTime();
        if(!isset($session['expenseId']))
        {
            
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
            
            $session->set("expenseId", $form_model->expenseId);
            $session->set("step", 1);
        //var_dump($form_model);
        } else {
            $expId = $session->get("expenseId");
            $form_model = Expense::find()->where(['expenseId'=>$expId])->orderBy(['expenseId'=>SORT_DESC])->one();
            $session->set("step", 1);
        
            //return print_r($form_model);
        }   
        
        $modelProduct = Product::find()->all();
        $modelStuff = Stuff::find()->all();
        
        
        return $this->render('create', ['model'=> $form_model, 
            'mProduct'=>$modelProduct, 
            'mStuff'=>$modelStuff
                ]);

        //}
        
        //return $this->render('create', ['model'=> $form_model]);
    }
    public function actionStep2()
    {
        $session = \Yii::$app->session;

        // проверяем наличие открытой сессии
        if($session->isActive && isset($session['expenseId']))
        {
            if($session['step']==1) {

                $expId = $session->get("expenseId");

                $form_model = Expense::find()->where(['expenseId' => $expId])->orderBy(['expenseId' => SORT_DESC])->one();
                if ($form_model->load(Yii::$app->request->post(), '')) {
                    $form_model->paidType = Yii::$app->request->post()['paidType'];
                    $form_model->clientId = Yii::$app->request->post()['clientId'];
                    $expSum = 0;
                    $orders = $form_model->orders;

                    foreach ($orders as $order) {
                        $expSum += isset($order->orderSumm)?$order->orderSumm:0;

                    }

                    $form_model->comment = Yii::$app->request->post()['comment'];
                    switch ($form_model->paidType) {
                        case 0:
                            $form_model->inCash = $expSum;
                            break;
                        case 1:
                            $form_model->terminal= $expSum;
                            break;
                        case 2:
                            $form_model->transfer = $expSum;
                            break;
                    }


                    $form_model->expSum = $expSum;
                    //$session['step'] = 2;
                    if ($form_model->validate())
                    {
                        $session['step'] = 2;
                        $form_model->save();
                        $this->redirect('/sold/delivery/step2');
                    }
                    else
                        return var_dump($form_model->errors);
                        //redirect('/sold/expense/step1');

                }else
                {
                    $this->redirect('/sold/expense');
                }

            }
            else
            {

                $this->redirect('/sold/expense');
            }


        }


    }
    public function actionRefreshd()
    {

        $models = Expense::find()->where(['expType'=>0])->orderBy(['expenseId'=>SORT_DESC])->limit(1000)->all();
        
        //return var_dump($models);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        $arr = ArrayHelper::toArray($models,[
                    Expense::class =>[
                'expenseId',
                'expenseDate',
                'comment',
                'clientId',
                'paidType',
                'paidTypeName'=>function($data){
                    $str = 'Наличные';
                    switch($data->paidType)
                    {
                        case 0:
                            $str = 'Наличные';
                            break;
                        case 1:
                            $str = 'Без нал';
                            break;
                        case 2:
                            $str = 'Перечисление';
                            break;
                    }
                    return $str;
                },
                'expSum',
                'clientName'=>function($data){
                    return (isset($data->client->clientName)?$data->client->clientName:'прямая продажа');
                },
                'deliveryPrice'=>function($data){
                    return $data->delivery->price;
                },
                'delivery'=>function($data){
                    return $data->delivery->address.' '.$data->delivery->name.' '.$data->delivery->description ;
                },
                //'category'=>function($data){
                //    return $data->category->name;
                //},
            ],

        ]);
        return ['datas' =>$arr];
    }

    public function actionNew()
    {
        $isAjax = false;
        $form_model =  new \app\models\Expense();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        //$form_model->load(\Yii::$app->request->post());

        if ($form_model->load(Yii::$app->request->post(), '')) {
            $form_model->expenseId = Yii::$app->request->post()['expenseId'];
            $form_model->expenseDate = Yii::$app->request->post()['expenseDate'];
            $form_model->debt = Yii::$app->request->post()['debt'];
            $form_model->comment = Yii::$app->request->post()['comment'];
            $form_model->clientId = Yii::$app->request->post()['clientId'];
            $form_model->fakt = Yii::$app->request->post()['fakt'];
            $form_model->expType = Yii::$app->request->post()['expType'];
            $form_model->transfer = Yii::$app->request->post()['transfer'];
            $form_model->inCash = Yii::$app->request->post()['inCash'];
            $form_model->terminal = Yii::$app->request->post()['terminal'];
            $form_model->expSum = Yii::$app->request->post()['expSum'];
            $form_model->status = Yii::$app->request->post()['status'];
            $form_model->userId = Yii::$app->request->post()['userId'];
            $form_model->paidType = Yii::$app->request->post()['paidType'];
            $form_model->charge = Yii::$app->request->post()['charge'];
            
            $form_model->save();
            
            /*
             * 
             * 'expenseId' => 'Номер накладной',
            'expenseDate' => 'Дата',
            'debt' => 'Карзж',
            'comment' => 'Комментарий',
            'clientId' => 'Клиент',
            'fakt' => 'Факт',
            'expType' => 'Тип',
            'transfer' => 'Перечисление',
            'inCash' => 'Наличные',
            'terminal' => 'Терминал',
            'expSum' => 'Сумма',
            'status' => 'Статус',
            'userId' => 'Пользователь',
            'paidType' => 'Тип оплаты',
            'charge' => 'Наценка',
             */
            if($isAjax)
            {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $form_model->toArray();

            }else
                return $this->render('index', ['models'=> $form_model]);

        }
        //var_dump($form_model);
        return $this->render('index', ['model'=> $form_model]);
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];

        try {
            $rowCnt = Expense::deleteAll('expenseId='.$id);
            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }

    }
    /*
    public function actionRefreshd()
    {

        $models = Product::find()->all();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        $arr = ArrayHelper::toArray($models,[
            Product::class =>[
                'productId',
                'name',
                'measureId',
                'categoryId',
                'measure'=>function($data){
                    return $data->measure->name;
                },
                'category'=>function($data){
                    return $data->category->name;
                },
            ],

        ]);
        return ['datas' =>$arr];
    }*/
}
