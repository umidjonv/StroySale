<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clients".
 *
 * @property int $clientId
 * @property string $clientName
 * @property string $inn
 * @property string $bank
 * @property string $address
 * @property string $ogrn
 * @property string $schet
 */
class Clients extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inn', 'bank', 'address', 'ogrn', 'schet'], 'required'],
            [['schet'], 'number'],
            [['clientName'], 'string', 'max' => 255],
            [['inn', 'ogrn'], 'string', 'max' => 200],
            [['bank', 'address'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'clientId' => 'Client ID',
            'clientName' => 'Client Name',
            'inn' => 'Inn',
            'bank' => 'Bank',
            'address' => 'Address',
            'ogrn' => 'Ogrn',
            'schet' => 'Schet',
        ];
    }


}
