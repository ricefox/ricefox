<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model ricefox\setting\models\Setting */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index')],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')],
[ 'label'=>'编辑', 'url'=>Url::toRoute(['update','id'=>$model->id])],
[ 'label'=>'查看', 'url'=>'javascript:void(0)','active'=>true]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_setting','Setting'),
        'url'=>Url::toRoute(['index'])
    ],
    [
        'label'=>Yii::t('rf_setting','View')
    ]
];
?>
<div class="setting-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'group_id',
            'key_name',
            'name',
            'value:ntext',
            'type',
        ],
    ]) ?>

</div>
