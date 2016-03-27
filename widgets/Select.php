<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/3
 * Time: 23:49
 */

namespace ricefox\widgets;

use yii\base\Widget;

class Select extends Widget
{

    /**
     * @var array 键值对，key为option的值，value为option的名称
     */
    public $items=[];
    /**
     * @var int 选中的值
     */
    public $selected=0;
    /**
     * @var array 禁用的值
     */
    public $disabled=[];
    /**
     * @var array select元素的属性
     */
    public $attributes=[];
    /**
     * @var string 占位符，默认为false
     */
    public $place=false;
    /**
     * @var int 占位符对应的值，默认为0.
     */
    public $placeValue=0;

    public function run()
    {
        $select='<select ';
        if($this->attributes){
            foreach($this->attributes as $key=>$value){
                $select.=' '.$key.'="'.$value.'" ';
            }
        }
        $select.='>'."\n";
        if($this->place){
            $select.='<option value="'.$this->placeValue.'" '.($this->selected==$this->placeValue ? 'selected':'').' >';
            $select.=$this->place.'</option>'."\n";
        }
        if($this->items){
            foreach($this->items as $key=>$value){
                $select.='<option value="'.$key.'" '.($this->selected==$key ? 'selected':'');
                if(in_array($key,$this->disabled)){
                    $select.=' disabled ';
                }
                $select.='>';
                $select.=$value.'</option>'."\n";
            }
        }
        $select.='</select>';
        return $select;
    }
}