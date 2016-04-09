<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/18
 * Time: 12:19
 */

namespace ricefox\setting;

use ricefox\setting\models\SettingGroup;
use Yii;
use ricefox\setting\models\Setting as SettingModel;

class Setting extends \yii\base\Object implements \ArrayAccess
{
    /**
     * 存储方式为 $settings[group key_name][key_name],如：$settings['site']['name']
     * @var array $settings
     */
    protected $settings=[];
    /** @var  SettingModel */
    protected $model;
    protected $loaded=[];
    function init()
    {
        parent::init();
        $model=$this->model=new SettingModel();
        $this->settings=$model->getPreload();
    }
    function offsetExists($offset)
    {
        return isset($this->settings[$offset]);
    }
    function offsetGet($offset)
    {
        return $this->settings[$offset];
    }
    function offsetSet($offset,$value)
    {
        $this->settings[$offset]=$value;
    }
    function offsetUnset($offset)
    {
        unset($this->settings[$offset]);
    }
    /**
     * 根据配置键名获取配置值
     * @param $key string 配置键名
     * @param $default
     * @return null|string|number|Array 配置的值
     */
    function get($key,$default=null)
    {
        return isset($this->settings[$key])?$this->settings[$key]:($default!==null ? $default :null);
    }
    public function loadSetting($groupKey)
    {
        if(!isset($this->loaded[$groupKey])){
            $this->settings[$groupKey]=$this->model->getGroup($groupKey);
            $this->loaded[$groupKey]=true;
        }
    }

}