<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoiceEx".
 *
 * @property int $invoiceExId
 * @property int $productId
 * @property double $cnt
 * @property int $invoiceId
 *
 * @property Invoice $invoice
 * @property Product $product
 */
class InvoiceEx extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoiceEx';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'invoiceId'], 'integer'],
            [['cnt'], 'number'],
            [['invoiceId'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoiceId' => 'invoiceId']],
            [['productId'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['productId' => 'productId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoiceExId' => 'Invoice Ex ID',
            'productId' => 'Product ID',
            'cnt' => 'Cnt',
            'invoiceId' => 'Invoice ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['invoiceId' => 'invoiceId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['productId' => 'productId']);
    }
}
