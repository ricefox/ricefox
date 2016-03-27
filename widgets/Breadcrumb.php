<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/4
 * Time: 23:52
 */

namespace ricefox\widgets;

use yii\base\Widget;
use Yii;

class Breadcrumb extends Widget
{
    public $items;
    public $linkOptions=['class'=>'breadcrumb-item'];
    public $separator='&nbsp;&gt;&nbsp;';
    public $addHome=true;
    public function run()
    {
        $attr=' ';
        foreach($this->linkOptions as $key=>$item){
            $attr.=$key.'="'.$item.'" ';
        }
        $link='<a '.$attr.' ';
        $bread='';
        if($this->addHome){
            $b=$link.' href="'.Yii::$app->params['pc.url'].'">'.Yii::$app->params['site.name'].'</a>'.$this->separator;
            $bread.=$b;
        }
        foreach($this->items as $item){
            $b=$link.' href="'.$item['url'].'">'.$item['name'].'</a>';
            $bread.=$b.$this->separator;
        }
        $r=preg_quote($this->separator,'/');
        $bread=preg_replace('/'.$r.'$/','',$bread,1);
        return $bread;

    }
}