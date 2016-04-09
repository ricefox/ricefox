<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/15
 * Time: 19:36
 */

namespace ricefox\widgets;

use yii\bootstrap\Html;

class DataColumn extends \yii\grid\DataColumn
{
    public $field;
    public $class;
    public $width;
    public $options=[];
    public $inputType='text';
    public $hidden=false;
    public $rows;
    public function init()
    {
        parent::init();
        if($this->hidden===true){
            Html::addCssClass($this->headerOptions,'hidden');
            Html::addCssClass($this->contentOptions,'hidden');
        }
    }
    /**
     * @var Array dropDownList的items
     */
    public $items=null;
    // 获取一个单元格的内容
    function getDataCellValue($model,$key,$index)
    {
        if(!$this->field){
            return parent::getDataCellValue($model,$key,$index);
        }
        $this->format='raw';
        if(method_exists($this,$this->field)){
            $name=$this->field;
            return $this->$name($model,$key,$index);
        }
        return '';
    }
    // 使用input
    function input($model,$key,$index)
    {
        $value=parent::getDataCellValue($model,$key,$index);
        $options=$this->getOptions($model,$key,$index);
        $input='<input class="form-control" type="'.$this->inputType.'" value="'.$value.'" ';
        foreach($options as $key=>$value){
            $input.=' '.$key.'="'.$value.'" ';
        }
        $input.='/>';
        return $input;
    }
    // 使用input
    function textarea($model,$key,$index)
    {
        $text=parent::getDataCellValue($model,$key,$index);
        $options=$this->getOptions($model,$key,$index);
        $input='<textarea class="form-control" ';
        if($this->rows)$input.=' rows="'.$this->rows.'" ';
        foreach($options as $key=>$value){
            $input.=' '.$key.'="'.$value.'" ';
        }
        $input.='>'.$text.'</textarea>';
        return $input;
    }
    function dropDownList($model,$key,$index)
    {
        $value=parent::getDataCellValue($model,$key,$index);
        $options=$this->getOptions($model,$key,$index);
        if(!isset($options['class']))$options['class']='form-control';
        $select=Html::dropDownList($options['name'],$value,$this->items,$options);
        return $select;
    }


    // 属性的键值对name=>pairs
    function getOptions($model,$key,$index)
    {
        $options=$this->options;
        if($this->width){
            $style=isset($options['style'])?$options['style']:'';
            $style=array_filter(explode(';',$style));
            $width=false;
            foreach($style as $i=>$item){
                if(strpos($item,'width')){
                    $style[$i]='width:'.$this->width.'px';
                    $width=true;
                    break;
                }
            }
            if(!$width)$style[]='width:'.$this->width.'px';
            $options['style']=implode(';',$style);
        }
        if($this->class){
            $options['class']=$this->class;
        }
        if(isset($options['name'])){
            if($options['name'] instanceof \Closure){
                $options['name']=call_user_func($options['name'],$model,$key,$index);
            }
        }else{
            if(is_array($key)){
                // 这里是联合主键的情况
                $key=Html::encode(json_encode($key));
            }
            $options['name']=$key."[$this->attribute]";
        }
        return $options;
    }

}