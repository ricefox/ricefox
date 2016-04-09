<?php

namespace ricefox\block\models;

use Yii;
use yii\base\Exception;
use yii\caching\TagDependency;
use ricefox\behaviors\ActiveTag;
/**
 * This is the model class for table "module".
 *
 * @property integer $id
 * @property string $name
 * @property string $class_id
 * @property string $class
 * @property integer $status
 * @property integer $sort
 */

class Module extends \ricefox\base\ActiveRecord
{
    private static $_items;

    public function behaviors()
    {
        return [
            [
                'class'=>ActiveTag::className()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'module';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class_id', 'class'], 'required'],
            [['status', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 14],
            [['class_id'], 'string', 'max' => 50],
            [['class'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rf_block', 'ID'),
            'name' => Yii::t('rf_block', 'Name'),
            'class_id' => Yii::t('rf_block', 'Class ID'),
            'class' => Yii::t('rf_block', 'Class'),
            'status' => Yii::t('rf_block', 'Status'),
            'sort' => Yii::t('rf_block', 'Sort'),
        ];
    }

    public static function getItems()
    {
        if(!static::$_items){
            $class=static::className();
            /** @var \ricefox\base\ActiveRecord $model */
            $model=new $class();
            $cache=$model->getCacheComponent();
            $cacheKey=$class.':items';
            $items=$cache->get($cacheKey);
            if($items===false){
                $array=$model->find()->orderBy('sort')->asArray()->all();
                foreach($array as $item){
                    $items[$item['id']]=$item['name'].'['.$item['class_id'].']';
                }
                $cache->set($cacheKey,$items,0,new TagDependency(
                    ['tags'=>ActiveTag::getCommonTag($model)]
                ));
            }
            self::$_items=$items;
        }
        return self::$_items;
    }

    public static function getByClassId($classId)
    {
        $class=static::className();
        /** @var \ricefox\base\ActiveRecord $model */
        $model=new $class();
        $cache=$model->getCacheComponent();
        $cacheKey=$class.':byClassId:'.$classId;
        $item=$cache->get($cacheKey);
        if($item===false){
            $item=$model->find()->where(['class_id'=>$classId])->one();
            if(!$item){
                throw new Exception('Cannot find module by module class id:'.$classId);
            }
            $cache->set($cacheKey,$item,0,new TagDependency(
                ['tags'=>ActiveTag::getCommonTag($model)]
            ));
        }
        return $item;
    }

}
