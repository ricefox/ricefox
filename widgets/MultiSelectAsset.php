<?php
/**
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace ricefox\widgets;

use yii\web\AssetBundle;

class MultiSelectAsset extends AssetBundle
{
    public $sourcePath = '/assets/multi-select';

    public $js = [
        'js/bootstrap-multiselect.js'
    ];

    public $css = [
        'css/bootstrap-multiselect.css'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset'
    ];
    public function init()
    {
        parent::init();
        $this->sourcePath=__DIR__.$this->sourcePath;
    }
}