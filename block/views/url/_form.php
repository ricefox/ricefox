<?php

use yii\helpers\Html;
use ricefox\widgets\ActiveForm;

/** @var $this yii\web\View */
/** @var $model ricefox\block\models\UrlModel */
/** @var $form ricefox\widgets\ActiveForm */
?>

<div class="url-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'target_id')->textInput() ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'protocol')->dropDownList([ 'http' => 'Http', 'https' => 'Https', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uri')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uri_match')->textInput() ?>

    <?= $form->field($model, 'suffix_slashes')->textInput() ?>

    <?= $form->field($model, 'action')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建': '更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
