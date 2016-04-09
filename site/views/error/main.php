<?php
/**
 * 默认的网站首页模版（文章模块）
 */
/** @var $this \ricefox\base\View */
/** @var $content string */

use yii\helpers\Html;

$setting=Yii::$app->get('setting');
?>
<?php $this->beginPage(); ?>
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
<?php $this->beginBody();?>
<div class="container960">
    <div class="body">
        <?=$content?>
        <h1><a style="text-decoration: none;" href="<?=$setting['site']['url']?>">返回网站首页</a></h1>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage()?>
