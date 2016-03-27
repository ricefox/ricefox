<?php
/**
 * This is the template for generating a module class file.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

$className = $generator->moduleClass;
$pos = strrpos($className, '\\');
$ns = ltrim(substr($className, 0, $pos), '\\');
$className = substr($className, $pos + 1);

$path=substr($generator->moduleClass, 0, strrpos($generator->moduleClass, '\\'));
$viewPath='@'.str_replace('\\','/',$path);

echo "<?php\n";
?>

namespace <?= $ns ?>;

class <?= $className ?> extends \yii\base\Module
{

    public $layout='main';
    public function init()
    {
        parent::init();
        if(defined('ADMIN')){
            $this->layoutPath='@backend/views/layouts';
            $this->controllerNamespace='<?=$path?>\bcontrollers';
            $this->setViewPath('<?=$viewPath?>/bviews');
        }else{
            $this->layoutPath='@frontend/views/layouts';
            $this->controllerNamespace='<?=$path?>\fcontrollers';
            $this->setViewPath('<?=$viewPath?>/fviews');
        }
    }
}
