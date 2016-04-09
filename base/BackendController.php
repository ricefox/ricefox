<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/26
 * Time: 16:13
 */

/** @property $module \ricefox\base\Module */

namespace ricefox\base;
use Yii;
use yii\base\Exception;

class BackendController extends \yii\web\Controller
{
    public $layout='@ricefox/views/backend';

    public function init()
    {
        parent::init();
        if($this->module->hasSetting){
            $this->module->loadModuleSetting();
        }
    }

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
    public function failed($action,$message='')
    {
        if(!$message){
            switch($action)
            {
                case 'add':
                    $message='添加失败';
                    break;
                case 'update':
                    $message='更新失败';
                    break;
                case 'delete':
                    $message='删除失败';
                    break;
                default:
                    $message='操作失败';
            }
        }
        \Yii::$app->session->setFlash('error',$message);
    }
    public function success($action,$message='')
    {
        if(!$message){
            switch($action)
            {
                case 'add':
                    $message='添加成功';
                    break;
                case 'update':
                    $message='更新成功';
                    break;
                case 'delete':
                    $message='删除成功';
                    break;
                default:
                    $message='操作成功';
            }
        }
        \Yii::$app->session->setFlash('success',$message);
    }

    /**
     * 在transaction状态下调用$model的save方法
     * @param ActiveRecord $model
     * @return bool|string
     * @throws \yii\db\Exception
     */
    public function save(ActiveRecord &$model)
    {
        $transaction=$model->getDb()->beginTransaction();
        try{
            if($model->save()===true){
                $transaction->commit();
                return true;
            }else{
                $transaction->rollBack();
                return false;
            }
        }catch (Exception $e){
            $transaction->rollBack();
            return $e->getMessage();
        }
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