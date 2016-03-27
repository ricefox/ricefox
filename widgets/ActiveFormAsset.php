<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/19
 * Time: 11:09
 */

namespace ricefox\widgets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ActiveFormAsset extends AssetBundle
{
    public $sourcePath;
    public $js = [
        'activeForm.js',
    ];
    public $css=[
        'activeForm.css'
    ];
    public $depends = [
        'yii\widgets\ActiveFormAsset',
    ];
    public function init()
    {
        parent::init();
        $this->sourcePath=__DIR__.'/assets';
    }
}

