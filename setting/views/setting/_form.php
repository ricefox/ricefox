<?php

use yii\helpers\Html;
use ricefox\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ricefox\setting\models\Setting */
/* @var $form ricefox\widgets\ActiveForm */
/* @var $groupItems Array */
/* @var $types Array */
?>
<?php
$groupContainer=<<<HTML
<div class="groupvalue setting-value-groupvalue">
    {input}
</div>
HTML;
$groupTpl=<<<HTML
<div class="group form-inline" id="settingValue">
    <input type="text" class="form-control col" name="value[group][name][{id}]" value="{name}">
    <input type="text" class="form-control col" name="value[group][value][{id}]" value="{value}">
    {button}
</div>
HTML;
$btnTpl='<button type="button" class="btn btn-xs col {btnclass}">{btntext}</button>';
$textArea=<<<HTML
<textarea style="width:700px;" class="form-control textarea setting-value-textarea" name="value[textarea]" rows="6"></textarea>
HTML;
$input=<<<HTML
<input type="text"  class="form-control setting-value-input" name="value[input]" style="width:700px">
HTML;
?>

<div class="setting-form">

    <?php $form = ActiveForm::begin(['id'=>'settingForm']); ?>

    <?= $form->fieldInline($model, 'group_id')->dropDownList($groupItems) ?>
    <?= $form->fieldInline($model, 'type')->dropDownList($types,['id'=>'type']) ?>
    <?= $form->fieldInline($model, 'key_name')->textInput(['maxlength' => true]) ?>
    <?= $form->fieldInline($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php
    $config=['labelOptions'=>['id'=>'valueLabel'],'options'=>['class'=>'setting-value']];
    $fieldValue=$form->field($model, 'value',$config)->width(700);
    if($model->isNewRecord)$model->type='input';
    switch($model->type)
    {
        case 'textarea':
            $fieldValue->textarea(['id'=>'settingValue','name'=>'value[textarea]','class'=>'form-control textarea setting-value-textarea']);
            break;
        case 'input':
        case 'number':
            $fieldValue->textInput(['id'=>'settingValue','name'=>'value[input]','class'=>'form-control setting-value-input']);
            break;
        default:
            $values=$model->getValue();
            $i=1;
            $count=count($values);
            $input='';
            foreach($values as $name=>$value){
                $replace=['{id}'=>$i,'{name}'=>$name,'{value}'=>$value];
                $btn=[];
                if($i==$count){
                    $btn['{btnclass}']='btn-success add-group';
                    $btn['{btntext}']='添加';
                }else if($i==1){
                    $replace['{button}']='';
                }else{
                    $btn['{btnclass}']='btn-danger remove-group';
                    $btn['{btntext}']='删除';
                }
                $btnHtml=strtr($btnTpl,$btn);
                if(!isset($replace['{button}']))$replace['{button}']=$btnHtml;
                $html=strtr($groupTpl,$replace);
                $input.=$html;
                $i++;
            }
            $input=strtr($groupContainer,['{input}'=>$input]);
            $fieldValue->parts['{input}']=$input;
            $groupValueId=$i;
            break;
    }
    echo $fieldValue;
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建': '更新', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <div type="text/html" id="template" class="hidden">
        <textarea style="width:700px;" class="form-control textarea hidden" name="value[textarea]" rows="6"></textarea>
        <div class="groupvalue hidden">
            <div class="group form-inline">
                <input type="text" class="form-control col" name="value[group][name][1]">
                <input type="text" class="form-control col" name="value[group][value][1]">
                <button type="button" class="btn btn-success btn-xs add-group col">添加</button>
            </div>
        </div>
    </div>
</div>
<?php
if(!isset($groupValueId))$groupValueId=1;
$js=<<<JS
var tpl=$('#template');
var label=$('#valueLabel');
var map={
    input:'input',
    number:'input',
    textarea:'textarea',
    select:'groupvalue',
    radio:'groupvalue',
    checkbox:'groupvalue'
};
$('#type').on('change',function()
{
    var value=this.value;
    value=map[value];
    var field=$('.setting-value-'+value);
    if(field.length<=0){
        field=tpl.find('.'+value).addClass('setting-value-'+value);
        label.after(field);
    }
    $('#settingValue').addClass('hidden').removeAttr('id');
    field.removeClass('hidden').attr('id','settingValue');
    $('.setting-value').removeClass('has-error has-success').find('.help-block').empty();

});
$('.add-group').on('click',addGroup);
// 添加组
function addGroup()
{
    var group=$(this).closest('.group');
    var clone=group.clone();
    clone.find('.add-group').on('click',addGroup);
    //$(this).remove();
    var removeBtn=clone.find('.remove-group');
    if(removeBtn.length<=0){
        removeBtn=$('<button type="button" class="btn btn-danger btn-xs remove-group col">删除</button>');
        group.append(removeBtn);
    }
    var id=getId();
    clone.find('input').each(function()
    {
        var name=this.name;
        var r=/\[\d+\]$/;
        name=name.replace(r,'['+id+']');
        this.name=name;
        this.value='';
    });
    removeBtn.on('click',removeGroup);
    group.after(clone);
    group.find('.add-group').remove();
    $('.setting-value .group').first().find('.remove-group').remove();
}
$('#settingForm').on('submit',function()
{
    var type=this.type.value;
    var fieldType=map[type];
    var state=false;
    if(fieldType!='groupvalue'){
        var value=$.trim($(this).find('.setting-value-'+fieldType).val());
        if(type=='number')value=Number(value);
        if(value)state=true;
    }else{
        $('.setting-value-groupvalue').find('.group').each(function()
        {
            var s=true;
            $(this).find('input').each(function()
            {
                if($.trim(this.value)===''){
                    s=false;
                    return false;
                }
            });
            if(s===true){
                state=true;
            }
        });
    }
    if(state!==true){
        var field=$('.setting-value');
        field.addClass('has-error');
        var label=field.find('>label').first().text();
        field.find('.help-block').text(label+'不能为空');
        return false;
    }else{
        $('.setting-value').addClass('has-success');
    }
});
// 删除组
function removeGroup()
{
    $(this).closest('.group').remove();
}
var getId=(function()
{
    var _id=$groupValueId;
    return function()
    {
        _id++;
        return _id;
    }
})();
JS;
$this->registerJs($js);
$css=<<<CSS
.form-inline.group{
    margin-bottom: 6px;
}
CSS;
$this->registerCss($css);
?>