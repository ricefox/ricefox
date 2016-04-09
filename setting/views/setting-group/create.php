<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model ricefox\setting\models\SettingGroup */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index')],
[ 'label'=>'添加', 'url'=>Url::toRoute('create'),'active'=>true]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_setting','Setting Group'),
        'url'=>Url::toRoute(['index'])
    ],
    [
        'label'=>Yii::t('rf_setting','Create')
    ]
];
?>
<div class="setting-group-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
