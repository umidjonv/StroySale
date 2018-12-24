<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoiceStuff".
 *
 * @property int $invoiceStuffId
 * @property string $invoiceDate
 * @property string $description
 *
 * @property InvoiceExStuff[] $invoiceExStuffs
 */
class InvoiceStuff extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoiceStuff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoiceDate'], 'safe'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoiceStuffId' => 'Invoice Stuff ID',
            'invoiceDate' => 'Invoice Date',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceExStuffs()
    {
        return $this->hasMany(InvoiceExStuff::className(), ['invoiceStuffId' => 'invoiceStuffId']);
    }
}
