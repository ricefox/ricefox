<?php

namespace ricefox\article\models;

use Yii;
use ricefox\article\helpers\Url as Url2;
use yii\base\Exception;

/**
 * This is the model class for table "article_image".
 *
 * @property integer $id
 * @property integer $article_id
 * @property integer $category_id
 * @property string $url
 * @property string $image_url
 * @property string $title
 * @property string $key_name
 * @property string $position
 * @property int $expire
 * @property int $category_cond
 * @property string $color
 */

class ArticleImage extends \ricefox\base\ActiveRecord
{
    public $expireDay;
    public $expireAuto;
    public function behaviors()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id','article_id','expireDay','expire','category_cond'], 'integer'],
            [['url', 'image_url', 'title', 'key_name'], 'required'],
            [['url', 'image_url'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 30],
            ['expireAuto','string'],
            ['color','string','max'=>20],
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
            'category_id' => 'Category ID',
            'url' => 'Url',
            'image_url' => 'Image Url',
            'title' => 'Title',
            'key_name' => 'Key Name',
            'position' => 'Position',
        ];
    }

    public function beforeSave($insert)
    {
        if($this->expireDay==0){
            $this->expire=0;
        }else if($this->expireDay!=-1){
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
                $model=new ArticleImage();
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
        }catch(Exception $e){
            $trans->rollBack();
            throw new $e;
        }

    }


}
