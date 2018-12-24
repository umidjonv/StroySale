<?php

namespace app\models;

use app\modules\calc\models\Struct;
use Yii;

/**
 * This is the model class for table "balance".
 *
 * @property int $balanceId
 * @property int $stuffProdId
 * @property double $cnt
 * @property bool $idType
 */
class Balance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'balance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stuffProdId'], 'integer'],
            [['cnt'], 'number'],
            [['idType'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'balanceId' => 'Balance ID',
            'stuffProdId' => 'Stuff Prod ID',
            'cnt' => 'Cnt',
            'idType' => 'Id Type',
        ];
    }


    public function getStuffProd()
    {
        return $this->hasOne(Product::className(), ['productId' => 'stuffProdId']);
    }

    public function getStuffStuff()
    {
        return $this->hasOne(Stuff::className(), ['stuffId' => 'stuffProdId']);
    }

    public function addToBalance($id,$type,$cnt){
        if($type == 1){
            $stuff = Yii::$app->db->createCommand("select * from stuff WHERE stuffId = ".$id)->queryOne();
            if($stuff["categoryId"] == 11){
                $struct = Yii::$app->db->createCommand("select * from struct WHERE stuffId = ".$id)->queryAll();
                foreach ($struct as $item) {
                    $this->addToBalance($item["stuffProdId"],$item["idType"],$item["cnt"]*$cnt);
                }
            }
            else{
                $model = Yii::$app->db->createCommand("select * from balance WHERE stuffProdId = ".$id." and idType = ".$type)->queryOne();
                if (!empty($model)) {
                    Yii::$app->db->createCommand()->update("balance",
                        [
                            'cnt' => $model["cnt"] + $cnt,
                        ],
                        "stuffProdId = " . $id . " and idType = " . $type
                    )->execute();
                } else {
                    Yii::$app->db->createCommand()->insert("balance",
                        [
                            'stuffProdId' => $id,
                            'cnt' => $cnt,
                            'idType' => $type
                        ]
                    )->execute();
                }
            }
        }
        else {
            $model = Yii::$app->db->createCommand("select * from balance WHERE stuffProdId = " . $id . " and idType = " . $type)->queryOne();
            if (!empty($model)) {
                Yii::$app->db->createCommand()->update("balance",
                    [
                        'cnt' => $model["cnt"] + $cnt,
                    ],
                    "stuffProdId = " . $id . " and idType = " . $type
                )->execute();
            } else {
                Yii::$app->db->createCommand()->insert("balance",
                    [
                        'stuffProdId' => $id,
                        'cnt' => $cnt,
                        'idType' => $type
                    ]
                )->execute();
            }
        }
    }
    public function removeFromBalance($id,$type,$cnt){
        if($type == 1){
            $stuff = Yii::$app->db->createCommand("select * from stuff WHERE stuffId = ".$id)->queryOne();
            if($stuff["categoryId"] == 11){
                $struct = Yii::$app->db->createCommand("select * from struct WHERE stuffId = ".$id)->queryAll();
                foreach ($struct as $item) {
                    $this->removeFromBalance($item["stuffProdId"],$item["idType"],$item["cnt"]*$cnt);
                }
            }
            else{
                $model = Yii::$app->db->createCommand("select * from balance WHERE stuffProdId = ".$id." and idType = ".$type)->queryOne();
                if(!empty($model)){
                    Yii::$app->db->createCommand()->update("balance",
                        [
                            'cnt'=>$model["cnt"]-$cnt,
                        ],
                        "stuffProdId = ".$id." and idType = ".$type
                    )->execute();
                }
                else{
                    Yii::$app->db->createCommand()->insert("balance",
                        [
                            'stuffProdId' => $id,
                            'cnt'=>-$cnt,
                            'idType' => $type
                        ]
                    )->execute();
                }
            }
        }
        else {
            $model = Yii::$app->db->createCommand("select * from balance WHERE stuffProdId = " . $id . " and idType = " . $type)->queryOne();
            if (!empty($model)) {
                Yii::$app->db->createCommand()->update("balance",
                    [
                        'cnt' => $model["cnt"] - $cnt,
                    ],
                    "stuffProdId = " . $id . " and idType = " . $type
                )->execute();
            } else {
                Yii::$app->db->createCommand()->insert("balance",
                    [
                        'stuffProdId' => $id,
                        'cnt' => -$cnt,
                        'idType' => $type
                    ]
                )->execute();
            }
        }
    }

    public function removeStuffProd($id,$cnt){
        $model = Yii::$app->db->createCommand("select * from struct WHERE stuffId = ".$id)->queryAll();

        foreach ($model as $val){
            $this->removeFromBalance($val["stuffProdId"], $val["idType"], $val["cnt"]*$cnt);
        }
        $this->addToBalance($id,1,$cnt);

    }

    public function addStuffProd($id,$cnt){
        $model = Yii::$app->db->createCommand("select * from struct WHERE stuffId = ".$id)->queryAll();
        foreach ($model as $val){
            $this->addToBalance($val["stuffProdId"], $val["idType"], $val["cnt"]*$cnt);
        }
        $this->removeFromBalance($id,1,$cnt);

    }
}
