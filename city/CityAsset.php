<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/4
 * Time: 22:57
 */

namespace ricefox\city;

class CityAsset extends \yii\web\AssetBundle
{
    public $sourcePath='/assets';
    public $js = [
        'city.js',
    ];
    public $css=[
        'city.css'
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