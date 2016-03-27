<?php

namespace ricefox\article;

/**
 * article-module module definition class
 */
class ArticleModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $layout='main';
    public $controllerNamespace = 'ricefox\article\controllers';
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
