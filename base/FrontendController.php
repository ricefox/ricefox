<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/6
 * Time: 18:52
 */

namespace ricefox\base;
use Yii;
class FrontendController extends \yii\web\Controller
{
    public $layout='@ricefox/views/frontend';
    public $isMobile;
    public function init()
    {
        parent::init();
        $this->isMobile=$this->view->isMobile;
        if($this->isMobile){
            $this->layout='@ricefox/views/m-frontend';
        }
    }

    public function renderDevice($view,$params=[])
    {
        if($this->isMobile){
            $view=$this->view->getMobileView($view);
        }
        return parent::render($view,$params);
    }
    public function getMobileView($view)
    {
        $first='';
        if(($pos=strrpos($view,'/')!==false)){
            $view=substr($view,$pos+1);
            $first=substr($view,0,$pos+1);
        }
        if($view[0]==='_'){
            $view=substr($view,1);
            $view='_m-'.$view;
        }else{
            $view='m-'.$view;
        }
        return $first.$view;
    }
}