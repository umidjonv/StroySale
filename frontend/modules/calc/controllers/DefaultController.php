<?php

namespace app\modules\calc\controllers;

use app\components;
use app\modules\calc\models\Product;

/**
 * Default controller for the `calc` module
 */
class DefaultController extends components\BaseController
{

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPassport(){
        $model = \Yii::$app->db->CreateCommand("select s.stuffId,s.name,s.measureId,s.energy,c.name as Cname, c.categoryId from stuff s left join category c on s.categoryId = c.categoryId");
        $model = $model->queryAll();

        $addition = \Yii::$app->db->CreateCommand("select * from product p where p.categoryId = 10");
        $addition = $addition->queryAll();

        return $this->render("passport",array(
            "addition" => $addition,
            "model" => $model
        ));
    }


    public function actionGetblank(){
        $res = array();

        $stuff = \Yii::$app->db->CreateCommand("select * from stuff where stuffId=" . $_GET["stuff"]);
        $stuff = $stuff->queryOne();

        $category = \Yii::$app->db->CreateCommand("select * from passport where fieldType='category' and id='" . $stuff["categoryId"] . "'");
        $category = $category->queryAll();
        foreach ($category as $val) {
            $res["category"][$val["fieldName"]] = $val["value"];
        }

        $from = \Yii::$app->db->CreateCommand("select * from `from`");
        $from = $from->queryOne();

//        foreach ($from as $val) {
//            $res["firm"][$val["fieldName"]] = $val["value"];
//        }


        $stuff = \Yii::$app->db->CreateCommand("select * from passport where fieldType='stuff' and id='" . $_GET["stuff"] . "'");
        $stuff = $stuff->queryAll();
        foreach ($stuff as $val) {
            $res["stuff"][$val["fieldName"]] = $val["value"];
        }
        $addition = Product::find()->where(["productId"=>$_GET["addition"]])->one();
        return $this->renderPartial("getBlank",array(
            "number" => $_GET["number"],
            "adr" => $_GET["adr"],
            "from" => $from,
            "v" => $_GET["v"],
            "addition" => (!empty($addition)) ? $addition->name : "",
            "additionCnt" => $_GET["additionCnt"],
            "sendDate" => $_GET["sendDate"],
            "res" => $res
        ));
    }

    public function actionAdd(){
        return $this->render("addPassportData");
    }

    public function actionGetType(){
        $isAjax = false;
        try {
            if (\Yii::$app->request->isAjax) {
                $isAjax = TRUE;
                $type = $_POST["type"];
                $model = \Yii::$app->db->CreateCommand("select s.stuffId,s.name,s.measureId,s.energy,c.name as Cname, c.categoryId from stuff s left join category c on s.categoryId = c.categoryId");
                $model = $model->queryAll();

                $category = \Yii::$app->db->CreateCommand("select * from category p ");
                $category = $category->queryAll();

                $passField = \Yii::$app->db->CreateCommand("select * from passportField where fieldtype='" . $type . "'");
                $passField = $passField->queryAll();
                return $this->renderPartial("getType", array(
                    "type" => $type,
                    "category" => $category,
                    "model" => $model,
                    "passField" => $passField
                ));
            }
        }catch(\yii\db\Exception $ex)
        {
            echo $ex->getMessage();
        }
    }

    public function actionGetTypeValue(){
        $isAjax = false;
        try {
            if (\Yii::$app->request->isAjax) {
                $isAjax = TRUE;
                $val = array();
                $type = $_POST["type"];
                $passField = \Yii::$app->db->CreateCommand("select * from passport where fieldtype = '" . $type . "' and id = ".$_POST["id"]." and fieldName = '" . $_POST["field"] . "'");
                $passField = $passField->queryOne();
                if(!empty($passField)){
                    $val["val"] = $passField["value"];
                    $val["id"] = $passField["passportId"];
                }
                else{
                    $val["val"] = "";
                    $val["id"] = "";
                }
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $val;

            }
        }catch(\yii\db\Exception $ex)
        {
            echo $ex->getMessage();
        }
    }

    public function actionAddType(){
        $post = $_POST;
        if($post["passportId"] != ""){
            \Yii::$app->db->createCommand()->update("passport", array(
                "fieldName" => $post["fieldName"],
                "value" => $post["value"],
                "fieldType" => $post["fieldType"],
                "id" => $post["id"],
            ),"passportId = :id",[":id"=>$post["passportId"]])->execute();
        }
        else {
            \Yii::$app->db->createCommand()->insert("passport", array(
                "fieldName" => $post["fieldName"],
                "value" => $post["value"],
                "fieldType" => $post["fieldType"],
                "id" => $post["id"],
            ))->execute();
        }
    }
}
