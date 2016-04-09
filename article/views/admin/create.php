<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model ricefox\article\models\Article */
/* @var $categories Array */
/* @var $articleData ricefox\article\models\ArticleData */
// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index')],
[ 'label'=>'添加', 'url'=>Url::toRoute('create'),'active'=>true]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_article','Article Manage'),
        'url'=>Url::toRoute(['index'])
    ],
    [
        'label'=>Yii::t('rf_article','Create')
    ]
];
?>
<div class="article-create">



    <?= $this->render('_form', [
        'model' => $model,
        'categories'=>$categories,
        'articleData'=>$articleData
    ]) ?>

</div>
