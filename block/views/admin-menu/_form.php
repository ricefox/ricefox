<?php

use yii\helpers\Html;
use ricefox\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ricefox\block\models\AdminMenu */
/* @var $form ricefox\widgets\ActiveForm */
/** @var $parents Array */
?>

<div class="admin-menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->fieldInline($model, 'parent_id')->dropDownList($parents[0],['options'=>$parents[1],'prompt'=>'请选择父菜单']) ?>
    <?= $form->fieldInline($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->fieldInline($model, 'sort')->textInput() ?>
    <?= $form->fieldInline($model, 'url')->width(300)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'has_child')->hiddenInput() ?>
    <?= $form->field($model, 'path')->hiddenInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'children')->hiddenInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->hiddenInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建': '更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
