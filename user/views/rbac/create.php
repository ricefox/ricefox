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
/** @var $this yii\web\View */
/** @var $model ricefox\user\models\AuthItemForm */
/** @var $roles Array */
/** @var  $typeString string */

$permission=[ 'label'=>Yii::t('rf_user','Create Permission'), 'url'=>Url::toRoute(['create','type'=>Item::TYPE_PERMISSION])];
$role=[ 'label'=>Yii::t('rf_user','Create Role'), 'url'=>Url::toRoute(['create','type'=>Item::TYPE_ROLE])];
if($model->type==Item::TYPE_ROLE){
    $role['active']=true;
}else if($model->type==Item::TYPE_PERMISSION){
    $permission['active']=true;
}
// 标签
$this->params['tabs']=[
    [ 'label'=>Yii::t('rf_user','Role'), 'url'=>Url::toRoute('index')],
    [ 'label'=>Yii::t('rf_user','Permission'), 'url'=>Url::toRoute(['index','type'=>Item::TYPE_PERMISSION])],
    $role,
    $permission
];
// 面包屑导航
$this->params['breadcrumbs']=[
    [
        'label'=>Yii::t('rf_user','Rbac'),
        'url'=>Url::toRoute(['index'])
    ],
    [
        'label'=>Yii::t('rf_user','Create '.$typeString),
    ]
];

?>
<div class="user-index">
    <?php echo $this->render('_form',['model'=>$model,'submit'=>'Create '.$typeString]) ?>
</div>
