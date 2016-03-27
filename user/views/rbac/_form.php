<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/26
 * Time: 22:44
 */
/** @var $submit string */
/** @var $model \ricefox\user\models\AuthItemForm */
/** @var $items Array */
use ricefox\widgets\ActiveForm;
use ricefox\helpers\Html;
use ricefox\widgets\Field;
$field=new Field();
?>
<? $form=ActiveForm::begin() ?>

<?= $form->field($model, 'oldname', ['template' => '{input}'])->input('hidden'); ?>
<?= $form->field($model, 'type', ['template' => '{input}'])->input('hidden'); ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
<?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>
<?php
$roles=isset($items['Role'])? $items['Role']:[];
$permissions=isset($items['Permission'])?$items['Permission']:[];
// 删除自身
if(isset($roles[$model->name]))unset($roles[$model->name]);
if(isset($permissions[$model->name]))unset($permissions[$model->name]);

if($roles){
    $before='<p class="form-control-static bold col">'.Yii::t('rf_user','Children').'&nbsp;&nbsp;&nbsp;</p>';
    $before.='<p class="form-control-static bold col">'.Yii::t('rf_user','Role').'：</p>';
    $children=$form->fieldInline($model,'children')->checkboxList($roles,['before'=>$before])->label(false);
    if($permissions){
        $before='<p class="form-control-static bold col">&nbsp;&nbsp;&nbsp;'.Yii::t('rf_user','Permission').'：</p>';
        $children->addCheckBoxList($permissions,['before'=>$before]);
    }
}else if($permissions){
    $before='<p class="form-control-static bold col">'.Yii::t('rf_user','Children').'&nbsp;&nbsp;&nbsp;</p>';
    $before.='<p class="form-control-static bold col">'.Yii::t('rf_user','Permission').'：</p>';
    $children=$form->fieldInline($model,'children')->checkboxList($permissions,['before'=>$before])->label(false);
}
if(isset($children)) echo $children;

?>
<?//= (!empty($rules)) ? $form->field($model, 'ruleName')->dropDownList($rules, ['prompt' => 'Choose rule']) : '' ?>
<?/*= $form->field($model, 'children')->widget('app\widgets\MultiSelect', [
    'items' => $items,
    'selectedItems' => $children,
    'ajax' => false,
]) */?>
<div class="form-group">
    <?= Html::submitButton(Yii::t('rf_user',$submit),['class'=>'btn btn-primary']) ?>
</div>


<?php ActiveForm::end() ?>