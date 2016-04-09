<?php
/** @var $this \ricefox\base\View */
/** @var $article Array  */
/** @var $prev Array 上一篇  */
/** @var $next Array 下一篇  */
/** @var $pages string 分页链接  */
/** @var $setting \ricefox\setting\Setting */

use ricefox\helpers\Html;
// 配置
$setting=Yii::$app->get('setting');
$category=$this->category;
//面包屑导航
$breadcrumb=$category['breadcrumb'];
$breadcrumb.='&nbsp;&gt;&nbsp;'.'<span class="breadcrumb-item">'.Html::strCut($article['title'],8).'<span>';
$this->breadcrumb=$breadcrumb;
$this->title=$article['title'].'_'.$category['paths'].'_'.$setting['site']['name'];
// 关键词、描述。
$this->keywords=isset($article['keywords'])? $article['keywords']:(isset($category['keywords'])?$category['keywords']:$setting['site']['keywords']);
$this->description=isset($article['description'])?$article['description']:(isset($category['description'])?$category['description']:$setting['site']['description']);
$this->pageType='content';
//$this->registerMetaTag(['name'=>'keywords','content'=>$keywords]);
//$this->registerMetaTag(['name'=>'description','content'=>$description]);

?>

<div class="article" id="article">
    <h1 class="articleTitle"><?php echo $article['title']; ?></h1>
    <div class="articleMeta">
        <div class="col">时间</div>
        <div class="col" style="margin-left: 5px;"><?= date('Y-m-d H:i',$article['created_at']) ?></div>
    </div>
    <?php
    if($article['description']){ ?>
        <div class="articleSummary"><?php echo $article['description']; ?></div>
    <?php } ?>
    <div class="articleContent">
        <?=$article['content']?>
    </div>
    <?=$pages?>

    <?php if($prev || $next){ ?>
    <div class="prevNext">
        <?php if($next){ ?>
        <div class="next">
            <span>下一篇：</span>
            <a href="<?=$next['url']?>"><?=$next['title']?></a>
        </div>
        <?php } ?>
        <?php if($prev){ ?>
            <div class="prev">
                <span>上一篇：</span>
                <a href="<?=$prev['url']?>"><?=$prev['title']?></a>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
</div>


