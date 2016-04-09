<?php

use yii\helpers\Html;
use ricefox\widgets\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel ricefox\block\models\CategroySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index'),'active'=>true],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_block','Category'),
        'url'=>Url::toRoute(['index'])
    ]
];

?>
<div class="category-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],


            [
                "attribute"=>"id",
                
            ],
            [
                "attribute"=>"name",
                
            ],
            [
                "attribute"=>"parent_id",
                
            ],
            [
                "attribute"=>"has_child",
                
            ],
            [
                "attribute"=>"path",
                
            ],
            //[
            //    "attribute"=>"url",
            //    'format'=>'url'
            //],
            //[
            //    "attribute"=>"sort",
            //    
            //],
            //[
            //    "attribute"=>"children",
            //    
            //],
            //[
            //    "attribute"=>"status",
            //    
            //],
            //[
            //    "attribute"=>"domain",
            //    
            //],
            //[
            //    "attribute"=>"uri",
            //    
            //],
            //[
            //    "attribute"=>"uri_type",
            //    
            //],
            //[
            //    "attribute"=>"protocol",
            //    
            //],
            //[
            //    "attribute"=>"suffix_slashes",
            //    
            //],
            //[
            //    "attribute"=>"module_id",
            //    
            //],
            //[
            //    "attribute"=>"title_name",
            //    
            //],
            //[
            //    "attribute"=>"title",
            //    
            //],
            //[
            //    "attribute"=>"keywords",
            //    
            //],
            //[
            //    "attribute"=>"description",
            //    
            //],
            //[
            //    "attribute"=>"show_type",
            //    
            //],
            //[
            //    "attribute"=>"related",
            //    
            //],
            ['class' => 'ricefox\widgets\ActionColumn'],
        ],
    ]); ?>


</div>
