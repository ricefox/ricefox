<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/7
 * Time: 13:25
 */

namespace ricefox\site\controllers;

use ricefox\article\models\Show;
use yii\db\Query;

class SiteController extends \ricefox\base\FrontendController
{
    public function actionIndex()
    {
        $query=(new Query())->from('article')->where('status=1')->orderBy('created_at desc')->limit(30);
        $articles=$query->all();
        $this->view->params['top']=Show::top(Show::POS_INDEX);
        return $this->renderDevice('index',['articles'=>$articles]);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionTest()
    {
        return $this->renderDevice('test');
    }

}