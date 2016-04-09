<?php

namespace ricefox\job\models;

use Yii;
use ricefox\behaviors\ActiveTag;
use yii\caching\TagDependency;
/**
 * This is the model class for table "job".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $name
 * @property integer $sort
 */

class Job extends \ricefox\base\ActiveRecord
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
        return 'job';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'name'], 'required'],
            [['group_id', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rf_job', 'ID'),
            'group_id' => Yii::t('rf_job', 'Group ID'),
            'name' => Yii::t('rf_job', 'Name'),
            'sort' => Yii::t('rf_job', 'Sort'),
        ];
    }

    public static function getAllJobs()
    {
        $class=static::className();
        /** @var Job $model */
        $model=new $class();
        $cache=$model->getCacheComponent();
        $cacheKey=$class.':allJobs';
        $items=$cache->get($cacheKey);
        if($items===false){
            $groups=JobGroup::find()->orderBy('sort')->asArray()->all();
            $top=[];
            $array=$model->find()->orderBy('sort')->asArray()->all();
            $items=[];
            foreach($array as $item){
                $items[$item['group_id']][$item['id']]=$item['name'];
            }
            foreach($groups as $group){
                if($group['parent_id']==0){
                    $top[$group['id']]=$group;
                }
            }
            foreach($groups as &$group){
                if(isset($items[$group['id']])){
                    $group['jobs']=$items[$group['id']];
                }
            }
            foreach($top as &$item){
                foreach($groups as $group){
                    if($group['parent_id']==$item['id']){
                        $item['sub'][$group['id']]=$group;
                    }
                }
            }
            $cache->set($cacheKey,$top,0,new TagDependency(
                ['tags'=>ActiveTag::getCommonTag($model)]
            ));
            $items=&$top;
        }
        return $items;
    }


}
