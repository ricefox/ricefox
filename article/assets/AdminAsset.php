<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/25
 * Time: 16:57
 */

namespace ricefox\article\assets;

class AdminAsset extends \yii\web\AssetBundle
{
    //public $sourcePath='';
    public $depends=[
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}