<?php

namespace ricefox\article\models;

use Yii;

/**
 * This is the model class for table "article_data".
 *
 * @property integer $id
 * @property string $content
 * @property integer $pagination
 * @property integer $pagination_length
 * @property integer $allow_reply
 */
class ArticleData extends \ricefox\base\ActiveRecord
{
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
            [['id', 'content'], 'required'],
            [['id', 'pagination', 'pagination_length', 'allow_reply'], 'integer'],
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
            'pagination_length' => Yii::t('rf_article', 'Pagination Length'),
            'allow_reply' => Yii::t('rf_article', 'Allow Reply'),
        ];
    }
}
