<?php

namespace app\modules\accounting\controllers;

use app\modules\accounting\models\Point;
use Yii;
use app\modules\calc\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class PointController extends components\BaseController
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

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $models = Point::find();

        return $this->render('index', [
            'models' => $models
        ]);
    }

    public function beforeAction($action)
    {
        $this->view->registerJs('menuActive("'.$this->uniqueId.'");');
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionSave()
    {
        $isAjax = false;
        try{
            if(\Yii::$app->request->isAjax){
                $isAjax = TRUE;

// return 'Запрос принят!';
            }
            //$form_model->load(\Yii::$app->request->post());
            $model = new Point();
            if ($model->load(Yii::$app->request->post(), '')) {
                $id = Yii::$app->request->post()['pointId'];
                $model = Point::findOne(['pointId'=>$id]);

                $model->pointId = Yii::$app->request->post()['pointId'];
                $model->name = Yii::$app->request->post()['name'];
                $model->save();
                $models = Point::find()->where("status!=1")->all();
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


    public function actionNew()
    {
        $isAjax = false;
        try {
            $form_model = new Point();
            if (\Yii::$app->request->isAjax) {
                $isAjax = TRUE;// return 'Запрос принят!';
            }
            //$form_model->load(\Yii::$app->request->post());

            if ($form_model->load(Yii::$app->request->post(), '')) {
                $form_model->pointId = Yii::$app->request->post()['pointId'];
                $form_model->name = Yii::$app->request->post()['name'];
                $form_model->save();
                $models = Point::find()->where("status!=1")->all();
                if ($isAjax) {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return $form_model->toArray();

                } else
                    return $this->render('index', ['models' => $models]);

            }
            //var_dump($form_model);
            return $this->render('index', ['model' => $form_model]);
        }catch(\yii\db\Exception $ex)
        {
            echo $ex->getMessage();
        }
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];

        try {$model = Point::findOne(['pointId'=>$id]);

            $model->status = 1;
            $model->save();
//            $rowCnt = Point::deleteAll('pointId='.$id);
//            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function actionRefreshd()
    {

        $models = Point::find()->where("status!=1")->all();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        return ['datas' =>$models];
    }
}
