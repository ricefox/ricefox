<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/4
 * Time: 17:35
 */

namespace ricefox\console;

class CrawlController extends \yii\console\Controller
{
    public $cityUrl='http://jianli.58.com/ajax/getareainfo/?t=1459776325746&operate=getcitybyzm&zm=';
    public $areaUrl='http://jianli.58.com/ajax/getareainfo/?t=1459780047171&operate=getareabyid&pid=';
    //public $url='http://jianli.58.com/ajax/getareainfo/?t=1459780913214&operate=getareabyid&pid=1143';
    public function actionTest()
    {
        $tpl=\Yii::getAlias('@ricefox/console/tpl.php');
        $tpl=file_get_contents($tpl);
        $body=file_get_contents(\Yii::getAlias('@ricefox/console/hot.php'));
        $html=strtr($tpl,['{body}'=>$body]);
        $hot=$this->extractHot($html);
        $p=\Yii::getAlias('@ricefox/city/data/alpha/hot.php');
        file_put_contents($p,"<?php \n return ".var_export($hot,true).';');
    }
    public function extractHot($html)
    {
        $doc=new \DOMDocument();
        $doc->loadHTML($html);
        $xpath=new \DOMXPath($doc);
        $links=$xpath->query('//a');
        $array=[];
        foreach($links as $link){
            //print_r($link);
            $d=$link->getAttribute('d');
            $arr=explode(',',$d);
            $array[$arr[0]]=$arr[1];
        }
        return $array;
    }
    public function extractCity($html)
    {
        $doc=new \DOMDocument();
        $doc->loadHTML($html);
        $xpath=new \DOMXPath($doc);
        $links=$xpath->query('//dl[@class="wordindex"]/dd/ul/li/a');
        $array=[];
        foreach($links as $link){
            $href=$link->getAttribute('href');
            preg_match('/\(([^\)]+)\)/',$href,$match);
            $str=$match[1];
            $arr=explode(',',$str);
            $array[$arr[0]]=$arr[1];
        }
        return $array;
    }

    public function actionGetCity()
    {
        $ab=['a','e','k','p','w'];
        $tpl=\Yii::getAlias('@ricefox/console/tpl.php');
        $tpl=file_get_contents($tpl);
        $array=[];
        foreach($ab as $item){
            $url=$this->cityUrl.$item;
            $response=\Requests::get($url);
            $body=$response->body;
            $html=strtr($tpl,['{body}'=>$body]);
            $array+=$this->extractCity($html);
        }
        file_put_contents('city.php',"<?php \n return".var_export($array,true).';');
    }

    public function actionGetArea()
    {
        $tpl=\Yii::getAlias('@ricefox/console/tpl.php');
        $tpl=file_get_contents($tpl);
        $array=[];
        $cities=require(\Yii::getAlias('@ricefox/city.php'));
        foreach($cities as $id=>$name){
            $response=\Requests::get($this->areaUrl.$id);
            $body=$response->body;
            $html=strtr($tpl,['{body}'=>$body]);
            $arr=$this->extractArea($html);
            file_put_contents("area/$id.php","<?php \n return ".var_export($arr,true).';');
        }

    }
    public function extractArea($html)
    {
        $doc=new \DOMDocument();
        $doc->loadHTML($html);
        $xpath=new \DOMXPath($doc);
        $links=$xpath->query('//ul[@class="cities"]/li/a');
        $array=[];
        foreach($links as $link){
            $href=$link->getAttribute('href');
            if(preg_match('/\(([0-9]+,[^\)]+)\)/',$href,$match)){
                $str=$match[1];
                $arr=explode(',',$str);
                $array[$arr[0]]=$arr[1];
            }
        }
        return $array;
    }

    public function actionGetQuan()
    {
        $tpl=\Yii::getAlias('@ricefox/console/tpl.php');
        $tpl=file_get_contents($tpl);
        $array=[];
        $cities=require(\Yii::getAlias('@ricefox/area.php'));
        $path=\Yii::getAlias('@ricefox/quan/');
        foreach($cities as $id=>$name){
            $f=$path.$id.'.php';
            if(file_exists($f)){
                continue;
            }
            $response=\Requests::get($this->areaUrl.$id);
            $body=$response->body;
            $html=strtr($tpl,['{body}'=>$body]);
            $arr=$this->extractArea($html);
            if($arr){
                file_put_contents("quan/$id.php","<?php \n return ".var_export($arr,true).';');
            }
        }

    }

}