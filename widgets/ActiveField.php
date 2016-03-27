<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/10
 * Time: 12:56
 */

namespace ricefox\widgets;
use yii\helpers\ArrayHelper;
use ricefox\helpers\Html;

class ActiveField extends \yii\widgets\ActiveField
{
    public $isInline=false;
    public $isGroupInline=false;
    public $isHorizontal=false;//
    function init()
    {
        parent::init();
        if($this->isInline){
            $this->template="{label}\n{input}\n{hint}\n{error}";
        }
    }
    function render($content=null)
    {
        if ($content === null) {
            if (!isset($this->parts['{input}'])) {
                $this->textInput();
            }
            if (!isset($this->parts['{label}'])) {
                $this->label();
            }
            if (!isset($this->parts['{error}'])) {
                $this->error();
            }
            if (!isset($this->parts['{hint}'])) {
                $this->hint(null);
            }
            $content = strtr($this->template, $this->parts);
        } elseif (!is_string($content)) {
            $content = call_user_func($content, $this);
        }
        $html=$this->begin() . "\n" . $content . "\n" . $this->end();

        if($this->isInline){

            $html='<div class="form-inline col-block">'.$html.'</div>';
        }
        return $html;
    }

    function dropDownList($items,$options=[])
    {
        if(!isset($options['encode']))$options['encode']=false;

        return parent::dropDownList($items,$options);
    }

    function hiddenInput($options=[])
    {
        return parent::hiddenInput($options)->label(false);
    }

    function checkbox($options = [], $enclosedByLabel = true)
    {
        Html::addCssClass($options,'col');//添加col让选项框与文字垂直居中。
        $addCol=false;
        if(!isset($options['label'])){
            //添加col 垂直居中。
            $addCol=true;
            $options['label']=Html::encode($this->model->getAttributeLabel(Html::getAttributeName($this->attribute)));
        }
        // 不含有html标签
        else if(!preg_match('/<\s+[^<>\s]+\*>/',$options['label'])){
            $addCol=true;
        }
        if($addCol){
            $options['label']='<span class="col">'.$options['label'].'</span>';
        }
        if($this->isInline){
            Html::addCssStyle($this->options,'margin-top:5px;');
        }
        return parent::checkbox($options, $enclosedByLabel);
    }



    function checkboxList($items,$options=[])
    {
        $before=ArrayHelper::remove($options,'before','');
        $array=$this->_checkBoxList($items,$options=[]);
        parent::checkboxList($array[0],$array[1]);
        $this->parts['{input}']=$before.$this->parts['{input}'];
        return $this;
    }
    public function addCheckBoxList($items,$options=[])
    {
        $before=ArrayHelper::remove($options,'before','');
        $array=$this->_checkboxList($items,$options);
        $items=$array[0];
        $options=$array[1];
        $this->adjustLabelFor($options);
        $this->parts['{input}'].=$before;
        $this->parts['{input}'].= Html::activeCheckboxList($this->model, $this->attribute, $items, $options);
        return $this;
    }
    protected function _checkBoxList($items,$options=[])
    {
        if(!isset($options['horizontal'])){
            $options['horizontal']=true;
        }
        if($options['horizontal']){
            Html::addCssClass($options,'col');
            Html::addCssStyle($options,'margin-left:8px;');
            Html::addCssClass($this->labelOptions,'col');
        }
        if($this->isInline){
            Html::addCssStyle($this->options,'margin-top:5px;');
            Html::addCssStyle($this->labelOptions,'margin-top:-5px;');
        }
        unset($options['horizontal']);
        if(!isset($options['encode'])){
            $options['encode']=false;
        }
        if(!$options['encode']){
            $items=array_map(function($item){
                if(!preg_match('/<\s+[^<>\s]+\*>/',$item)){
                    return '<span class="col">'.$item.'</span>';
                }else{
                    return $item;
                }
            },$items);
        }
        if(!isset($options['itemOptions']))$options['itemOptions']=[];
        if(isset($options['itemOptions']['labelOptions']))$options['itemOptions']['labelOptions']=[];
        Html::addCssClass($options['itemOptions'],'col');
        Html::addCssStyle($options['itemOptions']['labelOptions'],'margin-right:3px;');
        return [$items,$options];
    }
    function width($field=200,$label=null)
    {
        $field='width:'.Html::CssStyleValue($field);
        Html::addCssStyle($this->inputOptions,$field);
        if($label){
            $label='width:'.Html::CssStyleValue($label);
            Html::addCssStyle($this->labelOptions,$label);
        }
        return $this;
    }

}