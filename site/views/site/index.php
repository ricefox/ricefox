<?php

/* @var $this \ricefox\base\View */
/* @var $articles Array  */
/* @var $setting \ricefox\setting\Setting  */

$setting=Yii::$app->get('setting');

$this->title=$setting['site']['name'];
$this->keywords=$setting['site']['keywords'];
$this->description=$setting['site']['description'];
$this->pageType='index';
?>
<?php echo $this->render('@ricefox/article/views/show/_list',['articles'=>$articles]) ?>

