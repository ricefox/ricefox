<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/3
 * Time: 19:36
 */

namespace ricefox\job\widgets;

class JobAsset extends \yii\web\AssetBundle
{
    public $sourcePath='/assets';
    public $js=[
        'job.js'
    ];
    public $css=[
        'job.css'
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