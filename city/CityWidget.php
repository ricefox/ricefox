<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/4
 * Time: 22:57
 */

namespace ricefox\city;

use yii\helpers\Url;

class CityWidget extends \yii\base\Widget
{
    /** @var  \ricefox\widgets\ActiveForm */
    public $form;
    /** @var  \ricefox\base\ActiveRecord */
    public $model;

    public function run()
    {
        $rootTpl=<<<HTML
<div class="city-container" id="cityContainer">
<div class="form-inline col dropdown" id="CityWrap">
    <input id="city" type="text" class="form-control dropdown-toggle" readonly placeholder="请选择城市" />
    <i class="caret" style="margin-left: -20px;"></i>
    <div class="dropdown-panel hidden" id="cityPanel">{cityPanel}</div>
</div>
<div class="form-inline col dropdown" id="areaWrap">
    <input id="area" type="text" class="form-control dropdown-toggle" readonly placeholder="请选择区域" />
    <i class="caret" style="margin-left: -20px;"></i>
    <div class="dropdown-panel hidden" id="areaPanel">{areaPanel}</div>
</div>
<div class="form-inline col dropdown" id="quanWrap">
    <input id="quan" type="text" class="form-control dropdown-toggle" readonly placeholder="请选择商圈" />
    <i class="caret" style="margin-left: -20px;"></i>
    <div class="dropdown-panel hidden" id="quanPanel"></div>
</div>
<div class="form-inline col city-item" id="addressWrap">
    <input id="address" type="text" class="form-control" placeholder="详细地址" />
</div>
</div>
HTML;

        $model=new CityModel();
        $groups=$model->getCityAlpha();
        $cities=[];
        foreach($groups as $key=>$value){
            $data=$model->getCityByAlpha($key);
            $cities[$key]=$this->renderCityPanel($data,$key);
        }
        $controller=\Yii::$app->controller->id;

        $options=[
            //'cityData'=>$cities,
            'getCityUrl'=>Url::toRoute([$controller.'/city','type'=>'city']),
            'getAreaUrl'=>Url::toRoute([$controller.'/city','type'=>'area']),
            'getQuanUrl'=>Url::toRoute([$controller.'/city','type'=>'quan'])
        ];
        $options=json_encode($options);
        $areaHtml='';
        $tab=$this->renderCityTab();
        $html=strtr($rootTpl,['{cityPanel}'=>$tab.$cities['abcd'],'{areaPanel}'=>$areaHtml]);
        CityAsset::register($this->getView());
        $this->getView()->registerJs("window.cityOptions='$options'");
        echo $html;
    }

    public static function renderCityTab()
    {
        $tabs=CityModel::getCityAlpha();
        $tabTpl=<<<HTML
<ul class="panel-tab">
    {li}
</ul>
HTML;
        $liTpl=<<<HTML
<li class="col2 tab-item"><a class="col" id2="{id}">{name}</a> </li>
HTML;
        $liHtml='';
        foreach($tabs as $id=>$name){
            $liHtml.=strtr($liTpl,['{id}'=>$id,'{name}'=>$name]);
        }
        $tabHtml=strtr($tabTpl,['{li}'=>$liHtml]);
        return $tabHtml;
    }

    public static function renderCityPanel($cities,$key)
    {
        if(is_array(current($cities)))
        {
            $dlTpl=<<<HTML
<dl class="clear-fix">
<dt class="col-fixed city-initial">{initial}</dt>
<dd class="col-auto">
<ul>
{li}
</ul>
</dd>
</dl>
HTML;
            $liTpl=<<<HTML
<li class="col2 city-item"><a class="col2 item" id2="{id}">{name}</a> </li>
HTML;
            $html='';
            foreach($cities as $initial=>$city){
                $htmlLi='';
                foreach($city as $id=>$name){
                    $htmlLi.=strtr($liTpl,['{id}'=>$id,'{name}'=>$name]);
                }
                $dl=strtr($dlTpl,['{initial}'=>strtoupper($initial),'{li}'=>$htmlLi]);
                $html.=$dl;
            }
        }
        else //热门城市
        {
            $tpl=<<<HTML
<ul class="hot-city">{li}</ul>
HTML;
            $liTpl=<<<HTML
<li class="col2"><a class="col2 item" id2="{id}">{name}</a> </li>
HTML;
            $htmlLi='';
            foreach($cities as $id=>$name){
                $htmlLi.=strtr($liTpl,['{id}'=>$id,'{name}'=>$name]);
            }
            $html=strtr($tpl,['{li}'=>$htmlLi]);

        }
        $tpl=<<<HTML
<div class="panel-content" id="data{$key}">{content}</div>
HTML;

        $html=strtr($tpl,['{content}'=>$html]);

        return $html;
    }

    public static function renderArea($areas,$pid,$include='[不限]')
    {
        $tpl=<<<HTML
<div class="panel-content" id="data{$pid}"><ul>{li}</ul></div>
HTML;
        $liTpl=<<<HTML
<li class="col2 area-item"><a class="col2 item" id2="{id}">{name}</a> </li>
HTML;
        $liHtml='';
        if($include!==false){
            $areas=[-1=>$include]+$areas;
        }
        foreach($areas as $id=>$name){
            $liHtml.=strtr($liTpl,['{id}'=>$id,'{name}'=>$name]);
        }
        $html=strtr($tpl,['{li}'=>$liHtml]);
        return $html;
    }
}