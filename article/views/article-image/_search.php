<?php

use yii\helpers\Html;
use ricefox\widgets\ActiveForm;

/** @var $this yii\web\View */
/** @var $model ricefox\article\models\ArticleImageSearch */
/** @var $form ricefox\widgets\ActiveForm */
/** @var $categories Array */
/** @var $keyNames Array */
/** @var $positions Array */

$field=new \ricefox\widgets\Field();
$model->expireFrom=$model->expireFromString;
$model->expireTo=$model->expireToString;

?>

<div class="article-image-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?//= $form->field($model, 'id') ?>
    <?=$form->fieldInline($model,'category_id')->dropDownList($categories,['prompt'=>'请选择栏目'])?>
    <?=$form->fieldInline($model,'category_cond')->dropDownList([0=>'不相关',1=>'相关'],['prompt'=>'请选择'])?>
    <?=$form->fieldInline($model,'key_name')->dropDownList($keyNames,['prompt'=>'请选择标识'])?>
    <?=$form->fieldInline($model,'position')->dropDownList($positions,['prompt'=>'请选择位置'])?>
    <br/>
    <?= $field->inline()->staticText('有效期:') ?>
    <?= $form->fieldInline($model, 'expireFrom')->textInput()?>
    <?= $form->fieldInline($model, 'expireTo')->textInput() ?>
    <br/>
    <?= $field->inline()->staticText('ID:') ?>
    <?= $form->fieldInline($model, 'idFrom')->textInput()?>
    <?= $form->fieldInline($model, 'idTo')->textInput()?>
    <br/>

    <?= $form->fieldInline($model, 'title') ?>
    <?= $form->fieldInline($model, 'url') ?>
    <?= $form->fieldInline($model, 'image_url') ?>



    <?php // echo $form->field($model, 'key_name') ?>

    <?php // echo $form->field($model, 'position') ?>
    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>

</div>
