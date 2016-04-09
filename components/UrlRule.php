<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/4
 * Time: 22:11
 */

namespace ricefox\components;

use yii\web\UrlRuleInterface;
use yii\base\Object;
use ricefox\block\models\UrlModel;

class UrlRule extends Object implements UrlRuleInterface
{

    public function createUrl($manager, $route, $params)
    {
        if ($route === 'car/index') {
            if (isset($params['manufacturer'], $params['model'])) {
                return $params['manufacturer'] . '/' . $params['model'];
            } elseif (isset($params['manufacturer'])) {
                return $params['manufacturer'];
            }
        }
        return false;  // this rule does not apply
    }

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        //if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches)) {
            // check $matches[1] and $matches[3] to see
            // if they match a manufacturer and a model in the database
            // If so, set $params['manufacturer'] and/or $params['model']
            // and return ['car/index', $params]
        //}
        $params=[];

        $pathInfo=trim($pathInfo,'/');
        $arr=explode('/',$pathInfo);
        $len=count($arr);
        $last=$arr[$len-1];
        if(is_numeric($last)){
            unset($arr[$len-1]);//去掉分页数。
            $pathInfo=implode('/',$arr);
            $params['page']=$last;
        }
        $domain=parse_url($request->getHostInfo(),PHP_URL_HOST);
        $key=$domain.'/'.$pathInfo;
        $params['pathInfo']=$pathInfo;
        $url=UrlModel::getUrlByKey($key);
        if($url){
            $params['targetId']=$url['targetId'];
            return [$url['route'],$params];
        }
        return false;  // this rule does not apply
    }
}