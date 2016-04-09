<?php
/** @var $this \ricefox\base\View */
/* @var $tops Array 置顶的文章列表  */
/* @var $articles Array  文章列表。 */
use ricefox\article\helpers\ViewHelper;
$topId=[];
$tops=$this->params['top'];
?>
<?php ViewHelper::subNav() ?>
<div class="info clear-fix">
    <!--&nbsp; 这里是布局需要(由于下方需要撑开容器，这里是为了对称)-->
    <?php foreach($tops as $infoItem){ ?>
    <?php $topId[]=$infoItem['article_id']; ?>
    <div class="infoItem">
        <h3 class="infoItemTitle">
            <a href="<?php echo $infoItem['url']; ?>" target="_blank" title="<?php echo $infoItem['title']; ?>">
                <span class="topIcon">[推荐]</span><?php echo $infoItem['title']; ?>
            </a>
        </h3>

        <p class="infoItemSummary">
            <?=$infoItem['description']?>
        </p>
    </div>
<?php } ?>
<?php $len=count($articles);$i=1; foreach($articles as  $infoItem){ if(in_array($infoItem['id'],$topId))continue; ?>
    <div class="infoItem <?php if($i==$len)echo 'infoItemLast' ?>">
        <h3 class="infoItemTitle">
            <a href="<?php echo $infoItem['url']; ?>" target="_blank" title="<?php echo $infoItem['title']; ?>">
                <?php echo $infoItem['title']; ?>
            </a>
        </h3>
        <p class="infoItemSummary">
            <?=$infoItem['description']?>
        </p>
    </div>
<?php $i++; } ?>
    <?php if(isset($pages)){
        echo $pages."&nbsp;";

    } ?>
    <!-- &nbsp; 这里是为了撑开info容器，没找到不能撑开的原因-->
</div>