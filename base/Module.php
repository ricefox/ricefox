<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/30
 * Time: 18:15
 */

namespace ricefox\base;
use Yii;
use ricefox\block\models\Module as ModuleModel;

class Module extends \yii\base\Module
{
    /** @var  Array $setting */
    public $setting;
    /** @var  integer $loadState */
    public $hasSetting=0;

    public function init()
    {
        parent::init();
    }

    public function loadModuleSetting()
    {
        /** @var \ricefox\setting\Setting $setting */
        $setting=Yii::$app->get('setting');
        $setting->loadSetting($this->id);
        $this->setting=$setting[$this->id];
    }

}