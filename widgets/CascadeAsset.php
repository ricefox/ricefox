<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/2
 * Time: 8:42
 */

namespace ricefox\widgets;

class CascadeAsset extends \yii\web\AssetBundle
{
    public $sourcePath='/assets/cascade';
    public $js = [
        'cascade.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    public function init()
    {
        parent::init();
        $this->sourcePath=__DIR__.$this->sourcePath;
    }
}