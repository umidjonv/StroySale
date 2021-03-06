<?php

namespace app\modules\calc\models;

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
 * @property Struct[] $structs
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

    public $convertibles = array();


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Наименование не может быть пустым'],
            [['measureId', 'categoryId'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['price'], 'number'],
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
            'price' => 'price',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStructs()
    {
        return $this->hasMany(Struct::className(), ['stuffProdId' => 'productId']);
    }

    public function getStruct()
    {
        return $this->hasMany(Struct::className(), ['stuffProdId' => 'stuffId']);
    }

    public function converting()
    {
        $cat = Category::find()->where(['categoryId'=>$this->categoryId])->one();

        if(isset($cat->convertible)) {
            $convertibles = explode(';', $cat->convertible);

            foreach ($this->convertibles as $conv)
            {
                foreach ($convertibles  as $dbconv)
                {
                    if($conv == $dbconv)

                        return $conv;
                }
            }
        }
        return null;
    }


}
