<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/11
 * Time: 19:11
 */
/**
 * @var Array $tabs
 *
 */
use yii\bootstrap\Tabs;
?>
<div class="tabs-container">
<?php
echo Tabs::widget([
    'items'=>$tabs
])
?>
</div>

