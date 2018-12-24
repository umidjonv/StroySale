<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.10.2018
 * Time: 8:05
 */
namespace app\models;
use app\models;
use app\modules\sold\models\Expense;

class ReportQuery extends \yii\db\ActiveQuery
{

    public function all($db = null)
    {
        return parent::all($db);
    }

    public function findExpensebyclients($clientId, $productId, $type)
    {
        //Expense::find()->where([''])
    }

}