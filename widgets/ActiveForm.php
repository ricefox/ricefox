<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/10
 * Time: 12:54
 */

namespace ricefox\widgets;

use Yii;
use yii\base\InvalidCallException;
use yii\base\Widget;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;


class ActiveForm extends  \yii\widgets\ActiveForm
{
    public $fieldClass='ricefox\widgets\ActiveField';

    /**
     * @param $model
     * @param $attribute
     * @param array $options
     * @return \ricefox\widgets\ActiveField
     */
    function fieldInline($model,$attribute,$options=[])
    {
        $options['isInline']=true;
        return $this->field($model,$attribute,$options);

    }
    function withLinkage()
    {

    }
    /**
     * @param Model $model
     * @param string $attribute
     * @param array $options
     * @return \ricefox\widgets\ActiveField
     */
    function field($model,$attribute,$options=[])
    {
        return parent::field($model,$attribute,$options);
    }

    /**
     * Runs the widget.
     * This registers the necessary javascript code and renders the form close tag.
     * @throws InvalidCallException if `beginField()` and `endField()` calls are not matching
     */
    public function run()
    {
        parent::run();
        if ($this->enableClientScript) {
            $id = $this->options['id'];
            //$options = Json::htmlEncode($this->getClientOptions());
            //$attributes = Json::htmlEncode($this->attributes);
            $view = $this->getView();
            ActiveFormAsset::register($view);
            $view->registerJs("jQuery(function(){helpStyle(jQuery('#$id'));})");
        }
    }

}