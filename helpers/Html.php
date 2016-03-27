<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/25
 * Time: 16:05
 */

namespace ricefox\helpers;
use yii\helpers\ArrayHelper;
class Html extends \yii\helpers\BaseHtml
{
    public static function CssStyleValue($string)
    {
        if(strpos($string,'%')===false){
            $string.='px';
        }
        return $string;
    }
    public static function staticText($text,$options=[])
    {
        $defaultOptions=['class'=>'form-control-static','tag'=>'p','bold'=>true];
        $class=ArrayHelper::remove($options,'class','');
        if($options){
            $options=ArrayHelper::merge($defaultOptions,$options);
        }else{
            $options=$defaultOptions;
        }
        if($class){
            $class=trim(str_replace('form-control','',$class));
            if($class){
                Html::addCssClass($options,$class);
            }
        }
        if($options['bold']) Html::addCssClass($options,'bold');
        unset($options['bold']);
        $tag=ArrayHelper::remove($options,'tag','p');
        $html=Html::tag($tag,$text,$options);
        return $html;
    }
    public static function strCut($string,$length,$dot='...')
    {
        if(mb_strlen($string)>$length){
            $string=mb_substr($string,0,$length).$dot;
        }
        return $string;
    }
}