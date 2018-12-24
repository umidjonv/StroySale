<?php

namespace app\modules\accounting\controllers;

use app\modules\accounting\models\Account;
use app\modules\accounting\models\Balancesum;
use app\modules\accounting\models\Clientbalancesum;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\components;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class ReportController extends components\BaseController
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
        $this->view->registerJs('menuActive("'.$this->getUniqueId().'/'.Yii::$app->controller->action->id.'");');
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionCosts(){

        $models = Account::find()->where(["accountDate"=>date("Y-m-d"),"accountType"=>0])->orderBy("accountDate");
        return $this->render('cost', [
            'models' => $models
        ]);
    }

    public function actionGetCosts()
    {
        $models = Yii::$app->db->createCommand('select a.accountDate, a.comment, a.expenseId, a.summ, c.clientName from account a left join expense e on e.expenseId = a.expenseId left join clients c on c.clientId = a.clientId where a.accountDate >= :from AND a.accountDate < :to AND a.accountType = 0')
            ->bindValues([":from"=>Yii::$app->request->get()['dateFrom'],":to"=>Yii::$app->request->get()['dateTo']])
            ->queryAll();

        //where("accountDate >= :from AND accountDate < :to AND accountType = 0",[":from"=>Yii::$app->request->post()['dateFrom'],":to"=>Yii::$app->request->post()['dateTo']])->orderBy("accountDate")->all();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        return ['datas' =>$models];
    }

    public function actionComing(){

        $models = Account::find()->where(["accountDate"=>date("Y-m-d"),"accountType"=>1])->orderBy("accountDate");
        return $this->render('coming', [
            'models' => $models
        ]);
    }

    public function actionGetComing()
    {

        $models = Yii::$app->db->createCommand('select a.accountDate, a.comment, a.expenseId, a.summ, c.clientName from account a left join expense e on e.expenseId = a.expenseId left join clients c on c.clientId = a.clientId where a.accountDate >= :from AND a.accountDate < :to AND a.accountType = 1')
            ->bindValues([":from"=>Yii::$app->request->get()['dateFrom'],":to"=>Yii::$app->request->get()['dateTo']])
            ->queryAll();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        return ['datas' =>$models];
    }

    public function actionBalance(){
        $models = Yii::$app->db->createCommand('select a.accountDate, a.comment, a.expenseId, a.summ, c.clientName, a.accountType from account a left join expense e on e.expenseId = a.expenseId left join clients c on c.clientId = a.clientId where a.accountDate >= :date')
            ->bindValues([":date"=>date("Y-m-d")])
            ->queryAll();
        //$models = Account::find()->where(["accountDate"=>date("Y-m-d")])->orderBy("accountDate");
        return $this->render('balance', [
            'models' => $models
        ]);
    }

    public function actionGetBalance()
    {
        $balance = new Balancesum();
        $models = Yii::$app->db->createCommand('select a.accountDate, a.comment, a.expenseId, a.summ, c.clientName, a.accountType from account a left join expense e on e.expenseId = a.expenseId left join clients c on c.clientId = a.clientId where a.accountDate >= :from AND a.accountDate < :to')
            ->bindValues([":from"=>Yii::$app->request->post()['dateFrom'],":to"=>Yii::$app->request->post()['dateTo']])
            ->queryAll();
        //$models = Account::find()->where("accountDate >= :from AND accountDate < :to",[":from"=>Yii::$app->request->post()['dateFrom'],":to"=>Yii::$app->request->post()['dateTo']])->orderBy("accountDate")->all();
        $balance = $balance->getSum(date("Y-m-d",strtotime(Yii::$app->request->post()['dateTo'])-86400));
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        return ['datas' =>$models,'balance'=>$balance];
    }



    public function actionClientBalance(){
        $id = Yii::$app->request->post()['id'];
        $models = Account::find()->where(["accountDate"=>date("Y-m-d"),'clientId'=>$id])->orderBy("accountDate");
        return $this->renderPartial('clientBalance', [
            'models' => $models,
            'clientId' => $id
        ]);
    }

    public function actionGetClientBalance()
    {
        $balance = new Clientbalancesum();
        $models = Account::find()->where("accountDate >= :from AND accountDate < :to AND clientId = :id",[":from"=>Yii::$app->request->post()['dateFrom'],":to"=>Yii::$app->request->post()['dateTo'],':id'=>Yii::$app->request->post()['id']])->orderBy("accountDate")->all();
        $balance = $balance->getSum(date("Y-m-d",strtotime(Yii::$app->request->post()['dateTo'])-86400),Yii::$app->request->post()['id']);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        return ['datas' =>$models,'balance'=>$balance];
    }


}
