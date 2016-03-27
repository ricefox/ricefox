<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/19
 * Time: 20:36
 */

namespace ricefox\widgets;

use ricefox\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\ErrorHandler;
class Field extends \yii\base\Component
{
    public $isInline=false;
    public $defaultOptions=['class'=>'form-group','tag'=>'div'];
    public $defaultLabelOptions=['class'=>'control-label','label'=>''];
    public $defaultFieldOptions=['class'=>'form-control'];
    public $defaultTemplate="{label}\n{field}";
    public $options=[];
    public $labelOptions=[];
    public $fieldOptions=[];
    public $template="";
    public $parts=[];

    /**
     * PHP magic method that returns the string representation of this object.
     * @return string the string representation of this object.
     */
    public function __toString()
    {
        // __toString cannot throw exception
        // use trigger_error to bypass this limitation
        try {
            return $this->render();
        } catch (\Exception $e) {
            ErrorHandler::convertExceptionToError($e);
            return '';
        }
    }

    /**
     * @param $text string 静态控件的文本
     * @param array $options
     * @return $this
     */
    function staticText($text,$options=[])
    {
        if($options){
            $this->fieldOptions=ArrayHelper::merge($this->fieldOptions,$options);
        }

        $this->parts['{field}']=Html::staticText($text,$this->fieldOptions);
        $this->parts['{label}']='';
        return $this;
    }

    function dropDownList($items,$selection=null,$options=[])
    {
        $labelOptions=ArrayHelper::remove($options,'labelOptions',[]);
        if($labelOptions)$this->labelOptions=ArrayHelper::merge($this->labelOptions,$labelOptions);
        if($options){
            $this->fieldOptions=ArrayHelper::merge($this->fieldOptions,$options);
        }
        $this->parts['{field}']=Html::dropDownList('',$selection,$items,$this->fieldOptions);
        return $this;
    }
    /* 这个方法暂时没使用，不知道该怎么实现。，
    function Multi($items,$name,$type,$key='id')
    {
        if(method_exists($this,$type)){
            $return=[];
            foreach($items as $item){
                if(isset($item[$name]) && $item[$name]){

                }
            }
        }
    }
    */
    function render()
    {
        if(!isset($this->parts['{label}'])){
            $this->label();
        }
        if(!isset($this->parts['{field}'])){
            $this->textInput();
        }
        $content=strtr($this->template,$this->parts);
        $content=$this->begin()."\n".$content."\n".$this->end();
        if($this->isInline){
            $content=$this->inlineWrapper($content);
        }
        return $content;
    }

    function inlineWrapper($content)
    {
        return '<div class="form-inline col-block">'."\n".$content."\n"."</div>";
    }

    function begin()
    {
        $tag=ArrayHelper::remove($this->options,'tag','div');
        return Html::beginTag($tag,$this->options);
    }

    function textInput()
    {
        return '';
    }

    function width($field=200,$label=null,$container=null)
    {
        if($field){
            Html::addCssStyle($this->fieldOptions,Html::cssStyleValue($field));
        }
        if($label){
            Html::addCssStyle($this->labelOptions,Html::cssStyleValue($label));
        }
        if($container){
            Html::addCssStyle($this->options,Html::cssStyleValue($container));
        }
        return $this;
    }

    function end()
    {
        return Html::endTag(isset($this->options['tag'])?$this->options['tag']:'div');
    }

    function label($label=null,$options=[])
    {

        $this->labelOptions=array_merge($this->defaultLabelOptions,$this->labelOptions,$options);
        if(!$label && !$this->labelOptions['label']){
            $this->parts['{label}']='';
            return $this;
        }
        if(!$label){
            $label=$this->labelOptions['label'];
        }
        $for=ArrayHelper::remove($this->labelOptions,'for',null);
        $this->parts['{label}'] = Html::label($label, $for, $this->labelOptions);
        return $this;
    }

    function inline($options=[],$labelOptions=[],$fieldOptions=[])
    {
        $this->isInline=true;
        $this->config($options,$labelOptions,$fieldOptions);
        return $this;
    }
    function block($options=[],$labelOptions=[],$fieldOptions=[])
    {
        $this->isInline=false;
        $this->config($options,$labelOptions,$fieldOptions);
        return $this;
    }

    /**
     * @param $template
     * @return $this
     */
    function template($template)
    {
        $this->template=$template;
        return $this;
    }

    function config($options,$labelOptions,$fieldOptions)
    {
        $this->template=$this->defaultTemplate;//设置为默认模版。
        if($options){
            $this->options=ArrayHelper::merge($this->defaultOptions,$options);
        }else{
            $this->options=$this->defaultOptions;
        }
        if($labelOptions){
            $this->labelOptions=ArrayHelper::merge($this->defaultLabelOptions,$labelOptions);
        }else{
            $this->labelOptions=$this->defaultLabelOptions;
        }
        if($fieldOptions){
            $this->fieldOptions=ArrayHelper::merge($this->defaultFieldOptions,$fieldOptions);
        }else{
            $this->fieldOptions=$this->defaultFieldOptions;
        }

    }

}