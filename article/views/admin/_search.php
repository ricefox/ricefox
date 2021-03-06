<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/2/29
 * Time: 10:24
 */

use yii\helpers\Html;
use ricefox\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ricefox\article\models\ArticleSearch */
/* @var $form ricefox\widgets\ActiveForm */
/* @var $field ricefox\widgets\Field */
/* @var $categories Array */

$field=new \ricefox\widgets\Field();
$model->createFrom=$model->createFromString;
$model->createTo=$model->createToString;
?>

<div style="margin-top: 10px;">
    <?php
    $action=isset($action) ? $action:['search'];
    $form = ActiveForm::begin([
        'action' => $action,
        'method' => 'get',
    ]); ?>

    <?=$form->fieldInline($model,'category_id')->dropDownList($categories,['prompt'=>'请选择栏目'])?>
    <?= $form->fieldInline($model, 'title')->textInput(['placeholder'=>'请输入标题关键词'])?>
    <?= $field->inline()->staticText('文章发布时间:') ?>
    <?= $form->fieldInline($model, 'createFrom')->textInput()?>
    <?= $form->fieldInline($model, 'createTo')->textInput() ?>
    <!--<hr class="hr5">-->
    <br/>
    <?= $field->inline()->staticText('文章ID:') ?>
    <?= $form->fieldInline($model, 'idFrom')->textInput()?>
    <?= $form->fieldInline($model, 'idTo')->textInput()?>
    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end();?>

</div>


