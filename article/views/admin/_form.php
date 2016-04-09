<?php

use yii\helpers\Html;
use ricefox\widgets\ActiveForm;
use ricefox\widgets\Field;
use ricefox\assets\UeditorAsset;
/* @var $this yii\web\View */
/* @var $model ricefox\article\models\Article */
/* @var $form ricefox\widgets\ActiveForm */
/* @var $categories Array */
/* @var $articleData ricefox\article\models\ArticleData */
UeditorAsset::register($this);
/** @var \ricefox\setting\Setting $setting */
$setting=Yii::$app->get('setting');
$status=$setting['article']['status'];
$field=new Field();

?>

<div class="article-form">

    <?php $form = ActiveForm::begin(['id'=>'form']); ?>

    <?= $form->fieldInline($model, 'category_id')->dropDownList($categories,['prompt'=>'请选择栏目']) ?>
    <?= $form->fieldInline($model, 'username')->width(120)->textInput(['maxlength' => true]) ?>
    <?= $form->fieldInline($model, 'source')->width(120)->textInput(['maxlength' => true]) ?>
    <?= $form->fieldInline($model, 'status')->dropDownList($status) ?>

    <?= $form->field($model, 'title')->width(700)->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'keywords')->width(700)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->width(700)->textarea(['rows'=>4]) ?>

    <?php
    $contentError='';
    if($articleData->hasErrors('content')){
        $contentError=implode(' ',$articleData->getErrors('content'));
    }
    ?>
    <div class="form-group <?php if($contentError){echo 'has-error';} ?>">
        <div class="" style="width: 700px;">
            <script id="content" name="ArticleData[content]"><?=$articleData->getContent()?></script>
        </div>

        <div class="help-block"><?= $contentError ?></div>
    </div>


    <?php echo $form->fieldInline($model,'isDesc')->checkbox(['label'=>'是否自动截取文章']) ?>
    <?php echo $form->fieldInline($model,'descLength')->width(100)->textInput() ?>
    <?php echo $field->inline()->staticText('个字符作为文章简介') ?>
    <?php echo $form->fieldInline($model,'isThumbnail')->checkbox(['label'=>'是否自动截取第']) ?>
    <?php echo $form->fieldInline($model,'thumbnailOrder')->width(80)->textInput() ?>
    <?php echo $field->inline()->staticText('张图片作为缩略') ?>
    <br/>
    <?php echo $form->fieldInline($articleData,'pagination')->dropDownList([0=>'不分页',1=>'自动分页',2=>'手动分页'],['id'=>'pagination']) ?>
    <?php echo $form->fieldInline($articleData,'paginationLength',['options'=>['id'=>'paginationLength','class'=>'hidden']])->textInput() ?>

    <?//= $form->field($model, 'type_id')->textInput() ?>



    <?= $form->field($model, 'thumbnail')->hiddenInput(['id'=>'thumbnail']) ?>



    <?//= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'sort')->textInput() ?>



    <?//= $form->field($model, 'user_id')->textInput() ?>



    <?//= $form->field($model, 'created_at')->textInput() ?>

    <?//= $form->field($model, 'updated_at')->textInput() ?>

    <?//= $form->field($model, 'has_code')->textInput() ?>

    <?//= $form->field($model, 'replies')->textInput() ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建': '更新', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js=<<<JS
$(function()
{
    var ue=UE.getEditor('content',{initialFrameHeight:350,initialFrameWidth:'100%'});
    var form=$('#form');
    form.on('submit',function(e)
    {
        var content=ue.getContent();

        if($(content).find('img').length>0 && $('#isThumbnail').prop('checked')===true){
            var order=Number($('#thumbnailOrder').val());
            if(!order)order=1;
            var img=$(content).find('img').eq(order-1);
            if(img.length>0){
                $('#thumbnail').val(img.attr('src'));
            }
        }
        return true;
    });
    var pagination=$('#pagination');
    pagination.on('change',function()
    {
        var length=$('#paginationLength');
        if(this.value==1){
            length.removeClass('hidden');
        }else{
            length.addClass('hidden');
        }
    });
    pagination.trigger('change');
});
JS;
$this->registerJs($js);
?>