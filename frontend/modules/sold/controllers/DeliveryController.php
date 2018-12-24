<?php

namespace app\modules\sold\controllers;

use app\modules\sold\models\Deliver;
use app\modules\sold\models\Expense;

use app\modules\sold\models\Delivery;
use Yii;
use yii\helpers\ArrayHelper;
use app\components;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class DeliveryController extends components\BaseController
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
            if(Delivery::find()->where(['expenseId'=>$expId])->exists())
            {
                $modelDelivery= Delivery::find()->where(['expenseId'=>$expId])->one();
            }

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
            $modelEx = Expense::find()->where(['expenseId'=>$expId])->one();
            if(Delivery::find()->where(['expenseId'=>$expId])->exists())
            {
                $model = Delivery::find()->where(['expenseId'=>$expId])->one();

                //$model= Delivery::find()->where(['expenseId'=>$expId])->one();
                //$this->redirect('/sold/expense/nakladnaya/'.$expId);
            }else
            {

                //$modelEx->status = 0;
                //$modelEx->expSum = $modelEx->expSum+ Yii::$app->request->post()['price'];
                //$modelEx->save();
                $model->expenseId = $expId;
            }
                $model->deliveryType = Yii::$app->request->post()['deliveryType'];
                $model->name = Yii::$app->request->post()['name'];
                $model->driver = Yii::$app->request->post()['driver'];
                $model->description = Yii::$app->request->post()['description'];
                $model->price =  Yii::$app->request->post()['price'];
                $model->address = Yii::$app->request->post()['address'];
                $session->remove('expenseId');
                if($model->validate())
                {
                    //var_dump($model->price);
                    $modelEx->expSum += isset($model->price)?$model->price:0;
                    if($modelEx->validate())
                    {
                        $modelEx->save();
                    }else
                    {
                        return 'sss'.var_dump($modelEx->errors);
                    }

                    $model->save();
                    $this->redirect('/sold/expense/nakladnaya/'.$expId);
                }else
                {
                    return var_dump($model->errors);
                }






        }else
        {
            $this->redirect('/sold/expense/inprocesslist');
        }
        }catch(\Exception $ex)
        {
            return $ex->getMessage();
        }

    }
    public function actionGetnames()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        $my = (new yii\db\Query())->select(['name'])->from('delivery')->distinct()->all();

        return $my;
    }
    public function actionGetdrivers()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        $my = (new yii\db\Query())->select(['driver'])->from('delivery')->distinct()->all();

        return $my;
    }
    public function actionGetaddresses()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        $my = (new yii\db\Query())->select(['address'])->from('delivery')->distinct()->all();

        return $my;
    }

}

