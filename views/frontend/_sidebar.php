<?php
/** @var $images Array  */
/** @var $texts Array  */
/** @var $this \ricefox\base\View */
use ricefox\helpers\Html;

?>

<?php if(!empty($images)){ ?>
    <div class="box halfImage">
        <?php $i=0; foreach($images as $item){ ?>
            <a target="_blank" href="<?=$item['url']?>" title="<?=$item['title']?>" class="halfImageItem colblock <?php if($i%2==0){echo 'halfOne';}?>">
                <img class="col" src="<?=$item['image_url']?>" />
                <span class="col"><?=Html::strCut($item['title'],8)?></span>
            </a>
            <?php $i++; } ?>
    </div>
<?php } ?>

<?php if(!empty($texts)){ ?>
    <div class="box list">
        <?php $len=count($texts);$i=1; foreach($texts as $item){ ?>
            <div class="listItem<?php if($i==$len){echo ' lastListItem';} ?>">
                <a target="_blank" class="col" href="<?=$item['url']?>"><?=$item['title']?></a>
            </div>
        <?php $i++; } ?>
    </div>
<?php } ?>