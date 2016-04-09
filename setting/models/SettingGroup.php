<?php

namespace ricefox\setting\models;

use Yii;
use ricefox\behaviors\ActiveTag;
use yii\caching\TagDependency;
/**
 * This is the model class for table "setting_group".
 *
 * @property integer $id
 * @property string $key_name
 * @property string $name
 * @property integer $preload
 * @property integer $sort
 */

class SettingGroup extends \ricefox\base\ActiveRecord
{
    private static $_items;

    public function behaviors()
    {
        return [
            [
                'class'=>ActiveTag::className(),
                'cache'=>$this->cache
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setting_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key_name', 'name'], 'required'],
            [['preload', 'sort'], 'integer'],
            [['key_name'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rf_setting', 'ID'),
            'key_name' => Yii::t('rf_setting', 'Key Name'),
            'name' => Yii::t('rf_setting', 'Name'),
            'preload' => Yii::t('rf_setting', 'Preload'),
            'sort' => Yii::t('rf_setting', 'Sort'),
        ];
    }

    public static function getItems()
    {
        if(!self::$_items){
            $class=static::className();
            /** @var \ricefox\base\ActiveRecord $model */
            $model=new $class();
            $cache=$model->getCacheComponent();
            $cacheKey=static::className().':items';
            $items=$cache->get($cacheKey);
            if($items===false){
                $array=$model->find()->orderBy('sort')->asArray()->all();
                foreach($array as $item){
                    $items[$item['id']]=$item['name'].'['.$item['key_name'].']';
                }
                $cache->set($cacheKey,$items,0,new TagDependency(
                    ['tags'=>ActiveTag::getCommonTag($model)]
                ));
            }
            self::$_items=$items;
        }
        return self::$_items;
    }
    public static function getGroupByKey($keyName)
    {
        $groups=static::getGroups();
        return isset($groups[$keyName])?$groups[$keyName]:null;
    }
    public static function getGroups($index='key_name')
    {
        $class=static::className();
        /** @var \ricefox\base\ActiveRecord $model */
        $model=new $class();
        $cache=$model->getCacheComponent();
        $cacheKey=static::className().':groups:'.$index;
        $items=$cache->get($cacheKey);
        if($items===false){
            $array=$model->find()->orderBy('sort')->asArray()->all();
            foreach($array as $item){
                $items[$item[$index]]=$item;
            }
            $cache->set($cacheKey,$items,0,new TagDependency(
                ['tags'=>ActiveTag::getCommonTag($model)]
            ));
        }
        return $items;
    }


}
