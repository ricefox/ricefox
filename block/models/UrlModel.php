<?php

namespace ricefox\block\models;

use Yii;
use yii\caching\TagDependency;
use ricefox\behaviors\ActiveTag;
/**
 * This is the model class for table "url".
 *
 * @property integer $id
 * @property integer $target_id
 * @property string $url
 * @property string $protocol
 * @property string $domain
 * @property string $uri
 * @property integer $uri_match
 * @property integer $suffix_slashes
 * @property string $route
 * @property integer $sort
 * @property integer $status
 */

class UrlModel extends \ricefox\base\ActiveRecord
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
        return 'url';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['target_id', 'url', 'domain', 'route'], 'required'],
            [['target_id', 'uri_match', 'suffix_slashes', 'sort', 'status'], 'integer'],
            [['protocol'], 'string'],
            [['url', 'uri'], 'string', 'max' => 50],
            [['domain'], 'string', 'max' => 25],
            [['route'], 'string', 'max' => 255],
            [['domain', 'uri'], 'unique', 'targetAttribute' => ['domain', 'uri'], 'message' => 'The combination of Domain and Uri has already been taken.'],
        ];
    }
    public function beforeValidate()
    {
        $this->uri=trim($this->uri,'/ ');
        $this->url=$this->protocol.'://'.$this->domain.'/'.$this->uri;
        if(!$this->uri)$this->url=rtrim($this->url,'/');
        if($this->suffix_slashes)$this->url.='/';
        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'target_id' => 'Target ID',
            'url' => 'Url',
            'route' => 'Route',
            'sort' => 'Sort',
            'status' => 'Status',
            'domain' => Yii::t('rf_block', 'Domain'),
            'uri' => Yii::t('rf_block', 'Uri'),
            'uri_match' => 'URI 唯一匹配',
            'protocol' => Yii::t('rf_block', 'Protocol'),
            'suffix_slashes' => Yii::t('rf_block', 'Suffix Slashes'),
        ];
    }

    public function getRoutes($key=null)
    {
        /** @var \ricefox\setting\Setting $setting */
        $setting=Yii::$app->get('setting');
        $setting->loadSetting('route');
        return $key && isset($setting['route'][$key]) ? $setting['route'][$key]:$setting['route'];
    }
    public static function getUrlByKey($key)
    {
        $class=static::className();
        /** @var UrlModel $model */
        $model=new $class();
        $cache=$model->getCacheComponent();
        $cacheKey=$class.':urlByKey:'.$key;
        $url=$cache->get($cacheKey);
        if($url===false){
            $array=$model->find()->asArray()->all();
            $arr=[];
            foreach($array as $item){
                $arr[$item['domain'].'/'.$item['uri']]=[
                    'id'=>$item['id'],
                    'targetId'=>$item['target_id'],
                    'route'=>$item['route']
                    ];
            }
            foreach($arr as $k=>$item){
                $cache->set($class.':urlByKey:'.$k,$item,0,new TagDependency(
                    ['tags'=>ActiveTag::getCommonTag($class)]
                ));
            }
            if(isset($arr[$key]))$url=$arr[$key];
        }
        return $url;
    }
}
