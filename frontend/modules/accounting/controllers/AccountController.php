<?php

namespace app\modules\accounting\controllers;

use app\models\Clients;
use app\components;
use app\modules\accounting\models\Account;
use app\modules\accounting\models\Balancesum;
use app\modules\accounting\models\Clientbalancesum;
use Yii;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class AccountController extends components\BaseController
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
        $this->view->registerJs('menuActive("'.$this->uniqueId.'");');
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * @return array|string
     */
    public function actionNew()
    {
        $isAjax = false;
        try {
            if (\Yii::$app->request->isAjax) {
                $isAjax = TRUE;// return 'Запрос принят!';
            }
            //$form_model->load(\Yii::$app->request->post());
            $clientId = 0;
            if(Yii::$app->request->post()['expenseId'] == ""){
                $clientId = Yii::$app->request->post()['clientId'];
            }
            else{
                $temp = \app\modules\sold\models\Expense::findOne(["expenseId" => Yii::$app->request->post()['expenseId']]);
                $clientId = $temp->clientId;
            }
            $formModel = new Account();

            $models = Account::find();
            if (!empty(Yii::$app->request->post())) {
                foreach (Yii::$app->request->post()["comment"] as $key => $item) {
                    if($item != ""){
                        $form_model = new Account();
                        $form_model->expenseId = (Yii::$app->request->post()['expenseId'] != "") ? Yii::$app->request->post()['expenseId'] : 0;
                        $form_model->comment = $item;
                        $form_model->accountDate = date("Y-m-d H:i:s");
                        $form_model->summ = Yii::$app->request->post()['summ'][$key] > 0 ? Yii::$app->request->post()['summ'][$key] : -1*Yii::$app->request->post()['summ'][$key];
                        $form_model->accountType = Yii::$app->request->post()['summ'][$key] > 0 ? 1 : 0;
                        $form_model->clientId = $clientId;
                        $form_model->connect = Yii::$app->request->post()["accountId"];
                        $form_model->cnt = Yii::$app->request->post()['cnt'][$key];
                        $form_model->serviceDate = Yii::$app->request->post()['serviceDate'];
                        $form_model->byone = Yii::$app->request->post()['byone'][$key];
                        $form_model->accountPay = Yii::$app->request->post()['type'];
                        $form_model->identity = Yii::$app->request->post()['identity'];
                        if($form_model->validate())
                            $form_model->save();

//                        if ($isAjax) {
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

                            if($clientId != null || $clientId != 0) {
                                $model = Clients::findOne(['clientId' => $clientId]);
                                $model->summ = $model->summ + Yii::$app->request->post()['summ'][$key];
                                $model->save();

                            }
                            /*Суммирование */

                            $model = Balancesum::find()->where('balancesumDate >= :date',[ ":date"=>date("Y-m-d")])->all();
                            if(!empty($model)){
                                $balance = Balancesum::findOne(['balancesumDate' => date("Y-m-d")]);
                                if(empty($balance)){
                                    $command = Yii::$app->db->createCommand('SELECT * FROM balancesum WHERE balancesumDate < :dates order by balancesumDate desc limit 1');
                                    $tempBalance = $command->bindValue(":dates",Yii::$app->request->post()['date'])->queryOne();

                                    $balanceSum = new Balancesum();
                                    $balanceSum -> balancesumDate = date("Y-m-d");
                                    $balanceSum -> summ = $tempBalance["summ"][$key];
                                    $balanceSum -> save();
                                    $sql = "UPDATE balancesum SET summ = summ + ". Yii::$app->request->post()['summ'][$key] ."	WHERE balancesumDate >= '" . date("Y-m-d") . "'";
                                    $query = Yii::$app->db->createCommand($sql)->query();
                                }
                                else{
                                    $sql = "UPDATE balancesum SET summ = summ + ". Yii::$app->request->post()['summ'][$key] ."	WHERE balancesumDate >= '" . date("Y-m-d") . "'";

                                    $query = Yii::$app->db->createCommand($sql);

                                    $query->query();
                                }

                            }
                            else{
                                $command = Yii::$app->db->createCommand('SELECT * FROM balancesum WHERE balancesumDate < :dates order by balancesumDate desc limit 1');
                                $tempBalance = $command->bindValue(":dates",date("Y-m-d"))->queryOne();
                                $balance = new Balancesum();
                                $balance->balancesumDate = date("Y-m-d");
                                $balance->summ = $tempBalance["summ"] + Yii::$app->request->post()['summ'][$key];
                                $balance->save();

                            }

//                        } else {
//                            return $this->render('index', ['models' => $models]);
//                        }
                    }
                }
                if($isAjax){
                    return $form_model->errors;
                }
            }
            //var_dump($form_model);
            return $this->render('index', ['model' => $formModel]);
        }catch(\yii\db\Exception $ex)
        {
            echo $ex->getMessage();
        }
    }

}
