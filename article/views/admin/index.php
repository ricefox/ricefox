<?php

use yii\helpers\Html;
use ricefox\widgets\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel ricefox\article\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index'),'active'=>true],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')]
];

?>
<div class="article-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],


            [
                "attribute"=>"id",
                
            ],
            [
                "attribute"=>"category_id",
                
            ],
            [
                "attribute"=>"type_id",
                
            ],
            [
                "attribute"=>"title",
                
            ],
            [
                "attribute"=>"thumbnail",
                
            ],
            //[
            //    "attribute"=>"keywords",
            //    
            //],
            //[
            //    "attribute"=>"description",
            //    
            //],
            //[
            //    "attribute"=>"url",
            //    'format'=>'url'
            //],
            //[
            //    "attribute"=>"sort",
            //    
            //],
            //[
            //    "attribute"=>"status",
            //    
            //],
            //[
            //    "attribute"=>"user_id",
            //    
            //],
            //[
            //    "attribute"=>"username",
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
            //    "attribute"=>"has_code",
            //    
            //],
            //[
            //    "attribute"=>"replies",
            //    
            //],
            //[
            //    "attribute"=>"source",
            //    
            //],
            ['class' => 'ricefox\widgets\ActionColumn'],
        ],
    ]); ?>


</div>
