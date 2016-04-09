<?php

namespace ricefox\article\models;

use Yii;
use ricefox\components\PageContent;
/**
 * This is the model class for table "article_data".
 *
 * @property integer $id
 * @property string $content
 * @property integer $paging
 * @property integer $allow_reply
 */
class ArticleData extends \ricefox\base\ActiveRecord
{
    public $pagination=1;//设置为自动分页
    public $paginationLength=8000;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],//id不能填，需插入文章表后再赋值。
            [['id', 'paging', 'allow_reply'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rf_article', 'ID'),
            'content' => Yii::t('rf_article', 'Content'),
            'pagination' => Yii::t('rf_article', 'Pagination'),
            'paginationLength' => Yii::t('rf_article', 'Pagination Length'),
            'allow_reply' => Yii::t('rf_article', 'Allow Reply'),
        ];
    }

    public function beforeSave($insert)
    {
        if($this->pagination==1){
            $length=$this->paginationLength;
            if(mb_strlen($this->content,\Yii::$app->charset)>$length){
                $page=new PageContent();
                $this->content=$page->getData($this->content,$length);
            }
        }
        if(strpos($this->content,'[page]')!==false){
            $this->paging=1;
        }else{
            $this->paging=0;
        }
        return parent::beforeSave($insert);
    }

    public function getContent()
    {
        if($this->paging==1){
            return implode('',explode('[page]',$this->content));
        }else{
            return $this->content;
        }
    }
}
