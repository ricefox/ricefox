<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/4
 * Time: 11:08
 */

namespace ricefox\bootstrap;

class MultiSelectAsset extends \yii\web\AssetBundle
{
    public $sourcePath='/assets/multi-select';
    public $js=[
        'multi-select.js'
    ];
    public $css=[
        'multi-select.css'
    ];
    public $depends=[
        'yii\bootstrap\BootstrapPluginAsset'
    ];
    public function init()
    {
        parent::init();
        $this->sourcePath=__DIR__.$this->sourcePath;
    }
}