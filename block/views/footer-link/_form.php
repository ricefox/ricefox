<?php

use yii\helpers\Html;
use ricefox\widgets\ActiveForm;

/** @var $this yii\web\View */
/** @var $model ricefox\block\models\FooterLink */
/** @var $form ricefox\widgets\ActiveForm */
/** @var $modules Array $isNew */
$isNew=$model->isNewRecord;
?>

<div class="footer-link-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php $options=['id'=>'moduleId'];if(!$isNew)$options['disabled']=true; ?>
    <?= $form->fieldInline($model, 'module_id')->dropDownList($modules,$options) ?>

    <?= $form->field($model, 'category_id',['options'=>['id'=>'categoryId']])->hiddenInput(['id'=>'categoryValue']) ?>
    <?= $form->fieldInline($model, 'color')->textInput(['maxlength' => true]) ?>

    <?= $form->fieldInline($model, 'sort')->textInput() ?>
    <?= $form->fieldInline($model, 'category_cond')->dropDownList([0=>'不相关',1=>'相关']) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>



    <?// 暂时不考虑图片链接的情况，该字段为预留字段  = $form->field($model, 'image_url')->textInput(['maxlength' => true]) ?>




    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建': '更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
// 获取栏目。
$url=\yii\helpers\Url::toRoute(['get-categories']);
$js=<<<JS
$(function()
{
    var isNew=Boolean($isNew);
    var module=$('#moduleId');
    getCategories(module.val());
    module.on('change',function()
    {
        var moduleId=this.value;
        getCategories(moduleId);
    });
    function getCategories(moduleId)
    {
        $.get("$url",{moduleId:moduleId},function(html)
        {
            html=$(html).attr('id','categoryId');
            html.find('select').attr('id','categoryValue');
            var value=$('#categoryValue').val();
            $('#categoryId').replaceWith(html);
            if(!isNew){
                $('#categoryValue').val(value).prop('disabled',true);
                //$('#categoryValue')
            }


        });
    }
});
JS;
$this->registerJs($js);
?>