<?php

namespace ricefox\job\models;

use Yii;
use ricefox\behaviors\ActiveTag;
use yii\caching\TagDependency;
/**
 * This is the model class for table "job_group".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $has_child
 * @property string $path
 * @property integer $sort
 */

class JobGroup extends \ricefox\base\ActiveRecord
{
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
        return 'job_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id', 'has_child', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rf_job', 'ID'),
            'name' => Yii::t('rf_job', 'Name'),
            'parent_id' => Yii::t('rf_job', 'Parent ID'),
            'has_child' => Yii::t('rf_job', 'Has Child'),
            'path' => Yii::t('rf_job', 'Path'),
            'sort' => Yii::t('rf_job', 'Sort'),
        ];
    }

    public static function getItems()
    {
        $class=static::className();
        /** @var JobGroup $model */
        $model=new $class();
        $cache=$model->getCacheComponent();
        $cacheKey=$class.':items';
        $items=$cache->get($cacheKey);
        if($items===false){
            $array=$model->find()->orderBy('sort')->asArray()->all();
            $oneLevel=[];
            $twoLevel=[];
            foreach($array as $item){
                if($item['parent_id']==0){
                    $oneLevel[$item['id']]=$item['name'];
                }else{
                    $twoLevel[$item['parent_id']][$item['id']]=$item['name'];
                }
            }
            $items['one']=$oneLevel;
            $items['two']=$twoLevel;
            $cache->set($cacheKey,$items,0,new TagDependency(
                ['tags'=>ActiveTag::getCommonTag($model)]
            ));
        }
        return $items;
    }


}
