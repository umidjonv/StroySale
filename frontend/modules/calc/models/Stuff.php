<?php

namespace app\modules\calc\models;

use Yii;
use app\models\Category;

/**
 * This is the model class for table "stuff".
 *
 * @property int $stuffId
 * @property string $name
 * @property int $measureId
 * @property int $salary
 * @property int $energy
 * @property int $price
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
            ['name', 'required', 'message' => 'Наименование не может быть пустым'],
            ['price', 'required', 'message' => 'Цена не может быть пустым '],
            [['measureId', 'salary', 'energy', 'price'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['measureId'], 'exist', 'skipOnError' => true, 'targetClass' => Measure::className(), 'targetAttribute' => ['measureId' => 'measureId']],
            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['categoryId' => 'categoryId']],
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
            'price' => 'Price',
            'categoryId' => 'Category ID'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStructs()
    {
        return $this->hasMany(Struct::className(), ['stuffId' => 'stuffId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeasure()
    {
        return $this->hasOne(Measure::className(), ['measureId' => 'measureId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['categoryId' => 'categoryId']);
    }
}
