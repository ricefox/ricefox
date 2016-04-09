<?php

use yii\helpers\Html;
use yii\helpers\Url;
use ricefox\widgets\ActiveForm;
use ricefox\widgets\GridView;
/** @var $this yii\web\View */
/** @var $model ricefox\article\models\ArticleText */
/** @var $categories Array */
/** @var $keyNames Array */
/** @var $positions Array */
/** @var $status Array */
/* @var $searchModel ricefox\article\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index')],
[ 'label'=>'添加', 'url'=>Url::toRoute('create'),'active'=>true]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_article','Article Text'),
        'url'=>Url::toRoute(['index'])
    ],
    [
        'label'=>Yii::t('rf_article','Create')
    ]
];
?>

<div class="article-image-create">

    <?php
    $action=Url::toRoute(['create']);
    echo $this->render('_article',
        [
            'model' => $searchModel,'categories'=>$categories,'action'=>$action,
            'keyNames'=>$keyNames,'positions'=>$positions
        ]
    );
    $form = ActiveForm::begin();
    echo GridView::widget([
        'id'=>'articleList',
        'dataProvider'=>$dataProvider,
        'showGridFooter'=>false,
        'showGridForm'=>false,
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
                'field'=>'input',
            ],
            [
                'attribute'=>'title',
                'label'=>'标题',
                'width'=>500,
                'field'=>'input',
            ],
            [
                'attribute'=>'description',
                'label'=>'简介',
                'width'=>500,
                'field'=>'textarea',
                'rows'=>4

            ],
            [
                'attribute'=>'status',
                'label'=>'状态',
                'value'=>function($model)use($status){
                    return isset($status[$model['status']])?$status[$model['status']]:'';
                }
            ],
            [
                'attribute'=>'created_at',
                'label'=>'发布时间',
                'format'=>['date' ,'php:Y-m-d H:i:s']
            ],

            [
                'attribute'=>'category_id',
                'field'=>'input',
                'inputType'=>'hidden',
                'hidden'=>true,
            ],
            [
                'attribute'=>'article_id',
                'value'=>'id',
                'field'=>'input',
                'inputType'=>'hidden',
                'hidden'=>true,
            ],
            [
                'attribute'=>'url',
                'field'=>'input',
                'inputType'=>'hidden',
                'hidden'=>true,
            ],

        ]
    ]);

    ?>

    <?/*= $this->render('_create', [
        'model' => $model,
        'categories'=>$categories,
        'keyNames'=>$keyNames,
        'positions'=>$positions
    ])*/ ?>

    <?= $form->fieldInline($model, 'key_name')->dropDownList($keyNames) ?>
    <?= $form->fieldInline($model, 'position')->dropDownList($positions) ?>
    <?= $form->fieldInline($model, 'category_cond')->dropDownList([0=>'不相关',1=>'相关']) ?>
    <?= $form->fieldInline($model, 'color')->textInput() ?>
    <?php
    echo $form->fieldInline($model, 'expireDay')->dropDownList(
        [0=>'不限',1=>'1天',3=>'3天',5=>'5天',10=>'10天',15=>'15天',30=>'30天',-1=>'手动输入'],
        ['id'=>'expireDay']
    );
    echo $form->fieldInline($model, 'expireAuto',['options'=>['id'=>'expireAuto']])->width(300)
        ->textInput(['placeholder'=>'数字 或 '.date('Y-m-d H:i:s').' 格式的时间']);
    ?>


    <div class="form-group">
        <?= Html::submitButton('添加', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$js=<<<JS
var expireDay=$('#expireDay');
var expireAuto=$('#expireAuto').parent();
if(expireDay.val()!=-1){
    expireAuto.addClass('hidden');
}
expireDay.on('change',function()
{
    var value=this.value;
    if(value==-1){
        expireAuto.removeClass('hidden');
    }else{
        expireAuto.addClass('hidden');
    }
})
JS;
$this->registerJs($js);
?>
