<?php

use yii\helpers\Html;
use ricefox\widgets\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel ricefox\setting\models\SettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $groupItems Array */
// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index'),'active'=>true],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_setting','Setting'),
        'url'=>Url::toRoute(['index'])
    ]
];

?>
<div class="setting-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'footers'=>['update'=>false],
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],


            [
                "attribute"=>"id",
                
            ],
            [
                "attribute"=>"group_id",
                'value'=>function($model)use($groupItems){
                    $id=$model['group_id'];
                    return isset($groupItems[$id])? $groupItems[$id] : $id;
                }
            ],
            [
                "attribute"=>"key_name",
                
            ],
            [
                "attribute"=>"name",
                
            ],
            [
                "attribute"=>"value",
                'format'=>'raw',
                'value'=>function($model){
                    return $model->showValue();
                }
            ],
            [
                "attribute"=>"type",

            ],
            [
                'class' => 'ricefox\widgets\ActionColumn',
            ],
        ],
    ]); ?>


</div>
