<?php

use yii\helpers\Html;
use ricefox\widgets\GridView;
use yii\helpers\Url;

/** @var $this yii\web\View */
/* @var $searchModel ricefox\job\models\JobSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index'),'active'=>true],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_job','Job'),
        'url'=>Url::toRoute(['index'])
    ]
];

?>
<div class="job-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'footers'=>array (
),
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],


            [
                "attribute"=>"id",
                
            ],
            [
                "attribute"=>"group_id",
                
            ],
            [
                "attribute"=>"name",
                
            ],
            [
                "attribute"=>"sort",
                
            ],
            ['class' => 'ricefox\widgets\ActionColumn'],
        ],
    ]); ?>


</div>
