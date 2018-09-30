<?php

namespace app\modules\calc\models;

use Yii;

/**
 * This is the model class for table "price".
 *
 * @property int $priceId
 * @property string $priceDate
 * @property double $price
 * @property int $stuffProdId
 * @property int $idType
 */
class Price extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['priceDate'], 'safe'],
            [['price'], 'number'],
            [['stuffProdId', 'idType'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'priceId' => 'Price ID',
            'priceDate' => 'Price Date',
            'price' => 'Price',
            'stuffProdId' => 'Stuff Prod ID',
            'idType' => 'Id Type',
        ];
    }

    public function setPrice($price,$idType,$stuffProdId){

        $this->price = $price;
        $this->idType = $idType;
        $this->stuffProdId = $stuffProdId;
        $this->priceDate = date("Y-m-d H:i:s");
        $this->save();
    }
}
