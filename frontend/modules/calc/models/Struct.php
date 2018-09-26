<?php

namespace app\modules\calc\models;

use Yii;

/**
 * This is the model class for table "struct".
 *
 * @property int $structId
 * @property int $stuffId
 * @property int $stuffProdId
 * @property double $cnt
 * @property bool $idType
 *
 * @property Product $stuffProd
 * @property Stuff $stuff
 */
class Struct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'struct';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stuffId', 'stuffProdId'], 'integer'],
            [['cnt'], 'number'],
            [['idType'], 'boolean'],
            [['stuffId'], 'exist', 'skipOnError' => true, 'targetClass' => Stuff::className(), 'targetAttribute' => ['stuffId' => 'stuffId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'structId' => 'Struct ID',
            'stuffId' => 'Stuff ID',
            'stuffProdId' => 'Stuff Prod ID',
            'cnt' => 'Cnt',
            'idType' => 'Id Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStuffProd()
    {
        return $this->hasOne(Product::className(), ['productId' => 'stuffProdId']);
    }

    public function getStuffStuff()
    {
        return $this->hasOne(Stuff::className(), ['stuffId' => 'stuffProdId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStuff()
    {
        return $this->hasOne(Stuff::className(), ['stuffId' => 'stuffId']);
    }
}
