<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/7
 * Time: 14:46
 */

namespace ricefox\article\helpers;

use ricefox\block\models\Category;
use ricefox\article\models\Show;
use Yii;
class ViewHelper
{
    public static function sidebar()
    {
        /** @var \ricefox\base\View $view */
        $view=Yii::$app->view;
        $key=__CLASS__.':sidebar:'.$view->pageType;
        if($view->category)$key.=':'.$view->category['id'];
        $setting=Yii::$app->get('setting');
        $duration=isset($setting['core']['sidebar.duration'])?$setting['core']['sidebar.duration']: 36000;
        if($view->beginCache($key,['duration'=>$duration]))
        {
            $showId=$view->category?$view->category['showId']:'';
            $imageNumber=isset($setting['core']['sidebar.image.number'])?$setting['core']['sidebar.image.number']: 8;
            $textNumber=isset($setting['core']['sidebar.text.number'])?$setting['core']['sidebar.text.number']: 15;
            $images=Show::image('sidebar',$view->pageType,$showId,$imageNumber);
            $texts=Show::text('sidebar',$view->pageType,$showId,$textNumber);
            echo $view->render('@ricefox/views/frontend/_sidebar',['images'=>$images,'texts'=>$texts]);
            $view->endCache();
        }
    }
    //底部图片
    public static function footer()
    {
        /** @var \ricefox\base\View $view */
        $view=Yii::$app->view;
        $key=__CLASS__.':footer:'.$view->pageType;
        if($view->category)$key.=':'.$view->category['id'];
        $setting=Yii::$app->get('setting');
        $duration=isset($setting['core']['footer.duration'])?$setting['core']['footer.duration']: 36000;
        if($view->beginCache($key,['duration'=>$duration]))
        {
            $showId=$view->category?$view->category['showId']:'';
            $imageNumber=isset($setting['core']['footer.image.number'])?$setting['core']['footer.image.number']:10;
            $images=Show::image('footer',$view->pageType,$showId,$imageNumber);
            echo $view->render($view->getMobileView('@ricefox/views/frontend/_footer'),['images'=>$images]);
            $view->endCache();
        }
    }
    public static function footerText()
    {
        /** @var \ricefox\base\View $view */
        $view=Yii::$app->view;
        $key=__CLASS__.':footerText:'.$view->pageType;
        if($view->category)$key.=':'.$view->category['id'];
        $setting=Yii::$app->get('setting');
        $duration=isset($setting['core']['footer.duration'])?$setting['core']['footer.duration']: 36000;
        if($view->beginCache($key,['duration'=>$duration]))
        {
            $showId=$view->category?$view->category['showId']:'';
            $textNumber=isset($setting['core']['footer.text.number'])?$setting['core']['footer.text.number']: 20;
            $texts=Show::text('footer',$view->pageType,$showId,$textNumber);
            if($texts){
                echo $view->render($view->getMobileView('@ricefox/views/frontend/_footer-text'),['texts'=>$texts]);
            }
            $view->endCache();
        }
    }
    public static function mainNav()
    {
        $number=isset($setting['core']['mainNav.number'])?$setting['core']['mainNav.number']:20;
        $mainNav=Category::getMainNav($number);
        /** @var \ricefox\base\View $view */
        $view=Yii::$app->view;
        echo $view->render($view->getMobileView('@ricefox/views/frontend/_main-nav'),['menus'=>$mainNav]);
    }
    // 一个栏目的子栏目
    public static function subNav()
    {
        /** @var \ricefox\base\View $view */
        $view=Yii::$app->view;
        $pageType=$view->pageType;
        if($view->category && !empty($view->category['childNav'])){
            $subNav=$view->category['childNav'];
            echo $view->render($view->getMobileView('@ricefox/views/frontend/_sub-nav'),['menus'=>$subNav]);
        }
    }

    public static function seo()
    {
        $view=Yii::$app->view;
        $view->registerMetaTag(['name'=>'keywords','content'=>$view->keywords]);
        $view->registerMetaTag(['name'=>'description','content'=>$view->description]);
    }

    public static function relatedLink()
    {
        /** @var \ricefox\base\View $view */
        $view=Yii::$app->view;
        $category=$view->category;
        $key=__CLASS__.':relatedLink:';
        if($category){
            $key.='category:'.$category['id'];
        }else{
            $key.='index';
        }
        if($view->beginCache($key,['duration'=>0]))
        {
            if(!empty($category['relatedLink'])){
                $relatedLink=$category['relatedLink'];
            }else{
                $relatedLink=\ricefox\block\models\RelatedLink::getByCategoryId(0);
            }
            if($relatedLink){
                echo $view->render($view->getMobileView('@ricefox/views/frontend/_related-link'),['relatedLink'=>$relatedLink]);
            }
            $view->endCache();
        }
        //$relatedLink=!empty($category['relatedLink'])?$category['relatedLink']:null;

    }
    public static function footerLink()
    {
        /** @var \ricefox\base\View $view */
        $view=Yii::$app->view;
        $category=$view->category;
        $key=__CLASS__.':footerLink:';
        if($category){
            $key.='category:'.$category['id'];
            $categoryId=$category['id'];
        }else{
            $key.='index';
            $categoryId=0;
        }
        $duration=isset($setting['core']['footer.duration'])?$setting['core']['footer.duration']: 36000;
        if($view->beginCache($key,['duration'=>$duration]))
        {
            $showId=$category?$category['showId']:'';
            $links=\ricefox\block\models\FooterLink::getLinksByCategoryId($categoryId,$showId);
            if($links){
                echo $view->render($view->getMobileView('@ricefox/views/frontend/_footer-link'),['links'=>$links]);
            }
            $view->endCache();
        }
    }

}