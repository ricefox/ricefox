<?php
/**
 * 默认的网站首页模版（文章模块）
 */
/** @var $this \ricefox\base\View */
/** @var $content string */

use yii\helpers\Html;
use ricefox\article\helpers\ViewHelper;
\ricefox\assets\MobileAsset::register($this);
ViewHelper::seo();
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

<?php ViewHelper::mainNav() ?>
<?php ViewHelper::relatedLink() ?>
<div class="body">
    <div class="main">
        <?php if($this->breadcrumb){ ?>
            <div class="breadcrumb"> <?php echo $this->breadcrumb; ?> </div>
        <?php } ?>
        <div class="main-content clear-fix">
            <?=$content?>
        </div>
    </div>
    <div class="sidebar">
        <?php //ViewHelper::sidebar(); ?>
    </div>
</div>
<div class="clear"></div>
<div class="footer">
    <?php ViewHelper::footer(); ?>
    <?php ViewHelper::footerText(); ?>
    <?php ViewHelper::footerLink(); ?>
    <hr class="footer-line footer-line-15" />
    <div class="footer-bar">
        <div class="footer-bar-item">
            <a class="col" href="<?=$setting['site']['url']?>">
                <?=$setting['site']['name']?>
            </a>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage()?>