<?php

namespace ricefox\block\models;

use Yii;
use yii\caching\TagDependency;
use ricefox\behaviors\ActiveTag;
use ricefox\behaviors\ActiveTree;
use yii\helpers\ArrayHelper;
use ricefox\block\models\Module;

/**
 * This is the model class for table "category".
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
 * @property string $domain
 * @property string $uri
 * @property integer $uri_type
 * @property string $protocol
 * @property integer $suffix_slashes
 * @property integer $module_id
 * @property string $title_name
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property integer $show_type
 * @property string $related
 */

class Category extends \ricefox\base\ActiveRecord
{
    private static $_parentItems=[];
    private static $_items=[];
    // 更新时父栏目是否改变的表示
    public $parentIdChanged=false;
    // 更新时原父栏目的ID
    public $old_parent_id;
    public function init()
    {
        parent::init();
        if($this->related){
            $this->related=explode(',',$this->related);

        }
    }

    public function behaviors()
    {
        return [
            [
                'class'=>ActiveTag::className()
            ]
            ,
            [
                'class'=>ActiveTree::className()
            ]
        ];
    }

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
            [['name', 'module_id'], 'required'],
            [['parent_id', 'has_child', 'sort', 'status', 'module_id', 'show_type'], 'integer'],
            [['name'], 'string', 'max' => 25],
            [['path', 'url', 'children'], 'string', 'max' => 50],
            [['title_name'], 'string', 'max' => 14],
            [['title'], 'string', 'max' => 80],
            [['keywords'], 'string', 'max' => 120],
            [['description'], 'string', 'max' => 200],
            [['related'], 'string', 'max' => 255],
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

