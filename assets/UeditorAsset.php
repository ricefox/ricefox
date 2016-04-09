<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/13
 * Time: 9:21
 */

namespace ricefox\assets;

use yii\web\AssetBundle;

class UeditorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'ueditor/themes/default/css/ueditor.css',
    ];
    public $js = [
        'ueditor/config.js',
        'ueditor/ueditor.all.js',
    ];
}