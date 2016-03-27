<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index')],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')],
[ 'label'=>'编辑', 'url'=>'javascript:void(0)','active'=>true],
[ 'label'=>'查看', 'url'=>Url::toRoute(['view','id'=>$model->id])]
];
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update">

    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
