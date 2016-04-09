<?php
/** @var Array $relatedLink */
?>

<div class="related-link">
    <?php foreach($relatedLink as $link){ ?>
    <div class="col related-link-item">
        <a target="_blank" class="col related-link-item-a" style="color:<?php if($link['color']){echo $link['color'];}?>" href="<?=$link['url']?>"><?=$link['name']?></a>
    </div>
<?php } ?>
</div>