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

}

