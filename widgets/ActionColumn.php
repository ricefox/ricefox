<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/16
 * Time: 20:00
 */

namespace ricefox\widgets;

class ActionColumn extends \yii\grid\ActionColumn
{
    public $header='操作';
    public $template='';
    public $showView=false;
    public $showDelete=true;
    public $showUpdate=true;
    public function init()
    {
        parent::init();
        if(!$this->template){
            $templates=[];
            if($this->showView){
                $templates[]='{view}';
            }
            if($this->showUpdate){
                $templates[]='{update}';
            }
            if($this->showDelete){
                $templates[]='{delete}';
            }
            $this->template=implode('&nbsp;&nbsp',$templates);
        }
    }

}