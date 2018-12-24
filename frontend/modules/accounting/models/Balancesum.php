<?php

namespace app\modules\accounting\models;

use Yii;

/**
 * This is the model class for table "balancesum".
 *
 * @property int $balancesumId
 * @property string $balancesumDate
 * @property int $summ
 */
class Balancesum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'balancesum';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balancesumDate'], 'required'],
            [['balancesumDate'], 'safe'],
            [['summ'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'balancesumId' => 'Balancesum ID',
            'balancesumDate' => 'Balancesum Date',
            'summ' => 'Summ',
        ];
    }

    public function getSum($date){
        $balance = Balancesum::find()->where('balancesumDate <= :date',[':date' => $date])->orderBy("balancesumDate desc")->one();
        return (empty($balance)) ? 0 : $balance->summ;
    }
}
