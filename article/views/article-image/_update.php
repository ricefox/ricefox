<?php

use yii\helpers\Html;
use ricefox\widgets\ActiveForm;

/** @var $this yii\web\View */
/** @var $model ricefox\article\models\ArticleImage */
/** @var $form ricefox\widgets\ActiveForm */
/** @var $categories Array */
/** @var $keyNames Array */
/** @var $positions Array */
?>

<div class="article-image-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->fieldInline($model, 'category_id',['inputOptions'=>['disabled'=>true,'class'=>'form-control']])->dropDownList($categories) ?>
    <?= $form->fieldInline($model, 'key_name')->dropDownList($keyNames) ?>
    <?= $form->fieldInline($model, 'position')->dropDownList($positions) ?>
    <?= $form->fieldInline($model, 'category_cond')->dropDownList([0=>'不相关',1=>'相关']) ?>
    <?= $form->fieldInline($model, 'color')->textInput() ?>
    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>



    <div class="form-group">
        <?= Html::submitButton('更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
