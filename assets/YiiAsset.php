<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/25
 * Time: 18:02
 */

namespace ricefox\assets;

class YiiAsset extends \yii\web\AssetBundle
{

    public $depends=[
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}