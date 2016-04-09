<?php
/** @var Array $links */
?>
<hr class="footer-line footer-line-15" />
<div class="footer-link">
    <?php foreach($links as $link){ ?>
    <div class="col footer-link-item">
        <a target="_blank" class="col footer-link-item-a" style="color:<?php if($link['color']){echo $link['color'];}?>" href="<?=$link['url']?>"><?=$link['name']?></a>
    </div>
<?php } ?>
</div>