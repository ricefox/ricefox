<?php

/* @var $images Array  */

use \ricefox\helpers\Html;
?>

<?php if(!empty($images)){ ?>
    <hr class="footer-line footer-line-15"/>
    <div class="bottomImage">
    <?php  foreach($images as $item){ ?>
        <div class="bottomImageItem colblock">
            <a target="_blank" href="<?=$item['url']?>" class="colblock" title="<?=$item['title']?>">
                <img src="<?=$item['image_url']?>" class="col"/>
                <span class="col">
                    <?=Html::strCut($item['title'],12)?>
                </span>
            </a>
        </div>
    <?php  } ?>
    </div>
<?php } ?>
