<?php

use yii\helpers\Html;
use ricefox\widgets\GridView;
use yii\helpers\Url;

/** @var $this yii\web\View */
/* @var $searchModel ricefox\block\models\UrlModelSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index'),'active'=>true],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_article','Url'),
        'url'=>Url::toRoute(['index'])
    ]
];

?>
<div class="url-model-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'footers'=>array (
            'update' => false,
            'delete' => false,
        ),
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],


            [
                "attribute"=>"id",
                
            ],
            [
                "attribute"=>"target_id",
                
            ],
            [
                "attribute"=>"url",
                'format'=>'url'
            ],
            [
                "attribute"=>"protocol",
                
            ],
            [
                "attribute"=>"domain",
                
            ],
            //[
            //    "attribute"=>"uri",
            //    
            //],
            //[
            //    "attribute"=>"uri_match",
            //    
            //],
            //[
            //    "attribute"=>"suffix_slashes",
            //    
            //],
            //[
            //    "attribute"=>"action",
            //    
            //],
            //[
            //    "attribute"=>"sort",
            //    
            //],
            //[
            //    "attribute"=>"status",
            //    
            //],
            ['class' => 'ricefox\widgets\ActionColumn'],
        ],
    ]); ?>


</div>
