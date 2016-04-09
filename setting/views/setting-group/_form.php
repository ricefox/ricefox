<?php

use yii\helpers\Html;
use ricefox\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ricefox\setting\models\SettingGroup */
/* @var $form ricefox\widgets\ActiveForm */
?>

<div class="setting-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->fieldInline($model, 'key_name')->textInput(['maxlength' => true]) ?>

    <?= $form->fieldInline($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->fieldInline($model, 'preload')->radioList([0=>'否',1=>'是']) ?>

    <?= $form->fieldInline($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建': '更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
