<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/28
 * Time: 23:13
 */

namespace ricefox\behaviors;

class ActiveTree extends \yii\base\Behavior
{
    public $idName='id';
    public $parentIdName='parent_id';
    public $name='name';
    public $symbol=' ··├ ';
    public $symbolLevel=0;
    public function createTree(Array $arrays)
    {
        return $this->createTreeInternal($arrays);
    }
    public function createTreeInternal($arrays,$parentId=0,$level=0)
    {
        $arr=[];
        foreach($arrays as $key=> $array)
        {
            if($array[$this->parentIdName]==$parentId){
                $lev=$level-$this->symbolLevel;
                $lev=$lev>0?$lev:0;
                $str=str_repeat($this->symbol,$lev);
                $array[$this->name]=$str.$array[$this->name];
                $arr[$array[$this->idName]]=$array;
                unset($arrays[$key]);
                $arr+=$this->createTreeInternal($arrays,$array[$this->idName],$level+1);
            }
        }
        return $arr;
    }
}