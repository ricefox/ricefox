<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/2
 * Time: 18:28
 */

namespace ricefox\widgets;

class CitySelectAsset extends \yii\web\AssetBundle
{
    public $sourcePath='/assets/cascade';
    public $depends = [
        'ricefox\widgets\CascadeAsset'
    ];
    public function init()
    {
        parent::init();
        $this->sourcePath=__DIR__.$this->sourcePath;
    }
}