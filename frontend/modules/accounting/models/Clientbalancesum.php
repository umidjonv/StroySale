<?php

namespace app\modules\accounting\models;

use app\models\Clients;
use Yii;

/**
 * This is the model class for table "clientbalancesum".
 *
 * @property int $clientbalancesumId
 * @property string $clientbalancesumDate
 * @property int $clientId
 * @property double $summ
 *
 * @property Clients $client
 */
class Clientbalancesum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clientbalancesum';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clientbalancesumDate'], 'safe'],
            [['clientId'], 'integer'],
            [['summ'], 'number'],
            //[['clientId'], 'exist', 'skipOnError' => true, 'targetClass' => Clients::className(), 'targetAttribute' => ['clientId' => 'clientId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'clientbalancesumId' => 'Clientbalancesum ID',
            'clientbalancesumDate' => 'Clientbalancesum Date',
            'clientId' => 'Client ID',
            'summ' => 'Summ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Clients::className(), ['clientId' => 'clientId']);
    }

    public function getSum($date,$id){
        $balance = Clientbalancesum::find()->where('clientbalancesumDate <= :date AND clientId = :id',[':date' => $date,':id'=>$id])->orderBy("clientbalancesumDate desc")->one();
        return (empty($balance)) ? 0 : $balance->summ;
    }
}
