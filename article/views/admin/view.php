<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model ricefox\article\models\Article */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index')],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')],
[ 'label'=>'编辑', 'url'=>Url::toRoute(['update','id'=>$model->id])],
[ 'label'=>'查看', 'url'=>'javascript:void(0)','active'=>true]
];
?>
<div class="article-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'category_id',
            'type_id',
            'title',
            'thumbnail',
            'keywords',
            'description',
            'url:url',
            'sort',
            'status',
            'user_id',
            'username',
            'created_at',
            'updated_at',
            'has_code',
            'replies',
            'source',
        ],
    ]) ?>

</div>
