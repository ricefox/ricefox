<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model ricefox\user\models\User */

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
        'label'=>Yii::t('rf_user','User'),
        'url'=>Url::toRoute(['index'])
    ],
    [
        'label'=>Yii::t('rf_user','View')
    ]
];
?>
<div class="user-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'email:email',
            'status',
            'created_at',
            'updated_at',
            'access_token',
        ],
    ]) ?>

</div>
