<?php

namespace ricefox\article;

/**
 * article-module module definition class
 */
class ArticleModule extends \ricefox\base\Module
{
    /**
     * @inheritdoc
     */
    public $layout='main';
    public $controllerNamespace = 'ricefox\article\controllers';
    // 标记模块含有配置，访问后台时自动加载配置
    public $hasSetting=1;
    //public $layoutPath='@ricefox/article/views/layouts';
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //$this->setLayoutPath('@ricefox/article/views/layouts');
        // custom initialization code goes here
    }

}
