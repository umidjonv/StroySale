<?php

namespace app\modules\sold\controllers;

use app\modules\sold\models\Deliver;
use app\modules\sold\models\Expense;

use app\modules\sold\models\Delivery;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class DeliveryController extends Controller
{
    public function actionIndex()
    {
        $this->render('delivery_v');
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    public function actionStep2()
    {
        $session = \Yii::$app->session;

        if(isset($session['expenseId'])&& isset($session['step']) && $session['step']==2)
        {
            $modelDelivery  = new Delivery();

            $expId = $session['expenseId'];
            $model = Expense::find()->where(['expenseId' => $expId])->orderBy(['expenseId' => SORT_DESC])->one();
            //var_dump($model);

            return $this->render('expense_step2',['model'=>$model, 'modelDelivery'=>$modelDelivery]);
        }else
        {
            $this->redirect('/sold/expense');
        }

    }

    public function actionSave()
    {
        try
        {

            //$this->enableCsrfValidation = false;

            $session = \Yii::$app->session;
            $model = new Delivery();

        if((isset($session['expenseId'])&& isset($session['step'])) && ($session['step']==2&&($model->load(Yii::$app->request->post(), ''))) )
        {


            $expId = $session['expenseId'];

            $model->expenseId = $expId;
            $model->deliveryType = Yii::$app->request->post()['deliveryType'];
            $model->name = Yii::$app->request->post()['name'];
            $model->description = Yii::$app->request->post()['description'];
            $model->price =  Yii::$app->request->post()['price'];
            $model->address = Yii::$app->request->post()['address'];
            if($model->validate())
            {
                $model->save();
                return 'OK';
            }else
            {
                return var_dump(Yii::$app->request->post()['deliveryType']);
            }

        }else
        {
            $this->redirect('/sold/expense');
        }
        }catch(\Exception $ex)
        {
            return $ex->getMessage();
        }

    }

}

