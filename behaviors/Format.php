<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/26
 * Time: 8:25
 */

namespace ricefox\behaviors;

class Format extends \yii\base\Behavior
{
    function configFormat($string)
    {
        $return=[];
        if(trim($string)){
            // 每行为一项配置
            $lines=array_filter(explode("\n",$string));
            $array=[];
            foreach($lines as $item){
                if(!trim($item)){
                    continue;
                }
                // "键"与"值"(key=value)使用"="(等号)分割
                $f=explode('=',$item);
                if(count($f)===2){
                    $array[$f[0]]=$f[1];
                }
            }
            $return=$array;
        }
        return $return;
    }
}