<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/26
 * Time: 21:45
 */

use yii\helpers\Html;
use ricefox\widgets\GridView;
use yii\helpers\Url;
use yii\rbac\Item;

/* @var $this yii\web\View */
/* @var $searchModel ricefox\user\models\UserSearch */
/* @var $data Array */
/** @var  $type integer */

$role=[ 'label'=>Yii::t('rf_user','Role'), 'url'=>Url::toRoute('index')];
$permission=[ 'label'=>Yii::t('rf_user','Permission'), 'url'=>Url::toRoute(['index','type'=>Item::TYPE_PERMISSION])];
if($type==Item::TYPE_ROLE){
    $role['active']=true;
}else{
    $permission['active']=true;
}
// 标签
$this->params['tabs']=[
    $role,
    $permission,
    [ 'label'=>Yii::t('rf_user','Create Role'), 'url'=>Url::toRoute(['create','type'=>Item::TYPE_ROLE])],
    [ 'label'=>Yii::t('rf_user','Create Permission'), 'url'=>Url::toRoute(['create','type'=>Item::TYPE_PERMISSION])]
];

// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_user','Rbac'),
        'url'=>Url::toRoute(['index'])
    ]
];

?>
<div class="user-index">
    <?php echo $this->render('_grid',['data'=>$data]) ?>
</div>
