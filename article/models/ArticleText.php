<?php

namespace ricefox\article\models;

use Yii;
use ricefox\article\helpers\Url as Url2;
/**
 * This is the model class for table "article_text".
 *
 * @property integer $id
 * @property integer $article_id
 * @property integer $category_id
 * @property string $url
 * @property string $title
 * @property string $description
 * @property string $key_name
 * @property string $position
 * @property integer $expire
 * @property int $category_cond
 * @property string $color
 */

class ArticleText extends \ricefox\base\ActiveRecord
{
    public $expireDay;
    public $expireAuto;

    public function behaviors()
    {
        return [

        ];
    }
    public function afterFind()
    {
        if($this->expire){
            $this->expireAuto=date('Y-m-d H:i:s',$this->expire);
            $arr=explode(' ',$this->expireAuto);
            if(substr($arr[1],0,2)==='00'){
                $this->expireAuto=$arr[0];//去掉时间
            }else if(substr($arr[1],-2)==='00'){
                $this->expireAuto=$arr[0].' '.substr($arr[1],0,-3);
            }
        }
        return parent::afterFind();
    }
    public function beforeSave($insert)
    {
        if($this->expireDay!==null && $this->expireDay==0){
            $this->expire=0;
        }else if($this->expireDay>-1){
            $this->expire=strtotime('+'.$this->expireDay.' day');
        }else{
            $auto=$this->expireAuto;
            if(is_numeric($auto)){
                $this->expire=strtotime('+'.$auto.' day');
            }else{
                $this->expire=strtotime($auto);
            }
        }
        $this->expire=(int)$this->expire;
        return parent::beforeSave($insert);
    }
    public function add($post,$rows)
    {
        if(!$rows)return '添加数据不能为空';
        $trans=$this->getDb()->beginTransaction();
        try{
            foreach($rows as $key=>$row){
                $model=new ArticleText();
                $model->attributes=$row;
                $model->load($post);
                if(!$model->url){
                    $model->url=Url2::showUrl($model->article_id);
                }
                if($model->insert()===false){
                    $trans->rollBack();
                    return $model->getErrorString();
                }
            }
            $trans->commit();
            return true;
        }catch(\Exception $e){
            $trans->rollBack();
            throw new $e;
        }

    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'url', 'title', 'key_name'], 'required'],
            [['article_id', 'category_id', 'expire','expireDay','category_cond'], 'integer'],
            [['url', 'description'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 30],
            [['expireAuto'], 'string'],
            [['color'], 'string','max'=>20],
            [['key_name', 'position'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Article ID',
            'category_id' => 'Category ID',
            'url' => 'Url',
            'title' => 'Title',
            'description' => 'Description',
            'key_name' => 'Key Name',
            'position' => 'Position',
            'expire' => 'Expire',
        ];
    }


}
