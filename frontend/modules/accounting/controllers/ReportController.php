<?php

namespace app\modules\accounting\controllers;

use app\models\Clients;
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
        $models = Yii::$app->db->createCommand('select a.identity, a.accountDate, a.comment, a.expenseId, a.summ, c.clientName from account a left join expense e on e.expenseId = a.expenseId left join clients c on c.clientId = a.clientId where a.accountDate >= :from AND a.accountDate < :to AND a.accountType = 0')
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

        $models = Yii::$app->db->createCommand('select a.identity, a.accountDate, a.comment, a.expenseId, a.summ, c.clientName from account a left join expense e on e.expenseId = a.expenseId left join clients c on c.clientId = a.clientId where a.accountDate >= :from AND a.accountDate < :to AND a.accountType = 1')
            ->bindValues([":from"=>Yii::$app->request->get()['dateFrom'],":to"=>Yii::$app->request->get()['dateTo']])
            ->queryAll();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        return ['datas' =>$models];
    }

    public function actionBalance(){
        $models = Yii::$app->db->createCommand('select a.identity, a.accountDate, a.comment, a.expenseId, a.summ, c.clientName, a.accountType from account a left join expense e on e.expenseId = a.expenseId left join clients c on c.clientId = a.clientId where a.accountDate >= :date')
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
        $models = Yii::$app->db->createCommand('select a.identity, a.accountId, a.accountDate, a.comment, a.expenseId, a.summ, c.clientName, a.accountType from account a left join expense e on e.expenseId = a.expenseId left join clients c on c.clientId = a.clientId where a.accountDate >= :from AND a.accountDate < :to')
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

    public function actionDelete(){
        $acc = Account::find()->where("accountId = ".Yii::$app->request->post()['id'])->one();

        if($acc->accountType == 0){
            $model = Clients::findOne(['clientId' => $acc->clientId]);
            $model->summ = $model->summ - $acc->summ;
            $model->save();

            $model = Balancesum::find()->where('balancesumDate >= :date',[ ":date"=>date("Y-m-d")])->all();
            if(!empty($model)){
                $balance = Balancesum::findOne(['balancesumDate' => date("Y-m-d")]);
                if(empty($balance)){
                    $command = Yii::$app->db->createCommand('SELECT * FROM balancesum WHERE balancesumDate < :dates order by balancesumDate desc limit 1');
                    $tempBalance = $command->bindValue(":dates",Yii::$app->request->post()['date'])->queryOne();

                    $balanceSum = new Balancesum();
                    $balanceSum->balancesumDate = date("Y-m-d");
                    $balanceSum->summ = $tempBalance["summ"];
                    $balanceSum->save();
                    $sql = "UPDATE balancesum SET summ = summ + ". $acc->summ ."	WHERE balancesumDate >= '" . date("Y-m-d") . "'";
                    $query = Yii::$app->db->createCommand($sql)->query();
                }
                else{
                    $sql = "UPDATE balancesum SET summ = summ + ". $acc->summ ."	WHERE balancesumDate >= '" . date("Y-m-d") . "'";

                    $query = Yii::$app->db->createCommand($sql);

                    $query->query();
                }
            }
            else{
                $command = Yii::$app->db->createCommand('SELECT * FROM balancesum WHERE balancesumDate < :dates order by balancesumDate desc limit 1');
                $tempBalance = $command->bindValue(":dates",date("Y-m-d"))->queryOne();
                $balance = new Balancesum();
                $balance->balancesumDate = date("Y-m-d");
                $balance->summ = $tempBalance["summ"] + $acc->summ;
                $balance->save();

            }
        }
        else{
            $model = Clients::findOne(['clientId' => $acc->clientId]);
            $model->summ = $model->summ + $acc->summ;
            $model->save();

            $model = Balancesum::find()->where('balancesumDate >= :date',[ ":date"=>date("Y-m-d")])->all();
            if(!empty($model)){
                $balance = Balancesum::findOne(['balancesumDate' => date("Y-m-d")]);
                if(empty($balance)){
                    $command = Yii::$app->db->createCommand('SELECT * FROM balancesum WHERE balancesumDate < :dates order by balancesumDate desc limit 1');
                    $tempBalance = $command->bindValue(":dates",Yii::$app->request->post()['date'])->queryOne();

                    $balanceSum = new Balancesum();
                    $balanceSum->balancesumDate = date("Y-m-d");
                    $balanceSum->summ = $tempBalance["summ"];
                    $balanceSum->save();
                    $sql = "UPDATE balancesum SET summ = summ - ". $acc->summ ."	WHERE balancesumDate >= '" . date("Y-m-d") . "'";
                    $query = Yii::$app->db->createCommand($sql)->query();
                }
                else{
                    $sql = "UPDATE balancesum SET summ = summ - ". $acc->summ ."	WHERE balancesumDate >= '" . date("Y-m-d") . "'";

                    $query = Yii::$app->db->createCommand($sql);

                    $query->query();
                }
            }
            else{
                $command = Yii::$app->db->createCommand('SELECT * FROM balancesum WHERE balancesumDate < :dates order by balancesumDate desc limit 1');
                $tempBalance = $command->bindValue(":dates",date("Y-m-d"))->queryOne();
                $balance = new Balancesum();
                $balance->balancesumDate = date("Y-m-d");
                $balance->summ = $tempBalance["summ"] - $acc->summ;
                $balance->save();

            }

        }
        Yii::$app->db->createCommand("delete from account where accountId = ".Yii::$app->request->post()['id'])->execute();
    }

    public function actionCash(){

        $models = Account::find()->where(["accountDate"=>date("Y-m-d"),"accountPay"=>'cash'])->orderBy("accountDate");
        return $this->render('cash', [
            'models' => $models
        ]);
    }

    public function actionGetcash(){

        $models = Yii::$app->db->createCommand('select a.identity, a.accountDate, a.comment, a.expenseId, a.summ, c.clientName from account a left join expense e on e.expenseId = a.expenseId left join clients c on c.clientId = a.clientId where a.accountDate >= :from AND a.accountDate < :to AND accountPay = "cash"')
            ->bindValues([":from"=>Yii::$app->request->get()['dateFrom'],":to"=>Yii::$app->request->get()['dateTo']])
            ->queryAll();

        //where("accountDate >= :from AND accountDate < :to AND accountType = 0",[":from"=>Yii::$app->request->post()['dateFrom'],":to"=>Yii::$app->request->post()['dateTo']])->orderBy("accountDate")->all();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        return ['datas' =>$models];
    }

    public function actionTransfer(){

        $models = Account::find()->where(["accountDate"=>date("Y-m-d"),"accountPay"=>'transfer'])->orderBy("accountDate");
        return $this->render('transfer', [
            'models' => $models
        ]);
    }

    public function actionGettransfer(){
        $models = Yii::$app->db->createCommand('select a.identity, a.accountDate, a.comment, a.expenseId, a.summ, c.clientName from account a left join expense e on e.expenseId = a.expenseId left join clients c on c.clientId = a.clientId where a.accountDate >= :from AND a.accountDate < :to AND accountPay = "transfer"')
            ->bindValues([":from"=>Yii::$app->request->get()['dateFrom'],":to"=>Yii::$app->request->get()['dateTo']])
            ->queryAll();

        //where("accountDate >= :from AND accountDate < :to AND accountType = 0",[":from"=>Yii::$app->request->post()['dateFrom'],":to"=>Yii::$app->request->post()['dateTo']])->orderBy("accountDate")->all();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        return ['datas' =>$models];
    }

}
