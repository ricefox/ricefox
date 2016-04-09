<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/7
 * Time: 15:38
 */

namespace ricefox\assets;

class PcAsset extends \yii\web\AssetBundle
{
    public $sourcePath;
    public $baseUrl = '@web';
    public $css = [
        'pchtml.css',
        'pc.css'
    ];
    public $js = [
        //'js/pc.js'
    ];
    public $depends = [
        //'yii\web\JqueryAsset'
    ];

    public function init()
    {
        parent::init();
        $this->sourcePath=__DIR__.'/assets';
    }
}