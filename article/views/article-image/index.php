<?php

use yii\helpers\Html;
use ricefox\widgets\GridView;
use yii\helpers\Url;

/** @var $this yii\web\View */
/* @var $searchModel ricefox\article\models\ArticleImageSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */
/** @var $keyNames Array */
/** @var $positions Array */
/** @var $categories Array */
// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index'),'active'=>true],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_job','Article Image'),
        'url'=>Url::toRoute(['index'])
    ]
];
?>
<div class="article-image-index">
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
                "attribute"=>"category_id",
                'value'=>function($model)use($categories){
                    return $categories[$model['category_id']];
                }
                
            ],
            [
                "attribute"=>"url",
                'format'=>'url'
            ],
            [
                "attribute"=>"image_url",
                'format'=>'url'
            ],
            [
                "attribute"=>"title",
                
            ],
            [
                "attribute"=>"key_name",
                'value'=>function($model)use($keyNames){
                    return $keyNames[$model['key_name']];
                }
            ],
            [
                "attribute"=>"position",
                'value'=>function($model)use($positions){
                    return $positions[$model['position']];
                }
            ],
            ['class' => 'ricefox\widgets\ActionColumn'],
        ],
    ]); ?>


</div>
