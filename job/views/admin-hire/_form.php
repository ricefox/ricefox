<?php

use yii\helpers\Html;
use ricefox\widgets\ActiveForm;
use ricefox\widgets\CitySelect;
use yii\helpers\Url;

/** @var $this yii\web\View */
/** @var $model ricefox\job\models\Hire */
/** @var $form ricefox\widgets\ActiveForm */
/** @var $setting Array */
/** @var $welfare Array */

$field=new \ricefox\widgets\Field();
?>

<div class="hire-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo \ricefox\job\widgets\Job::widget(['maxSelected'=>3]); ?>

    <?php echo \ricefox\city\CityWidget::widget() ?>

    <?php //echo CitySelect::widget(['form'=>$form,'model'=>$model,'label'=>'工作地点']) ?>

    <br/>

    <?= $form->fieldInline($model, 'type')->dropDownList($setting['hire.type']) ?>
    <?= $form->fieldInline($model, 'job')->width(250)->textInput(['maxlength' => true,'id'=>'job']) ?>
    <?= $form->fieldInline($model, 'number')->width(120)->input('number') ?>
    <?= $form->fieldInline($model, 'year')->dropDownList($setting['hire.year']) ?>
    <?= $form->fieldInline($model, 'edu')->dropDownList($setting['hire.edu']) ?>
    <br/>
    <?= $form->fieldInline($model, 'salary')->dropDownList($setting['hire.salary']) ?>
    <?= \ricefox\job\widgets\Welfare::widget(['form'=>$form,'model'=>$model,'items'=>$welfare]) ?>
    <br/>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建': '更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
