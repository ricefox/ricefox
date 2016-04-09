<?php

use yii\helpers\Html;
use yii\helpers\Url;
use ricefox\widgets\ActiveForm;
/** @var $this yii\web\View */
/** @var $model ricefox\article\models\ArticleText */
/** @var $categories Array */
/** @var $keyNames Array */
/** @var $positions Array */
/** @var $form ricefox\widgets\ActiveForm */
// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index')],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')],
[ 'label'=>'编辑', 'url'=>'javascript:void(0)','active'=>true],
[ 'label'=>'查看', 'url'=>Url::toRoute(['view','id'=>$model->id])]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_article','Article Text'),
        'url'=>Url::toRoute(['index'])
    ],
    [
        'label'=>Yii::t('rf_article','Update')
    ]
];
?>
<div class="article-text-update">

    <?php $form = ActiveForm::begin(); ?>

    <?//= $form->field($model, 'article_id')->textInput() ?>

    <?= $form->fieldInline($model, 'category_id',['inputOptions'=>['disabled'=>true,'class'=>'form-control']])->dropDownList($categories) ?>
    <?= $form->fieldInline($model, 'key_name')->dropDownList($keyNames) ?>
    <?= $form->fieldInline($model, 'position')->dropDownList($positions) ?>
    <?= $form->fieldInline($model, 'sort')->width(80)->textInput() ?>
    <?= $form->fieldInline($model, 'category_cond')->dropDownList([0=>'不相关',1=>'相关']) ?>
    <?= $form->fieldInline($model, 'color')->textInput() ?>
    <?= $form->fieldInline($model, 'expireAuto')->textInput() ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows'=>6]) ?>



    <div class="form-group">
        <?= Html::submitButton('更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
