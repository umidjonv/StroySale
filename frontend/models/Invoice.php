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
 *
 * @property Provider $provider
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
            [['invoiceDate'], 'safe'],
            [['providerId'], 'integer'],
            [['transportType'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
            [['providerId'], 'exist', 'skipOnError' => true, 'targetClass' => Provider::className(), 'targetAttribute' => ['providerId' => 'providerId']],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvider()
    {
        return $this->hasOne(Provider::className(), ['providerId' => 'providerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceExes()
    {
        return $this->hasMany(InvoiceEx::className(), ['invoiceId' => 'invoiceId']);
    }
}
