<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/1
 * Time: 10:02
 */

namespace ricefox\city;

class CityAction extends \ricefox\base\Action
{
    public function run($type,$key)
    {
        $return=['status'=>false];
        $model=new CityModel();
        if($type=='city'){
            $data=$model->getCityByAlpha($key);
            $html=CityWidget::renderCityPanel($data,$key);
            $return['status']=true;
            $return['html']=$html;
        }else if($type=='area'){
            $data=$model->getArea($key);
            $return['status']=true;
            if($data){
                $html=CityWidget::renderArea($data,$key);
                $return['html']=$html;
            }else{
                $return['html']='';
            }
        }else if($type=='quan'){
            $data=$model->getQuan($key);
            $return['status']=true;
            if($data){
                $html=CityWidget::renderArea($data,$key);
                $return['html']=$html;
            }else{
                $return['html']='';
            }
        }
        echo json_encode($return);
    }
}