<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/29
 * Time: 12:16
 */
/** @var $this yii\web\View */
/** @var $model ricefox\block\models\Category  */
/** @var $parents Array  */

use ricefox\widgets\ActiveForm;
// 去掉<form>标签及crsf hidden
ob_start();
$form=new ActiveForm();
ob_end_clean();
$options=[
    'options'=>$parents[1],
    'prompt'=>'请选择父栏目'
]
?>
<?php echo $form->fieldInline($model,'parent_id')->dropDownList($parents[0],$options); ?>
