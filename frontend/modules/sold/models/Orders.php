<?php

namespace app\modules\sold\models;

use app\modules\calc\models\Product;
use app\modules\calc\models\Stuff;
use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $orderId
 * @property int $expenseId
 * @property int $stuffProdId
 * @property double $packCount
 * @property double $faktCount
 * @property double $orderSumm
 * @property int $idType
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expenseId', 'stuffProdId'], 'required'],
            [['expenseId', 'stuffProdId', 'idType','addition'], 'integer'],
            [['packCount', 'orderSumm', 'faktCount','additionCnt'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'orderId' => 'Order ID',
            'expenseId' => 'Expense ID',
            'stuffProdId' => 'Product ID',
            'packCount' => 'Pack Count',
            'faktCount' => 'Fakt count',
            'orderSumm' => 'Order Summ',
            'idType' => 'TypeId',
        ];
    }
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['productId' => 'stuffProdId']);
    }

    public function getStuff()
    {
        return $this->hasOne(Stuff::className(), ['stuffId' => 'stuffProdId']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */

}
