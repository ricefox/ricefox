<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/30
 * Time: 17:37
 */

namespace ricefox\article\helpers;
use Yii;
class Url
{
    public static $siteUrl;
    public static function showUrl($id)
    {
        if(!static::$siteUrl){
            static::getSiteUrl();
        }
        $url=static::$siteUrl.'view/'.$id.'.html';
        return $url;
    }

    public static function getSiteUrl()
    {
        /** @var \ricefox\setting\Setting $setting */
        $setting=Yii::$app->get('setting');
        static::$siteUrl=$setting['site']['url'];
        return static::$siteUrl;
    }
}