<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator ricefox\gii\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "ricefox\\widgets\\GridView" : "yii\\widgets\\ListView" ?>;
use yii\helpers\Url;

/** @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/** @var $dataProvider yii\data\ActiveDataProvider */

// 标签
$this->params['tabs']=[
[ 'label'=>'列表', 'url'=>Url::toRoute('index'),'active'=>true],
[ 'label'=>'添加', 'url'=>Url::toRoute('create')]
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('<?=$generator->messageCategory?>','<?=$generator->breadcrumb?>'),
        'url'=>Url::toRoute(['index'])
    ]
];

?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
<?php
$actions=$generator->getActions();
$footers=[];
if(!in_array('multi-update',$actions)){
    $footers['update']=false;
}
if(!in_array('multi-delete',$actions)){
    $footers['delete']=false;
}
?>
<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        'footers'=><?php var_export($footers) ?>,
        <?= "'columns' => [\n"; ?>
            ['class' => 'yii\grid\CheckboxColumn'],

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumns() as $name=>$value) {
        //$label=
        if (++$count < 6) {
            $str=<<<STR
            [
                "attribute"=>"$name",

            ],
STR;
        } else{
            $str=<<<STR
            //[
            //    "attribute"=>"$name",
            //
            //],
STR;
        }
        echo $str;
    }
} else {
    $labels=$generator->getActiveLabels();
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        //$label=isset($labels[$column->name])? $labels[$column->name]:Inflector::camel2words($column->name);
        $format= $format=== 'text' ? "" : "'format'=>'$format'";
        if (++$count < 6) {
            $str=<<<STR

            [
                "attribute"=>"$column->name",
                $format
            ],
STR;
        }else{
            $str=<<<STR

            //[
            //    "attribute"=>"$column->name",
            //    $format
            //],
STR;
        }
        echo $str;
    }
}
?>

            ['class' => 'ricefox\widgets\ActionColumn'],
        ],
    ]); ?>

<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>

</div>
