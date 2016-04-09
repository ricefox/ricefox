<?php

use yii\helpers\Html;
use ricefox\widgets\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel ricefox\block\models\AdminMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index'),'active'=>true],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_block','Admin Menu'),
        'url'=>Url::toRoute(['index'])
    ]
];

?>
<div class="admin-menu-index">

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
                "attribute"=>"url"
            ],
            [
                "attribute"=>"sort",

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
            //    "attribute"=>"children",
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
