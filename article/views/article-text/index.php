<?php

use yii\helpers\Html;
use ricefox\widgets\GridView;
use yii\helpers\Url;

/** @var $this yii\web\View */
/* @var $searchModel ricefox\article\models\ArticleTextSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index'),'active'=>true],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_article','Article Text'),
        'url'=>Url::toRoute(['index'])
    ]
];

?>
<div class="article-text-index">
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
                "attribute"=>"article_id",
                
            ],
            [
                "attribute"=>"category_id",
                
            ],
            [
                "attribute"=>"url",
                'format'=>'url'
            ],
            [
                "attribute"=>"title",
                
            ],
            //[
            //    "attribute"=>"description",
            //    
            //],
            //[
            //    "attribute"=>"key_name",
            //    
            //],
            //[
            //    "attribute"=>"position",
            //    
            //],
            //[
            //    "attribute"=>"expire",
            //    
            //],
            ['class' => 'ricefox\widgets\ActionColumn'],
        ],
    ]); ?>


</div>
