<?php

/** @var $menus Array */
/** @var $this \ricefox\base\View */

$active=$this->category ? $this->category['id'] : null;
$setting=Yii::$app->get('setting');

?>

<div class="menus">
    <h1 class="col menus-item siteName">
        <a class="menus-item-link col" href="<?=$setting['site']['url']?>">
            <?=$setting['site']['name']?>
        </a>
    </h1>
    <?php foreach($menus as $item){ ?>
        <div class="col menus-item">
            <a class="menus-item-link col <?php if($active && $item['id']==$active){echo 'active';} ?>" href="<?=$item['url']?>">
                <?=$item['name']?>
            </a>
        </div>
    <?php } ?>
</div>