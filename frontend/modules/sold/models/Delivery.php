<?php

namespace app\modules\sold\models;

use Yii;

/**
 * This is the model class for table "delivery".
 *
 * @property int $expenseId
 * @property int $deliveryType
 * @property string $name
 * @property int $description
 * @property int $price
 * @property string $address
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expenseId'], 'required'],
            [['expenseId', 'price'], 'integer'],
            [['deliveryType'], 'string', 'max' => 1],
            [['name', 'address', 'description'], 'string', 'max' => 200],
            [['expenseId'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'expenseId' => 'Expense ID',
            'deliveryType' => 'Delivery Type',
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'address' => 'Address',
        ];
    }

    public function getExpense()
    {
        return $this->hasOne(Expense::className(), ['expenseId' => 'expenseId']);
    }
}
