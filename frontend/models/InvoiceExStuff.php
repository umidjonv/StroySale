<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoiceExStuff".
 *
 * @property int $invoiceExStuffId
 * @property int $stuffId
 * @property double $cnt
 * @property int $invoiceStuffId
 *
 * @property Stuff $stuff
 * @property InvoiceStuff $invoiceStuff
 */
class InvoiceExStuff extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoiceExStuff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stuffId', 'invoiceStuffId'], 'integer'],
            [['cnt'], 'number'],
            [['stuffId'], 'exist', 'skipOnError' => true, 'targetClass' => Stuff::className(), 'targetAttribute' => ['stuffId' => 'stuffId']],
            [['invoiceStuffId'], 'exist', 'skipOnError' => true, 'targetClass' => InvoiceStuff::className(), 'targetAttribute' => ['invoiceStuffId' => 'invoiceStuffId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoiceExStuffId' => 'Invoice Ex Stuff ID',
            'stuffId' => 'Stuff ID',
            'cnt' => 'Cnt',
            'invoiceStuffId' => 'Invoice Stuff ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStuff()
    {
        return $this->hasOne(Stuff::className(), ['stuffId' => 'stuffId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceStuff()
    {
        return $this->hasOne(InvoiceStuff::className(), ['invoiceStuffId' => 'invoiceStuffId']);
    }
}
