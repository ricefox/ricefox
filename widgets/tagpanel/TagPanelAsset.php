<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/3
 * Time: 15:54
 */


namespace ricefox\widgets\tagpanel;

class TagPanelAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '/assets';

    public $js = [
        'tagpanel.js'
    ];

    public $css = [
        //'css/bootstrap-multiselect.css'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset'
    ];
    public function init()
    {
        parent::init();
        $this->sourcePath=__DIR__.$this->sourcePath;
    }
}