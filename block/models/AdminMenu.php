<?php

namespace ricefox\block\models;

use Yii;
use ricefox\behaviors\ActiveTree;
use ricefox\behaviors\ActiveTag;
use yii\helpers\ArrayHelper;
use yii\caching\TagDependency;

/**
 * This is the model class for table "admin_menu".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $has_child
 * @property string $path
 * @property string $url
 * @property integer $sort
 * @property string $children
 * @property integer $status
 */

class AdminMenu extends \ricefox\base\ActiveRecord
{
    public $parentIdChanged=false;
    public $old_parent_id;
    public function behaviors()
    {
        return [
            [
                'class'=>ActiveTree::className()
            ],
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
        return 'admin_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            [['parent_id', 'has_child', 'sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 25],
            [['path', 'url', 'children'], 'string', 'max' => 50],
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
            'parent_id' => Yii::t('rf_block', 'Parent ID'),
            'has_child' => Yii::t('rf_block', 'Has Child'),
            'path' => Yii::t('rf_block', 'Path'),
            'url' => Yii::t('rf_block', 'Url'),
            'sort' => Yii::t('rf_block', 'Sort'),
            'children' => Yii::t('rf_block', 'Children'),
            'status' => Yii::t('rf_block', 'Status'),
        ];
    }
    public function beforeSave($insert)
    {
        $this->sort=(int)$this->sort;
        $this->parent_id=(int)$this->parent_id;
        return parent::beforeSave($insert);
    }

    /**
     * 对一个栏目的子栏目进行排序
     * @param $v1
     * @param $v2
     * @return int
     */
    public function sortChild($v1,$v2)
    {
        $int=$v1['sort']-$v2['sort'];
        return $int;
    }

    public function afterSave($insert,$changedAttributes)
    {
        //添加操作。
        if($insert){

            $this->path=$this->id;
            if(!$this->sort){
                $this->sort=$this->id;
            }
            $this->save(false);
            if($this->parent_id){
                $parent=$this->findOne($this->parent_id);
                if(!$parent->has_child){
                    $parent->has_child=1;
                }
                $children=explode(',',$parent->children);
                if(!in_array($this->id,$children)){
                    $children[]=$this->id;
                }
                // 对父栏目的子类重新排序。
                if(count($children)>1){
                    $childrenArray=$this->find()->select('id,sort')->orderBy('sort')->where(['id'=>$children])->indexBy('id')->all();
                    uasort($childrenArray,array($this,'sortChild'));
                    $children=array_keys($childrenArray);
                    $parent->children=implode(',',$children);
                }
                $parent->save(false);
                $this->path=$parent->path.','.$this->id;
                $this->save(false);
            }
        }
        // 更新操作。
        else
        {
            if($this->parentIdChanged){
                $this->parentIdChanged=false;
                // 新的父类
                if($this->parent_id){
                    $parent=$this->findOne($this->parent_id);
                    if(!$parent->has_child){
                        $parent->has_child=1;
                    }
                    $children=explode(',',$parent->children);
                    if(!in_array($this->id,$children)){
                        $children[]=$this->id;
                    }
                    // 对父类的子类重新排序。
                    if(count($children)>1){
                        $childrenArray=$this->find()->select('id,sort')->orderBy('sort')->where(['id'=>$children])->indexBy('id')->all();
                        uasort($childrenArray,array($this,'sortChild'));
                        $children=array_keys($childrenArray);
                        $parent->children=implode(',',$children);
                    }
                    $parent->save(false);
                    $this->path=$parent->path.','.$this->id;
                    $this->save(false);
                }
                else{
                    $this->path=$this->id;
                    $this->save(false);
                }
                // 如果原先有父类，则删除在父类children中的ID
                if($this->old_parent_id){
                    $parent=$this->findOne($this->old_parent_id);
                    $children=explode(',',$parent->children);
                    if(($index=array_search($this->id,$children))){
                        unset($children[$index]);
                    }
                    $parent->children=implode(',',$children);
                    $parent->save(false);
                }

            }
        }
        parent::afterSave($insert,$changedAttributes);
    }

    public function getParents()
    {
        $model=$this;
        $cache=$model->getCacheComponent();
        $class=$this->className();
        $cacheKey=$class.':parents';
        $parents=$cache->get($cacheKey);
        if($parents===false){
            $array=$model->find()->orderBy('sort')->asArray()->all();
            $disabled=[];
            $tree=[];
            if($array){
                $tree=$model->createTree($array);
                if($model->id){
                    $id=$model->id;
                    foreach($tree as $item){
                        if(in_array($id,explode(',',$item['path']))){
                            $disabled[$item['id']]=['disabled'=>true];
                        }
                    }
                }
            }
            $items=ArrayHelper::map($tree,'id','name');
            $parents=[$items,$disabled];
            $cache->set($cacheKey,$parents,0,new TagDependency(
                ['tags'=>ActiveTag::getCommonTag($class)]
            ));
        }
        return $parents;
    }
    public function getMenus()
    {
        $class=$this->className();
        /** @var AdminMenu $model */
        $model=$this;
        $cache=$model->getCacheComponent();
        $cacheKey=$class.':menus';
        $menus=$cache->get($cacheKey);
        if($menus===false){
            $menus=[];
            $array=$model->find()->orderBy('sort')->indexBy('id')->asArray()->all();
            if($array){
                $model->symbolLevel=1;
                $menus=$model->createTree($array);
            }
            $cache->set($cacheKey,$menus,0,new TagDependency(
                ['tags'=>ActiveTag::getCommonTag($class)]
            ));
        }
        return $menus;
    }
    /** 未使用 */
    public function getByParentId($parentId=0)
    {
        $class=$this->className();
        /** @var AdminMenu $model */
        $model=$this;
        $cache=$model->getCacheComponent();
        $cacheKey=$class.':byParentId:'.$parentId;
        $items=$cache->get($cacheKey);
        if($items===false){
            $items=$model->find()->where(['parent_id'=>$parentId])->orderBy('sort')->indexBy('id')->asArray()->all();
            $cache->set($cacheKey,$items,0,new TagDependency(
                ['tags'=>ActiveTag::getCommonTag($class)]
            ));
        }
        return $items;
    }
    /** 未使用 */
    public function getMenusById($id)
    {
        $class=$this->className();
        /** @var AdminMenu $model */
        $model=$this;
        $cache=$model->getCacheComponent();
        $cacheKey=$class.':menusById:'.$id;
        $items=$cache->get($cacheKey);
        if($items===false){

            $array=$model->find()->where(['like','path',$id])->orderBy('sort')->indexBy('id')->asArray()->all();

            $cache->set($cacheKey,$items,0,new TagDependency(
                ['tags'=>ActiveTag::getCommonTag($class)]
            ));
        }
        return $items;
    }

}
