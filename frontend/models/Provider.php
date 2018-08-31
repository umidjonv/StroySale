<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "provider".
 *
 * @property int $providerId
 * @property string $name
 * @property string $address
 */
class Provider extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'provider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            [['name', 'address'], 'string', 'max' => 255],
            [['providerId','name', 'address'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'providerId' => 'ID Поставщика',
            'name' => 'Имя',
            'address' => 'Адрес',
        ];
    }

    /**
     * @inheritdoc
     * @return ProviderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProviderQuery(get_called_class());
    }
}
