<?php

namespace ricefox\block\models;

use Yii;

/**
 * This is the model class for table "related_link".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $url
 * @property string $name
 * @property string $color
 * @property integer $sort
 */

class RelatedLink extends \ricefox\base\ActiveRecord
{
    public $module_id;
    public function behaviors()
    {
        return [

        ];
    }
    public function getCategory()
    {
        return $this->hasOne(Category::className(),['id'=>'category_id']);
    }
    public function beforeValidate()
    {
        $this->category_id=(int)$this->category_id;
        return parent::beforeValidate();
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'related_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'name'], 'required'],
            [['category_id', 'sort'], 'integer'],
            [['url'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 25],
            [['color'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'url' => 'Url',
            'name' => 'Name',
            'color' => 'Color',
            'sort' => 'Sort',
        ];
    }

    public static function getByCategoryId($id)
    {
        $rows=RelatedLink::find()->where('category_id='.$id)->orderBy('sort')->asArray()->all();
        return $rows;
    }
}
