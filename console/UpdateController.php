<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/7
 * Time: 17:19
 */

namespace ricefox\console;
use yii\db\Query;
use ricefox\article\models\Article;
use ricefox\article\helpers\Url;
class UpdateController extends \yii\console\Controller
{
    public function actionUrl()
    {
        $query=Article::find();
        $update=Article::getDb()->createCommand('update article set url=:url where id=:id');
        foreach($query->each() as $article){
            if(!$article['is_link']){
                $url=Url::showUrl($article['id']);
                $params=[
                    ':url'=>$url,
                    ':id'=>$article['id']
                ];
                $update->bindValues($params);
                $update->execute();
                echo "update id ".$article['id'].' success'.PHP_EOL;
            }
        }
    }
}