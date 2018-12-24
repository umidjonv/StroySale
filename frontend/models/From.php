<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "from".
 *
 * @property int $fromId
 * @property string $kod
 * @property string $fromName
 * @property string $director
 * @property string $address
 * @property string $schet
 * @property string $inn
 * @property string $okpo
 * @property string $tel
 * @property string $dop
 */
class From extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'from';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fromName', 'address', 'inn', 'okpo', 'tel', 'dop'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fromId' => 'From ID',
            'kod' => 'Kod',
            'fromName' => 'From Name',
            'director' => 'Director',
            'address' => 'Address',
            'schet' => 'schet',
            'inn' => 'Inn',
            'okpo' => 'Okpo',
            'tel' => 'Tel',
            'dop' => 'Dop',
        ];
    }
}
