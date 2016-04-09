<?php

use yii\helpers\Html;
use ricefox\widgets\GridView;
use yii\helpers\Url;

/** @var $this yii\web\View */
/* @var $searchModel ricefox\article\models\ArticleImageSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */
/** @var $keyNames Array */
/** @var $positions Array */
/** @var $categories Array */
// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index'),'active'=>true],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_article','Article Image'),
        'url'=>Url::toRoute(['index'])
    ]
];
?>
<?php echo $this->render('_search',['model'=>$searchModel,'keyNames'=>$keyNames,'positions'=>$positions,'categories'=>$categories]) ?>
<div class="article-image-index">
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
                "attribute"=>"sort",
                "field"=>"input",
                "width"=>60
            ],
            [
                "attribute"=>"title",
                "field"=>'input',
                'width'=>300
            ],
            [
                "attribute"=>"color",
                "field"=>'input',
                'width'=>60
            ],

            [
                "attribute"=>"url",
                "field"=>'input',
                "format"=>"url",
                'width'=>250
            ],
            [
                "attribute"=>"image_url",
                'format'=>'url',
                "field"=>'input',
                'width'=>250
            ],

            [
                "attribute"=>"expireAuto",
                'value'=>function($model){
                    if(!$model['expireAuto']){
                        return '';
                    }
                    return $model['expireAuto'];
                }
            ],

            ['class' => 'ricefox\widgets\ActionColumn'],
        ],
    ]); ?>


</div>
