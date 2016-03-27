<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model ricefox\article\models\Article */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index')],
[ 'label'=>'添加', 'url'=>Url::toRoute('create'),'active'=>true]
];
?>
<div class="article-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
