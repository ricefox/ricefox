<?php

use yii\helpers\Html;
use ricefox\widgets\GridView;
use yii\helpers\Url;

/** @var $this yii\web\View */
/* @var $searchModel ricefox\job\models\HireSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index'),'active'=>true],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_job','Hire'),
        'url'=>Url::toRoute(['index'])
    ]
];

?>
<div class="hire-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'footers'=>array (
  'update' => false,
),
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],


            [
                "attribute"=>"id",
                
            ],
            [
                "attribute"=>"title",
                
            ],
            [
                "attribute"=>"job",
                
            ],
            [
                "attribute"=>"company_id",
                
            ],
            [
                "attribute"=>"number",
                
            ],
            //[
            //    "attribute"=>"year",
            //    
            //],
            //[
            //    "attribute"=>"city",
            //    
            //],
            //[
            //    "attribute"=>"area",
            //    
            //],
            //[
            //    "attribute"=>"welfare",
            //    
            //],
            //[
            //    "attribute"=>"type",
            //    
            //],
            //[
            //    "attribute"=>"edu",
            //    
            //],
            //[
            //    "attribute"=>"salary",
            //    
            //],
            //[
            //    "attribute"=>"address",
            //    
            //],
            //[
            //    "attribute"=>"created_at",
            //    
            //],
            //[
            //    "attribute"=>"updated_at",
            //    
            //],
            //[
            //    "attribute"=>"content",
            //    'format'=>'ntext'
            //],
            ['class' => 'ricefox\widgets\ActionColumn'],
        ],
    ]); ?>


</div>
