<?php

namespace app\modules\accounting\models;

use Yii;

/**
 * This is the model class for table "point".
 *
 * @property int $pointId
 * @property string $name
 * @property double $summ
 * @property int $status
 *
 * @property Account[] $accounts
 */
class Point extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'point';
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pointId' => 'Point ID',
            'name' => 'Name',
            'summ' => 'Summ',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['pointId' => 'pointId']);
    }
}
