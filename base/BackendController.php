<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/26
 * Time: 16:13
 */

namespace ricefox\base;
use Yii;
class BackendController extends \yii\web\Controller
{
    /**
     * 添加删除失败的提示
     * @param string $message
     */
    public function deleteFailed($message='删除失败')
    {
        \Yii::$app->session->setFlash('error',$message);
    }

    /**
     * 添加删除成功的提示
     * @param string $message
     */
    public function deleteSuccess($message='删除成功')
    {
        \Yii::$app->session->setFlash('success',$message);
    }

    /**
     * 获取GridView提交的多行数据
     * @return array
     */
    public function getMultiRows()
    {
        $post=Yii::$app->request->post();
        $selection=isset($post['selection']) ? $post['selection']:[];
        $array=[];
        foreach($selection as $key){
            if(isset($post[$key])){
                $array[$key]=$post[$key];
            }
        }
        return $array;
    }

    /**
     * 获取GridView提交的主键，单主键或联合主键
     * @return array
     */
    public function getKeys()
    {
        $post=Yii::$app->request->post();
        $selection=isset($post['selection']) ? $post['selection']:[];
        $array=[];
        // $key 可能为字符串(数字|字母等字符)，和json字符串(联合主键)。
        foreach($selection as $key){
            $keys=json_decode($key,true);
            if(is_array($keys)){
                $array[]=$keys;
            }else{
                $array[]=$key;
            }
        }
        return $array;
    }
}