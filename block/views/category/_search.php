<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ricefox\block\models\CategroySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'parent_id') ?>

    <?= $form->field($model, 'has_child') ?>

    <?= $form->field($model, 'path') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'children') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'domain') ?>

    <?php // echo $form->field($model, 'uri') ?>

    <?php // echo $form->field($model, 'uri_type') ?>

    <?php // echo $form->field($model, 'protocol') ?>

    <?php // echo $form->field($model, 'suffix_slashes') ?>

    <?php // echo $form->field($model, 'module_id') ?>

    <?php // echo $form->field($model, 'title_name') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'keywords') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'show_type') ?>

    <?php // echo $form->field($model, 'related') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rf_block', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rf_block', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
