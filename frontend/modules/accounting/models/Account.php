<?php

namespace app\modules\accounting\models;

use app\models\Clients;
use app\modules\sold\models\Expense;
use Yii;

/**
 * This is the model class for table "account".
 *
 * @property int $accountId
 * @property int $expenseId
 * @property string $accountDate
 * @property double $summ
 * @property string $comment
 * @property int $accountType
 *
 * @property Expense $expense
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expenseId', 'accountType', 'clientId'], 'integer'],
            [['accountDate', 'summ', 'accountType'], 'required'],
            [['accountDate','serviceDate'], 'safe'],
            [['summ','cnt','byone'], 'number'],
            [['comment'], 'string', 'max' => 255],
            //[['expenseId'], 'exist', 'skipOnError' => true, 'targetClass' => Expense::className(), 'targetAttribute' => ['expenseId' => 'expenseId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'accountId' => 'Account ID',
            'expenseId' => 'Expense ID',
            'accountDate' => 'Account Date',
            'summ' => 'Summ',
            'comment' => 'Comment',
            'accountType' => 'Account Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
     
    public function AddClientSum($id,$sum){
        $model = Clients::findOne(['clientId' => $id]);
        $model->summ = $model->summ + $sum;
        $model->save();
    }
//    public function getExpense()
//    {
//        return $this->hasOne(Expense::className(), ['expenseId' => 'expenseId']);
//    }
}
