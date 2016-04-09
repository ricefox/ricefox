<?php

use yii\helpers\Html;
use ricefox\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ricefox\block\models\Module */
/* @var $form ricefox\widgets\ActiveForm */
?>

<div class="module-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->fieldInline($model, 'name')->width(120)->textInput(['maxlength' => true]) ?>

    <?= $form->fieldInline($model, 'class_id')->width(120)->textInput(['maxlength' => true]) ?>

    <?= $form->fieldInline($model, 'class')->width(200)->textInput(['maxlength' => true]) ?>

    <?= $form->fieldInline($model, 'status')->radioList([1=>'开启',0=>'关闭']) ?>

    <?= $form->fieldInline($model, 'sort')->width(100)->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建': '更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
