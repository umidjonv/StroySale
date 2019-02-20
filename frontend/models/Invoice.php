<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property int $invoiceId
 * @property string $invoiceDate
 * @property string $transportType
 * @property string $description
 * @property int $providerId
 * @property string $deliveryDate
 * @property double $invoiceSumm
 * @property string $driver
 * @property string $phone
 *
 * @property Clients $provider
 * @property InvoiceEx[] $invoiceExes
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deliveryDate'], 'required', 'message' => 'Дата поставки объязательно к заполнению'],
            [['invoiceDate', 'deliveryDate'], 'safe'],
            [['providerId'], 'integer'],
            [['expNum'], 'string', 'max' => 11],
            [['dogNum'], 'string', 'max' => 11],
            [['invoiceSumm'], 'number'],
            [['transportType'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
            [['driver'], 'string', 'max' => 200],
            [['phone'], 'string', 'max' => 100],
            [['carNumber'], 'string', 'max' => 50],
            [['providerId'], 'exist', 'skipOnError' => true, 'targetClass' => Clients::className(), 'targetAttribute' => ['providerId' => 'clientId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoiceId' => 'Invoice ID',
            'invoiceDate' => 'Invoice Date',
            'transportType' => 'Transport Type',
            'description' => 'Description',
            'providerId' => 'Provider ID',
            'deliveryDate' => 'Delivery Date',
            'invoiceSumm' => 'Invoice Summ',
            'driver' => 'Driver',
            'phone' => 'Phone',
            'carNumber' => 'Car number',
            'expNum' => 'Expense number',
            'dogNum' => 'Dogovor number',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvider()
    {
        return $this->hasOne(Clients::className(), ['clientId' => 'providerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceExes()
    {
        return $this->hasMany(InvoiceEx::className(), ['invoiceId' => 'invoiceId']);
    }
}
