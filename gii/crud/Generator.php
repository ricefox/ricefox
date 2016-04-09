<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace ricefox\gii\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\web\Controller;

/**
 * Generates CRUD
 *
 * @property array $columnNames Model column names. This property is read-only.
 * @property string $controllerID The controller ID (without the module ID prefix). This property is
 * read-only.
 * @property array $searchAttributes Searchable attributes. This property is read-only.
 * @property boolean|\yii\db\TableSchema $tableSchema This property is read-only.
 * @property string $viewPath The controller view path. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */

class Generator extends \yii\gii\generators\crud\Generator
{
    public $breadcrumb;
    public $actions='create update delete view';
    /**
     * @var \yii\base\Model $model
     */

    public function beforeValidate()
    {
        $this->breadcrumb=ucfirst($this->breadcrumb);
        return true;
    }

    public function getColumns()
    {
        $class=$this->modelClass;
        $model=new $class();
        $attributes=$model->attributes();
        $array=$model->attributeLabels();
        $return=[];
        foreach($attributes as $attr){
            $return[$attr]=isset($array[$attr]) ? $array[$attr] : Inflector::camel2words($attr);
        }
        return $return;
    }
    public function rules()
    {
        $parent=parent::rules();
        $rules=[
            [['breadcrumb','actions'],'string']
        ];
        return array_merge($parent,$rules);
    }

    public function attributeLabels()
    {
        $parent=parent::attributeLabels();
        $labels=[
            'breadcrumb'=>'面包屑导航'
        ];
        return array_merge($parent,$labels);
    }

    public function getActiveLabels()
    {
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            $model=new $class();
            $labels=$model->attributeLabels();
            return $labels;
        } else {
            return false;
        }
    }

    public function getActions()
    {
        if($this->actions){
            return array_filter(explode(' ',$this->actions));
        }
        return [];
    }

}
