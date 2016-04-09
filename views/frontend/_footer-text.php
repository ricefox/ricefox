<?php
/** @var Array $texts */
use ricefox\helpers\Html;
?>
<?php  ?>
<hr class="footer-line footer-line-15" />
<div class="footer-text">
    <?php foreach($texts as $text){ ?>
    <div class="colblock footer-text-item">
        <a target="_blank" title="<?=$text['title']?>" class="col footer-text-item-a" style="color:<?php if(isset($text['color'])){echo $text['color'];}?>" href="<?=$text['url']?>">
            <?=$text['title']?>
        </a>
    </div>
<?php } ?>
</div>