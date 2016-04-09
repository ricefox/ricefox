<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/3
 * Time: 19:36
 */

namespace ricefox\job\widgets;

class WelfareAsset extends \yii\web\AssetBundle
{
    public $sourcePath='/assets';
    public $js=[
        'welfare.js'
    ];
    public $depends=[
        'ricefox\bootstrap\MultiSelectAsset'
    ];
    public function init()
    {
        parent::init();
        $this->sourcePath=__DIR__.$this->sourcePath;
    }
}