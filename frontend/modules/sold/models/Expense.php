<?php

namespace app\modules\sold\models;

use Yii;

/**
 * This is the model class for table "expense".
 *
 * @property int $expenseId
 * @property string $expenseDate
 * @property int $debt
 * @property string $comment
 * @property int $clientId
 * @property int $fakt
 * @property int $expType
 * @property int $transfer
 * @property int $inCash
 * @property int $terminal
 * @property int $expSum
 * @property int $status
 * @property int $userId
 * @property int $paidType
 * @property int $charge
 * @property int $fromId
 * @property string $dogNum
 */
class Expense extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expense';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expenseDate'], 'safe'],
            [['debt', 'fakt', 'status'], 'required'],
            [['debt', 'clientId', 'fakt', 'expType', 'transfer', 'inCash', 'terminal', 'expSum', 'status', 'userId', 'paidType', 'charge'], 'integer'],
            [['comment'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'expenseId' => 'Номер накладной',
            'expenseDate' => 'Дата',
            'debt' => 'Долг',
            'dogNum' => 'Номер договора',
            'comment' => 'Комментарий',
            'clientId' => 'Клиент',
            'fakt' => 'Факт',
            'expType' => 'Тип',
            'transfer' => 'Перечисление',
            'inCash' => 'Наличные',
            'terminal' => 'Терминал',
            'expSum' => 'Сумма',
            'status' => 'Статус',
            'userId' => 'Пользователь',
            'paidType' => 'Тип оплаты',
            'charge' => 'Наценка',
            'from' => 'От имени',

        ];
    }
    
    public function getClient()
    {
        return $this->hasOne(\app\models\Clients::className(), ['clientId' => 'clientId']);
    }

    public function getDelivery()
    {
        return $this->hasOne(Delivery::className(), ['expenseId' => 'expenseId']);
    }

    public function getOrders()
    {
        return $this->hasMany(\app\modules\sold\models\Orders::className(), ['expenseId' => 'expenseId']);
    }

    public function getFrom()
    {
        return $this->hasOne(\app\models\From::className(), ['fromId' => 'fromId']);
    }

}
