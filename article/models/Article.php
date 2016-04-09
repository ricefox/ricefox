<?php

namespace ricefox\article\models;

use Yii;
use ricefox\block\models\Category;
use ricefox\article\helpers\Url;
/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $type_id
 * @property string $title
 * @property string $thumbnail
 * @property string $keywords
 * @property string $description
 * @property string $url
 * @property integer $sort
 * @property integer $status
 * @property integer $user_id
 * @property string $username
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $has_code
 * @property integer $replies
 * @property string $source
 * @property integer $views
 */

class Article extends \ricefox\base\ActiveRecord
{
    public $isDesc=1;
    public $descLength=200;
    public $isThumbnail=1;
    public $thumbnailOrder=1;
    public $content;//用于提取desc
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'title'], 'required'],
            [['category_id', 'type_id', 'sort', 'status', 'user_id', 'created_at', 'updated_at', 'has_code', 'replies', 'views','isDesc','descLength','isThumbnail','thumbnailOrder'], 'integer'],
            [['title'], 'string', 'max' => 200],
            [['thumbnail', 'url'], 'string', 'max' => 100],
            [['keywords'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 1000],
            [['username', 'source'], 'string', 'max' => 14],

        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(),['id'=>'category_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rf_article', 'ID'),
            'category_id' => Yii::t('rf_article', 'Category ID'),
            'type_id' => Yii::t('rf_article', 'Type ID'),
            'title' => Yii::t('rf_article', 'Title'),
            'thumbnail' => Yii::t('rf_article', 'Thumbnail'),
            'keywords' => Yii::t('rf_article', 'Keywords'),
            'description' => Yii::t('rf_article', 'Description'),
            'url' => Yii::t('rf_article', 'Url'),
            'sort' => Yii::t('rf_article', 'Sort'),
            'status' => Yii::t('rf_article', 'Status'),
            'user_id' => Yii::t('rf_article', 'User ID'),
            'username' => Yii::t('rf_article', 'Username'),
            'created_at' => Yii::t('rf_article', 'Created At'),
            'updated_at' => Yii::t('rf_article', 'Updated At'),
            'has_code' => Yii::t('rf_article', 'Has Code'),
            'replies' => Yii::t('rf_article', 'Replies'),
            'source' => Yii::t('rf_article', 'Source'),
            'views' => Yii::t('rf_article', 'Views'),
            'descLength'=>'',
            'thumbnailOrder'=>''
        ];
    }
    public function beforeSave($insert)
    {
        $this->updated_at=time();
        if($insert){
            $this->created_at=strtotime($this->created_at);
            if(!$this->created_at){
                $this->created_at=time();
            }
        }
        if(!trim($this->description) && $this->isDesc){
            $content=strip_tags($this->content);
            $length=$this->descLength;
            $this->description=mb_substr($content,0,$length,\Yii::$app->charset);
        }
        if($this->keywords){
            if(strpos($this->keywords,',')===false &&  strpos($this->keywords,' ')!==false){
                $this->keywords=preg_replace('/[\s]+/',',',$this->keywords);
            }
        }
        return parent::beforeSave($insert);
    }
    public static function deleteById($id)
    {
        $transaction=Article::getDb()->beginTransaction();
        try{
            $article=Article::findOne($id);
            $articleData=ArticleData::findOne($id);
            if($article->delete()===false || $articleData->delete()===false){
                $transaction->rollBack();
                return false;
            }else{
                $transaction->commit();
                return true;
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }

    public function deleteMultiRows(Array $keys)
    {
        foreach($keys as $id){
            $this->deleteById($id);
        }
        return true;
    }
    /**
     * 添加文章。
     * @param $data ArticleData
     * @return bool
     * @throws \yii\db\Exception
     */
    public function saveArticle(&$data)
    {
        $transaction=$this->getDb()->beginTransaction();
        if($this->save()!==false){
            if(!$data->id)$data->id=$this->id;
            if(!$this->url){
                $this->url=Url::showUrl($this->id);
                $this->save(false);
            }
            if($data->save()!==false){
                $transaction->commit();
                return true;
            }else{
                $transaction->rollBack();
            }
        }else{
            $transaction->rollBack();
        }
        return false;
    }

    public function behaviors()
    {
        return [

        ];
    }
}
