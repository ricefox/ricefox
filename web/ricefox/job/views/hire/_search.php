<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ricefox\job\models\HireSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hire-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'job') ?>

    <?= $form->field($model, 'company_id') ?>

    <?= $form->field($model, 'number') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'welfare') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'edu') ?>

    <?php // echo $form->field($model, 'salary') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'content') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rf_job', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rf_job', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
