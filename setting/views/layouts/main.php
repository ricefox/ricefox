<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use ricefox\widgets\Alert;
use ricefox\assets\YiiAsset;
use ricefox\assets\BaseAsset;
use ricefox\widgets\Breadcrumbs;
YiiAsset::register($this);
BaseAsset::register($this);
Yii::$app->getUser()->setReturnUrl(Url::to());//记住URL
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="body">
    <?php
    if(isset($this->params['breadcrumbs'])){
        echo Breadcrumbs::widget(['links'=>$this->params['breadcrumbs']]);
    }

    if(isset($this->params['tabs'])){
        echo $this->render('_tabs.php',['tabs'=>$this->params['tabs']]);
    }

    echo Alert::widget();
    ?>
    <?=$content;?>
</div>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage() ?>
