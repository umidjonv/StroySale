<?php

namespace app\modules\calc\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $categoryId
 * @property string $name
 * @property string $convertible
 * @property Product[] $products
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Наименование не может быть пустым'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'categoryId' => 'Category ID',
            'name' => 'Name',
            'convertible' => 'convertible',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['categoryId' => 'categoryId']);
    }

    public function getCategoryName($categoryId){
        $models = $this::findOne($categoryId);
        return $models->name;
    }
}
