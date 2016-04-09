<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model ricefox\block\models\Category */

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
        'label'=>Yii::t('rf_block','Category'),
        'url'=>Url::toRoute(['index'])
    ],
    [
        'label'=>Yii::t('rf_block','View')
    ]
];
?>
<div class="category-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'parent_id',
            'has_child',
            'path',
            'url:url',
            'sort',
            'children',
            'status',
            'domain',
            'uri',
            'uri_type',
            'protocol',
            'suffix_slashes',
            'module_id',
            'title_name',
            'title',
            'keywords',
            'description',
            'show_type',
            'related',
        ],
    ]) ?>

</div>
