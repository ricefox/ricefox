<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/25
 * Time: 18:02
 */

namespace ricefox\assets;

class BaseAsset extends \yii\web\AssetBundle
{
    public $sourcePath;
    public $css=[
        'base.css',
    ];
    public function init()
    {
        parent::init();
        $this->sourcePath=__DIR__.'/assets';
    }
}