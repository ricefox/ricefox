<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/6
 * Time: 17:27
 */

namespace ricefox\article\models;

use yii\db\Query;
use Yii;
class Show
{
    const POS_INDEX='index';
    const POS_CATEGORY='category';
    const POS_CONTENT='content';
    // 显示文章
    public static function article($id)
    {
        $db=Yii::$app->db;
        $sql='select a.*,ad.* from article a,article_data ad where a.status=1 and a.id=ad.id and a.id='.(int)$id;
        $data=$db->createCommand($sql)->queryOne();
        return $data;
    }
    // 上一篇
    public static function prevArticle($value,$categoryId)
    {
        $article=(new Query())->select('id,title,url')->from('article')->where('status=1 and category_id='.$categoryId)
            ->andWhere('created_at > '.$value)
            ->orderBy('created_at')->limit(1)->one();
        return $article;
    }
    // 下一篇
    public static function nextArticle($value,$categoryId)
    {
        $article=(new Query())->select('id,title,url')->from('article')->where('status=1 and category_id='.$categoryId)
            ->andWhere('created_at<'.$value)
            ->orderBy('created_at desc')->limit(1)->one();
        return $article;
    }
    public static function top($position,$category=[])
    {
        $query=(new Query())->select('id,title,url,description,color')->from('article_text')
            ->where('key_name="top" and position="'.$position.'" and (expire=0 or expire>'.time().') ');
        if($position==self::POS_CATEGORY){
            $showId=$category['showId'];
            if(strpos($showId,',')===false){
                $query->andWhere("(category_id=".$showId." or category_cond=0)");
            }else{
                $query->andWhere("(category_id in (".$showId.") or category_cond=0)");
            }
        }
        return $query->orderBy('sort')->all();
    }
    public static function image($keyName,$pos,$showId='',$limit=0)
    {
        $time=time();
        $query=(new Query())->select('id,title,url,image_url,color')->from('article_image')
            ->where("key_name=\"$keyName\" and position=\"$pos\" and (expire=0 or expire>$time)");
        if($pos==self::POS_CATEGORY){
            if(strpos($showId,',')===false){
                $query->andWhere("(category_id=".$showId." or category_cond=0)");
            }else{
                $query->andWhere("(category_id in (".$showId.") or category_cond=0)");
            }
        }
        $query->orderBy('sort');
        if($limit)$query->limit($limit);
        return $query->all();
    }
    public static function text($keyName,$pos,$showId='',$limit=0)
    {
        $time=time();
        $query=(new Query())->select('id,title,url,description,color')->from('article_text')
            ->where("key_name=\"$keyName\" and position=\"$pos\" and (expire=0 or expire>$time)");
        if($pos==self::POS_CATEGORY){
            if(strpos($showId,',')===false){
                $query->andWhere("(category_id=".$showId." or category_cond=0)");
            }else{
                $query->andWhere("(category_id in (".$showId.") or category_cond=0)");
            }
        }
        $query->orderBy('sort');
        if($limit){
            $query->limit($limit);
        }
        return $query->all();
    }
}