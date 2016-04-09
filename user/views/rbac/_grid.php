<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/26
 * Time: 21:50
 */
/** @var $data */
use ricefox\widgets\GridView;
use yii\helpers\Url;

echo GridView::widget([
    'dataProvider' => $data,
    'columns' => [
        ['class' => 'yii\grid\CheckboxColumn'],
        [
            "attribute"=>"name",
            "label"=>Yii::t('rf_user','Name')
        ],
        [
            "attribute"=>"description",
            "label"=>Yii::t('rf_user','Description')
        ],
        [
            'attribute' => 'createdAt',
            'label' => Yii::t('rf_user', 'Created At'),
            'value' => function ($data) {
                return  $data->createdAt>0?date("Y-m-d H:i:s", $data->createdAt):'';
            },
        ],
        [
            'attribute' => 'updatedAt',
            'label' => Yii::t('rf_user', 'Updated At'),
            'value' => function ($data) {
                return $data->updatedAt>0?date("Y-m-d H:i:s", $data->updatedAt):'';
            },

        ],
        [
            'class' => 'ricefox\widgets\ActionColumn',
            'urlCreator'=>function($action,$model,$key,$index){
                return Url::toRoute([$action,'id'=>$key,'type'=>$model->type]);
            }
        ],
    ],
    'footers'=>[
        'update'=>false
    ]
]);