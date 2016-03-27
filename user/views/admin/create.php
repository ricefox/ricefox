<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model ricefox\user\models\User */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index')],
[ 'label'=>'添加', 'url'=>Url::toRoute('create'),'active'=>true]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_user','User'),
        'url'=>Url::toRoute(['index'])
    ],
    [
        'label'=>Yii::t('rf_user','Create')
    ]
];
?>
<div class="user-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
