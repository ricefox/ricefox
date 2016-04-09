<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var $this yii\web\View */
/** @var $model ricefox\block\models\UrlModel */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index')],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')],
[ 'label'=>'编辑', 'url'=>'javascript:void(0)','active'=>true],
[ 'label'=>'查看', 'url'=>Url::toRoute(['view','id'=>$model->id])]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_article','Url'),
        'url'=>Url::toRoute(['index'])
    ],
    [
        'label'=>Yii::t('rf_article','Update')
    ]
];
?>
<div class="url-model-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
