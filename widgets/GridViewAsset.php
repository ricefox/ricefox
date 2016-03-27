<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/16
 * Time: 0:07
 */

namespace ricefox\widgets;

class GridViewAsset extends \yii\web\AssetBundle
{
    public $sourcePath;
    public $js = [
        'gridView.js'
    ];
    public $css=[
        'gridView.css'
    ];
    public $depends = [
        'yii\grid\GridViewAsset'
    ];
    public function init()
    {
        parent::init();
        $this->sourcePath=__DIR__.'/assets';
    }
}