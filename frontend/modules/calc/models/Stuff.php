<?php

namespace app\modules\calc\models;

use Yii;

/**
 * This is the model class for table "stuff".
 *
 * @property int $stuffId
 * @property string $name
 * @property int $measureId
 * @property int $salary
 * @property int $energy
 *
 * @property Struct[] $structs
 * @property Measure $measure
 */
class Stuff extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stuff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['measureId', 'salary', 'energy'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['measureId'], 'exist', 'skipOnError' => true, 'targetClass' => Measure::className(), 'targetAttribute' => ['measureId' => 'measureId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stuffId' => 'Stuff ID',
            'name' => 'Name',
            'measureId' => 'Measure ID',
            'salary' => 'Salary',
            'energy' => 'Energy',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStructs()
    {
        return $this->hasMany(Struct::className(), ['stuffProdId' => 'stuffId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeasure()
    {
        return $this->hasOne(Measure::className(), ['measureId' => 'measureId']);
    }
}
