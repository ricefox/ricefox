<?php

use yii\helpers\Html;
use ricefox\widgets\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel ricefox\user\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index'),'active'=>true],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_user','User'),
        'url'=>Url::toRoute(['index'])
    ]
];

?>
<div class="user-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],


            [
                "attribute"=>"id",
                
            ],
            [
                "attribute"=>"username",
                
            ],
            [
                "attribute"=>"auth_key",
                
            ],
            [
                "attribute"=>"password_hash",
                
            ],
            [
                "attribute"=>"password_reset_token",
                
            ],
            //[
            //    "attribute"=>"email",
            //    'format'=>'email'
            //],
            //[
            //    "attribute"=>"status",
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
            //    "attribute"=>"access_token",
            //    
            //],
            ['class' => 'ricefox\widgets\ActionColumn'],
        ],
    ]); ?>


</div>
