<?php

namespace ricefox\block\models;

use Yii;
use yii\caching\TagDependency;
use ricefox\behaviors\ActiveTag;

/**
 * This is the model class for table "footer_link".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $url
 * @property string $name
 * @property integer $sort
 * @property string $image_url
 * @property string $color
 * @property integer $category_cond
 */

class FooterLink extends \ricefox\base\ActiveRecord
{
    public $module_id;
    public function behaviors()
    {
        return [
            'class'=>ActiveTag::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'footer_link';
    }
    public function beforeValidate()
    {
        $this->category_id=(int)$this->category_id;
        return parent::beforeValidate();
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'name'], 'required'],
            [['category_id', 'sort', 'category_cond'], 'integer'],
            [['url', 'image_url'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 25],
            [['color'], 'string', 'max' => 20],
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
            'sort' => 'Sort',
            'image_url' => 'Image Url',
            'color' => 'Color',
            'category_cond' => 'Category Cond',
        ];
    }

    public static function getLinksByCategoryId($categoryId,$showId='')
    {
        $cache=Yii::$app->cache;
        $class=static::className();
        $cacheKey=$class.':byCategoryId:'.$categoryId;
        $data=$cache->get($cacheKey);
        if($data===false){
            /** @var FooterLink $model */
            $model=new $class();
            $query=$model->find()->select('url,name,color');
            if($categoryId && $showId){
                $cond=strpos($showId,',')===false ? 'category_id='.$categoryId : 'category_id in ('.$showId.')';
                $cond=$cond.' or category_cond=0';
                $query->where($cond);
            }else{
                $query->where('category_id=0');
            }
            $rows=$query->orderBy('sort')->asArray()->all();
            $cache->set($cacheKey,$rows,0,new TagDependency(
                ['tags'=>ActiveTag::getCommonTag($class)]
            ));
            return $rows;
        }
        return $data;
    }


}
