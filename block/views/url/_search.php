<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $this yii\web\View */
/** @var $model ricefox\block\models\UrlModelSearch */
/** @var $form yii\widgets\ActiveForm */
?>

<div class="url-model-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'target_id') ?>

    <?= $form->field($model, 'url') ?>

    <?= $form->field($model, 'protocol') ?>

    <?= $form->field($model, 'domain') ?>

    <?php // echo $form->field($model, 'uri') ?>

    <?php // echo $form->field($model, 'uri_match') ?>

    <?php // echo $form->field($model, 'suffix_slashes') ?>

    <?php // echo $form->field($model, 'action') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
