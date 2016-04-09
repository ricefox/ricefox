<?php

/** @var $menus Array */
/** @var $this \ricefox\base\View */


$setting=Yii::$app->get('setting');
?>

<div class="sub-menus">
    <?php foreach($menus as $item){ ?>
        <div class="col sub-menus-item">
            <a class="sub-menus-item-link col" href="<?=$item['url']?>">
                <?=$item['name']?>
            </a>
        </div>
    <?php } ?>
</div>