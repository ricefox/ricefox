<?php

namespace ricefox\setting\models;

use Yii;
use ricefox\behaviors\ActiveTag;
use yii\caching\TagDependency;
/**
 * This is the model class for table "setting".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $key_name
 * @property string $name
 * @property string $value
 * @property string $type
 */

class Setting extends \ricefox\base\ActiveRecord
{
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
        return 'setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'key_name', 'name'], 'required'],
            [['group_id'], 'integer'],
            [['value'], 'validateSettingValue'],
            [['key_name'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 25],
            [['type'], 'string', 'max' => 12],
            [['key_name'], 'unique'],

        ];
    }
    public function validateSettingValue($attr)
    {
        if(!trim($this->$attr)){
            $label=$this->getAttributeLabel($attr);
            $this->addError($attr,$label.'不能为空');
            return false;
        }
        return true;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rf_setting', 'ID'),
            'group_id' => Yii::t('rf_setting', 'Group ID'),
            'key_name' => Yii::t('rf_setting', 'Key Name'),
            'name' => Yii::t('rf_setting', 'Name'),
            'value' => Yii::t('rf_setting', 'Value'),
            'type' => Yii::t('rf_setting', 'Type'),
        ];
    }

    public static function getTypes()
    {
        return [
            'input'=>'文本框[input]',
            'textarea'=>'文本[textarea]',
            'select'=>'下拉框[select]',
            'radio'=>'单选框[radio]',
            'checkbox'=>'多选框[checkbox]',
            'number'=>'数字[number]'
        ];
    }

    public function getPreload()
    {
        /** @var \ricefox\base\ActiveRecord $model */
        $model=$this;
        $class=$model->className();
        $cache=$model->getCacheComponent();
        $cacheKey=$class.':preload';
        $array=$cache->get($cacheKey);
        if($array===false){

            $groups=SettingGroup::find()->where('preload=1')->orderBy('sort')->asArray()->all();
            if($groups){
                foreach($groups as $group){
                    $arr=$this->find()->where('group_id='.$group['id'])->orderBy('sort')->asArray()->all();
                    $setting=[];
                    foreach($arr as $item){
                        if(!in_array($item['type'],['input','number','textarea'])){
                            $item['value']=json_decode($item['value'],true);
                        }
                        $setting[$item['key_name']]=$item['value'];
                    }
                    if($setting)$array[$group['key_name']]=$setting;
                }
            }
            if(!$array)$array=[];
            $cache->set($cacheKey,$array,0,new TagDependency(
                ['tags'=>ActiveTag::getCommonTag($class)]
            ));
        }
        return $array;
    }

    public function getGroup($keyName)
    {
        /** @var \ricefox\base\ActiveRecord $model */
        $model=$this;
        $class=$model->className();
        $cache=$model->getCacheComponent();
        $cacheKey=$class.':group:'.$keyName;
        $setting=$cache->get($cacheKey);
        if($setting===false){
            $group=SettingGroup::getGroupByKey($keyName);
            if(!$group)return null;
            $setting=[];
            $arr=$this->find()->where('group_id='.$group['id'])->orderBy('sort')->asArray()->all();
            foreach($arr as $item){
                if(!in_array($item['type'],['input','number','textarea'])){
                    $item['value']=json_decode($item['value'],true);
                }
                $setting[$item['key_name']]=$item['value'];
            }
            $cache->set($cacheKey,$setting,0,new TagDependency(
                ['tags'=>ActiveTag::getCommonTag($class)]
            ));
        }
        return $setting;
    }

    public function getValue()
    {
        if(in_array($this->type,['select','radio','checkbox'])){
            $this->value=json_decode($this->value,true);
        }
        return $this->value;
    }

    public function showValue()
    {
        if(in_array($this->type,['select','radio','checkbox'])){
            $value=json_decode($this->value,true);
            $html='';
            foreach($value as $k=>$v){
                $html.=$k.'='.$v.'<br/>';
            }
        }else{
            $html=$this->value;
        }
        return $html;
    }

}
