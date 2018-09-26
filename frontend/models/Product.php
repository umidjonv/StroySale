<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $productId
 * @property string $name
 * @property int $measureId
 * @property int $categoryId
 *
 * @property InvoiceEx[] $invoiceExes
 * @property Category $category
 * @property Measure $measure
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['measureId', 'categoryId'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['categoryId' => 'categoryId']],
            [['measureId'], 'exist', 'skipOnError' => true, 'targetClass' => Measure::className(), 'targetAttribute' => ['measureId' => 'measureId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'productId' => 'Product ID',
            'name' => 'Name',
            'measureId' => 'Measure ID',
            'categoryId' => 'Category ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceExes()
    {
        return $this->hasMany(InvoiceEx::className(), ['productId' => 'productId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['categoryId' => 'categoryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeasure()
    {
        return $this->hasOne(Measure::className(), ['measureId' => 'measureId']);
    }
    
}
