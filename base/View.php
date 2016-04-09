<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/6
 * Time: 23:03
 */

namespace ricefox\base;

class View extends \yii\web\View
{
    /** @var  Array 栏目 */
    public $category;
    /** @var  string 面包屑导航 */
    public $breadcrumb;
    public $keywords;
    public $description;
    /** @var  string 页面类型，index|category|content 等不同类型，用于获取sidebar,footer等碎片数据 */
    public $pageType;
    public $isMobile;

    public function init()
    {
        parent::init();
        $this->isMobile=\Yii::$app->devicedetect->isMobile();
    }

    public function getMobileView($view)
    {
        if(!$this->isMobile)return $view;

        $first='';
        if(($pos=strrpos($view,'/'))!==false){
            $first=substr($view,0,$pos+1);
            $view=substr($view,$pos+1);
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