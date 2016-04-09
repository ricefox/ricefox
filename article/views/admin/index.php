<?php

use yii\helpers\Html;
use ricefox\widgets\GridView;
use yii\helpers\Url;
use ricefox\article\helpers\Url as Url2;

/* @var $this yii\web\View */
/* @var $searchModel ricefox\article\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $categories Array */
// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index'),'active'=>true],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_article','Article Manage'),
        'url'=>Url::toRoute(['index'])
    ]
];
?>
<div class="article-index">
<?php
echo $this->render('_search', ['model' => $searchModel,'categories'=>$categories]);
echo GridView::widget([
    'id'=>'articleList',
    'dataProvider'=>$dataProvider,
    'columns'=>[
        ['class'=>'yii\grid\CheckboxColumn'],
        [
            'attribute'=>'id',
            'label'=>'文章ID'
        ],
        [
            'attribute'=>'sort',
            'label'=>'排序',
            'width'=>60,
            'field'=>'input'

        ],
        [
            'attribute'=>'title',
            'label'=>'标题',
            'width'=>500,
            'field'=>'input',
        ],
        [
            'attribute'=>'created_at',
            'label'=>'发布时间',
            'format'=>['date' ,'php:Y-m-d H:i:s']
        ],
        [
            'class'=>'ricefox\widgets\ActionColumn',
            'urlCreator'=>function($action,$model,$key,$index){
                if($action=='update' || $action=='delete'){
                    return Url::toRoute([$action,'id'=>$key]);
                }else{
                    return Url2::showUrl($key);
                }
            },
            'buttons'=>[
                'view'=>function($url,$model,$key){
                    $options = [
                        'title' => Yii::t('yii', 'View'),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                        'onclick'=>'window.top.open(this.href);return false;'
                    ];
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                }
            ]
        ]
    ]
]);

?>




</div>
