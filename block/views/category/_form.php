<?php

use yii\helpers\Html;
use ricefox\widgets\ActiveForm;
use ricefox\widgets\Field;
/* @var $this yii\web\View */
/* @var $model ricefox\block\models\Category */
/* @var $urlModel ricefox\block\models\UrlModel */
/* @var $form ricefox\widgets\ActiveForm */
/* @var $modules Array */
$field=new Field();
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->fieldInline($model, 'module_id')->dropDownList($modules,['id'=>'moduleId']) ?>
    <?= $form->field($model, 'parent_id',['options'=>['id'=>'parentId']])->hiddenInput() ?>
    <?= $form->fieldInline($model, 'status')->radioLIst([0=>'关闭',1=>'开启']) ?>
    <?= $form->fieldInline($model, 'show_type')->dropDownList([1=>'本栏目',2=>'本栏目+子栏目',3=>'本栏目+子孙栏目']) ?>
    <?= $form->fieldInline($model, 'sort')->textInput() ?>
    <br/>

    <?= $form->fieldInline($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->fieldInline($model, 'title_name')->textInput(['maxlength' => true]) ?>

    <?= $form->fieldInline($model, 'title')->width(500)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
    <br/>
    <?= $this->render('../url/_url',['form'=>$form,'model'=>$urlModel,'routeKey'=>'article'])  ?>

    <?= $form->field($model, 'related')->hiddenInput() ?>
    <?= $form->field($model, 'url')->hiddenInput() ?>
    <?= $form->field($model, 'has_child')->hiddenInput() ?>
    <?= $form->field($model, 'path')->hiddenInput() ?>
    <?= $form->field($model, 'children')->hiddenInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建': '更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$getParentsUrl=\yii\helpers\Url::toRoute(['get-parents']);
$id=$model->id!==null?$model->id:0;
$js=<<<JS
$(function()
{
    var module=$('#moduleId');
    getParents(module.val());
    module.on('change',function()
    {
        var moduleId=this.value;
        getParents(moduleId);
    });
    function getParents(moduleId)
    {
        $.get("$getParentsUrl",{moduleId:moduleId,id:"$id"},function(html)
        {
            html=$(html).attr('id','parentId');
            $('#parentId').replaceWith(html);
        });
    }
});
JS;
$this->registerJs($js);
?>