            'module_id' => Yii::t('rf_block', 'Module ID'),
            'title_name' => Yii::t('rf_block', 'Title Name'),
            'title' => Yii::t('rf_block', 'Title'),
            'keywords' => Yii::t('rf_block', 'Keywords'),
            'description' => Yii::t('rf_block', 'Description'),
            'show_type' => Yii::t('rf_block', 'Show Type'),
            'related' => Yii::t('rf_block', 'Related'),
        ];
    }

    public function beforeValidate()
    {
        if(is_array($this->related))$this->related=implode(',',$this->related);
        //$this->parent_id=(int)$this->parent_id;
        return true;
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

    public function getParents($moduleKey=null)
    {
        $items=$this->getArray($moduleKey);
        $disabled=[];
        if($this->id){
            $id=$this->id;
            foreach($items as $item){
                if(in_array($id,explode(',',$item['path']))){
                    $disabled[$item['id']]=['disabled'=>true];
                }
            }
        }
        $items=ArrayHelper::map($items,'id','name');
        return [$items,$disabled];
    }

    public static function getArray($moduleKey=null)
    {
        if(!isset(self::$_items[$moduleKey])){
            $class=static::className();
            /** @var Category $model */
            $model=new $class();
            $cache=$model->getCacheComponent();
            $cacheKey=$class.':items:'.$moduleKey;
            $tree=$cache->get($cacheKey);
            if($tree===false){
                $moduleId=$model->getModuleId($moduleKey);
                $array=$model->find()->where(['module_id'=>$moduleId])->orderBy('sort')->asArray()->all();
                if($array){
                    $tree=$model->createTree($array);
                }else{
                    $tree=[];
                }
                $cache->set($cacheKey,$tree,0,new TagDependency(
                    ['tags'=>ActiveTag::getCommonTag($class)]
                ));
            }
            self::$_items[$moduleKey]=$tree;
        }
        return self::$_items[$moduleKey];
    }

    public static function getItems($moduleKey=null)
    {
        $array=self::getArray($moduleKey);
        return ArrayHelper::map($array,'id','name');
    }

    public function getModuleId($moduleKey=null)
    {
        if(!$moduleKey){
            $moduleKey=Yii::$app->controller->module->id;
        }
        $module=Module::getByClassId($moduleKey);
        return $module['id'];
    }


    // 查找一个栏目的所有子孙栏目的ID
    public static function findDescendant($items,$id)
    {
        $descendant=[];
        foreach($items as $item){
            if(in_array($id,explode(',',$item['path'])) && $item['id']!=$id){
                $descendant[]=$item['id'];
            }
        }
        return implode(',',$descendant);
    }

    /**
     * 获取一个栏目，包含面包屑、标题路径等在前台显示的信息。
     * @param $categoryId
     * @return bool|mixed
     */
    public static function getCategory($categoryId)
    {
        if(!$categoryId)return false;
        $cache=Yii::$app->cache;
        $class=static::className();
        $cacheKey=$class.':category:'.$categoryId;
        $data=$cache->get($cacheKey);
        if($data===false){
            /** @var Category $model */
            $model=new $class();
            $array=$model->find()->where('status>=1')->orderBy('sort')->indexBy('id')->asArray()->all();
            // 面包屑导航 家电>冰箱>海尔
            $breads=[];
            // 用于标题的路径 海尔网_冰箱网_家电网_网站名
            $paths=[];
            // 存储一个栏目的子栏目导航
            $child=[];
            // 当前栏目对应的顶级栏目id。用于在主导航中标记当前激活的顶级栏目。
            $top=[];
            //一个栏目可能不只是显示自身栏目下的信息，还有可能显示子栏目、子孙栏目下的信息。
            //栏目有三种显示信息类型：本栏目，本栏目+子栏目，本栏目+子孙栏目
            $show=[];
            $relatedLink=[];
            foreach($array as $id=>$item)
            {
                $bread=[];
                $path=[];
                $arr=explode(',',$item['path']);
                foreach($arr as $i){
                    if(isset($array[$i])){
                        $bread[$i]=['url'=>$array[$i]['url'],'name'=>$array[$i]['name']];
                        $path[$i]=$array[$i]['title_name'];
                    }
                }
                $top[$id]=$arr[0];
                if($bread)$breads[$id]=$bread;
                if($path)$paths[$id]=$path;
                // 子栏目
                if($item['has_child'] && $item['children']){
                    $arr=explode(',',$item['children']);
                    $cat=[];
                    foreach($arr as $i){
                        if(isset($array[$i]))$cat[$i]=['url'=>$array[$i]['url'],'name'=>$array[$i]['name']];
                    }
                    if($cat)$child[$id]=$cat;
                }

                if(!$item['has_child']){
                    $show[$item['id']]=$item['id'];
                }else{
                    if($item['show_type']==1){//只显示本菜单
                        $show[$item['id']]=$item['id'];
                    }else if($item['show_type']==2){//显示本菜单+本菜单的子菜单
                        $show[$item['id']]=$item['id'].','.$item['children'];
                    }else if($item['show_type']==3){//显示本菜单+本菜单的子孙菜单
                        $show[$item['id']]=$item['id'].','.self::findDescendant($array,$item['id']);
                    }
                }
                $rows=RelatedLink::getByCategoryId($item['id']);
                if($rows){
                    $arr2=[];
                    foreach($rows as $row){
                        $arr2[$row['id']]=[
                            'name'=>$row['name'],
                            'url'=>$row['url'],
                            'color'=>$row['color']
                        ];
                        $relatedLink[$item['id']]=$arr2;
                    }
                }
            }
            if($breads){
                foreach($breads as $id=>$item){
                    $breads[$id]=\ricefox\widgets\FrontBreadcrumb::widget(['items'=>$item]);
                }
            }
            if($paths){
                foreach($paths as $id=>$item){
                    $paths[$id]=implode('_',array_reverse($item));
                }
            }
            foreach($array as &$item){
                // 面包屑导航
                if(isset($breads[$item['id']]))$item['breadcrumb']=$breads[$item['id']];
                // 标题路径
                if(isset($paths[$item['id']]))$item['paths']=$paths[$item['id']];
                // 子栏目。
                if(isset($child[$item['id']]))$item['childNav']=$child[$item['id']];
                // 顶级栏目id
                if(isset($top[$item['id']]))$item['topId']=$top[$item['id']];
                // 显示信息的栏目id
                if(isset($show[$item['id']]))$item['showId']=$show[$item['id']];
                if(isset($relatedLink[$item['id']]))$item['relatedLink']=$relatedLink[$item['id']];
            }
            foreach($array as $item){
                $cache->set($class.':category:'.$item['id'],$item,0,new TagDependency(
                    ['tags'=>ActiveTag::getCommonTag($class)]
                ));
            }
            if(isset($array[$categoryId]))$data=$array[$categoryId];
        }
        return $data;
    }

    /**
     * 网站主导航(状态为1的顶级栏目)
     */
    public static function getMainNav($limit=0)
    {
        $cache=Yii::$app->cache;
        $class=static::className();
        $cacheKey=$class.':mainNav';
        $data=$cache->get($cacheKey);
        if($data===false){
            /** @var Category $model */
            $model=new $class();
            $query=$model->find()->where('status=1 and parent_id=0')->orderBy('sort');
            if($limit){
                $query->limit($limit);
            }
            $array=$query->asArray()->all();
            $data=[];
            foreach($array as $item){
                $data[$item['id']]=['url'=>$item['url'],'name'=>$item['name'],'id'=>$item['id']];
            }
            $cache->set($cacheKey,$data,0,new TagDependency(
                ['tags'=>ActiveTag::getCommonTag($class)]
            ));
        }
        return $data;
    }


}
