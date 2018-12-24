<?php

namespace app\modules\calc\controllers;

use app\modules\calc\models\Price;
use app\modules\calc\models\Product;
use app\modules\calc\models\Stuff;
use app\modules\calc\models\Category;
use Yii;
use yii\helpers\ArrayHelper;
use app\components;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class StuffController extends components\BaseController
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

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $modelProduct = Product::find()->all();
        $modelStuff = Stuff::find()->all();
        $models = Stuff::find();
        return $this->render('index', [
            'models' => $models,
            'mProduct'=>$modelProduct,
            'mStuff'=>$modelStuff
        ]);
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
            $model = new Stuff();
            if ($model->load(Yii::$app->request->post(), '')) {
                $id = Yii::$app->request->post()['stuffId'];
                $model = Stuff::findOne(['stuffId'=>$id]);
                $model->stuffId = Yii::$app->request->post()['stuffId'];
                $model->name = Yii::$app->request->post()['name'];
                $model->price = Yii::$app->request->post()['price'];
                $model->measureId = Yii::$app->request->post()['measureId'];
                $model->categoryId = Yii::$app->request->post()['categoryId'];
                $model->salary = Yii::$app->request->post()['salary'];
                $model->energy = Yii::$app->request->post()['energy'];
                if($model->validate()) {
                    if ($model->save()) {
                        $price = new Price();
                        $price->setPrice($model->price, 2, $model->stuffId);
                    }
                }
                $models = Stuff::find()->all();
                // var_dump($model);
                if($isAjax)
                {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return $model->errors;

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
        $form_model =  new Stuff();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        //$form_model->load(\Yii::$app->request->post());

        if ($form_model->load(Yii::$app->request->post(), '')) {
            $form_model->stuffId = Yii::$app->request->post()['stuffId'];
            $form_model->name = Yii::$app->request->post()['name'];
            $form_model->price = Yii::$app->request->post()['price'];
            $form_model->measureId = Yii::$app->request->post()['measureId'];
            $form_model->categoryId = Yii::$app->request->post()['categoryId'];
            $form_model->salary = Yii::$app->request->post()['salary'];
            $form_model->energy = Yii::$app->request->post()['energy'];
            if($form_model->validate()) {
                if ($form_model->save()) {
                    $price = new Price();
                    $price->setPrice($form_model->price, 2, $form_model->stuffId);
                }
            }
            $models = Stuff::find();
            if($isAjax)
            {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $form_model->errors;

            }else
                return $this->render('index', ['models'=> $models]);

        }
        //var_dump($form_model);
        return $this->render('index', ['model'=> $form_model]);
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];

        try {
            $rowCnt = Stuff::deleteAll('stuffId='.$id);
            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }

    }
    public function actionRefreshd()
    {

        $models = Stuff::find()->all();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        $arr = ArrayHelper::toArray($models,[
            Stuff::class =>[
                'stuffId',
                'name',
                'price',
                'salary',
                'energy',
                'categoryId',
                'category'=>function($data){
                    return ($data->categoryId != null) ? $data->category->name : "";
                },
                'measureId',
                'measure'=>function($data){
                    return $data->measure->name;
                },
            ],

        ]);
        return ['datas' =>$arr];
    }

    public function actionRefresh()
    {

        $models = \app\models\Struct::find()->all(Yii::$app->request->post('id'));
        echo "<pre>";
        print_r($models);
        echo "</pre>";
//
//        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        //$this->redirect("site/login");
//        $arr = ArrayHelper::toArray($models,[
//            Stuff::class =>[
//                'stuffId',
//                'name',
//                'salary',
//                'energy',
//                'measure'=>function($data){
//                    return $data->measure->name;
//                },
//            ],
//
//        ]);
//        return ['datas' =>$arr];
    }
}
