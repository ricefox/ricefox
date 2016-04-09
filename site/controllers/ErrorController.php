<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/7
 * Time: 18:50
 */

namespace ricefox\site\controllers;


class ErrorController extends \yii\web\Controller
{
    public $layout='@ricefox/site/views/error/main';
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}