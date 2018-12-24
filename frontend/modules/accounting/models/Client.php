<?php

namespace app\modules\accounting\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property int $clientId
 * @property string $name
 * @property double $summ
 * @property int $status
 * @property string $phone
 *
 * @property Account[] $accounts
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['summ'], 'number'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'clientId' => 'Client ID',
            'name' => 'Name',
            'summ' => 'Summ',
            'status' => 'Status',
            'phone' => 'Phone',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['clientId' => 'clientId']);
    }
}
