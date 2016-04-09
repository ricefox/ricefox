<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/6
 * Time: 14:17
 */

/* @var $this yii\web\View */
/* @var $model ricefox\block\models\UrlModel */
/* @var $form ricefox\widgets\ActiveForm */
/* @var $modules Array */
/* @var $routeKey string */

use yii\helpers\Html;
use ricefox\widgets\ActiveForm;
use ricefox\widgets\Field;

$field=new Field();

?>

<?= $field->inline()->staticText('URL') ?>
<?= $form->fieldInline($model, 'protocol')->dropDownList([ 'http' => 'Http', 'https' => 'Https', ], ['prompt' => '']) ?>
<?= $field->inline()->staticText('://') ?>
<?= $form->fieldInline($model, 'domain')->textInput(['maxlength' => true]) ?>
<?= $field->inline()->staticText('/') ?>
<?= $form->fieldInline($model, 'uri')->textInput(['maxlength' => true]) ?>
<?= $field->inline()->staticText('/') ?>
<?= $form->fieldInline($model, 'suffix_slashes')->dropDownList([1=>'是',0=>'否']) ?>
<?= $form->fieldInline($model, 'uri_match')->dropDownList([1=>'相等',2=>'含有']) ?>
<?php ?>
<?= $form->fieldInline($model, 'route')->dropDownList($model->getRoutes($routeKey)) ?>