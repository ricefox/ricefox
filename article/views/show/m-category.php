<?php
/** @var $this \ricefox\base\View  */
/** @var $pages string  */
/** @var $articles Array  */
/** @var $setting \ricefox\setting\Setting  */

$category=$this->category;
$setting=Yii::$app->get('setting');
// 标题
if(isset($category['title']) && $category['title']){
    $this->title=$category['title'].'_'.$category['paths'].'_'.$setting['site']['name'];
}else{
    $this->title=$setting['site']['name'];
}
// 关键词、描述。
$this->keywords=isset($category['keywords'])? $category['keywords']:$setting['site']['keywords'];
$this->description=isset($category['description'])?$category['description']:$setting['site']['description'];
//$this->registerMetaTag(['name'=>'keywords','content'=>$keywords]);
//$this->registerMetaTag(['name'=>'description','content'=>$description]);
//面包屑
$this->breadcrumb=$category['breadcrumb'];
$this->pageType='category';
?>
<?php echo $this->render('_m-list',['articles'=>$articles,'pages'=>$pages]); ?>
