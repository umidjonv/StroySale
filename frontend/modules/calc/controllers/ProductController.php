<?php

namespace app\modules\calc\controllers;

use app\modules\calc\models\Product;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
        $models = Product::find();
        return $this->render('index', [
            'models' => $models,
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


    public function actionNew()
    {
        $isAjax = false;
        $form_model =  new Product();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        //$form_model->load(\Yii::$app->request->post());

        if ($form_model->load(Yii::$app->request->post(), '')) {
            $form_model->productId = Yii::$app->request->post()['productId'];
            $form_model->name = Yii::$app->request->post()['name'];
            $form_model->measureId = Yii::$app->request->post()['measureId'];
            $form_model->categoryId = Yii::$app->request->post()['categoryId'];
            $form_model->save();
            $models = Product::find();
            if($isAjax)
            {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $form_model->toArray();

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
            $rowCnt = Product::deleteAll('productId='.$id);
            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }

    }
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
    }
}
