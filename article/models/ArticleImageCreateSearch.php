<?php

namespace ricefox\article\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ricefox\article\models\Article;

/**
 * ArticleSearch represents the model behind the search form about `ricefox\article\models\Article`.
 */
class ArticleImageCreateSearch extends ArticleSearch
{

    public $keyName;
    public $position;
    public $positionString='';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $parent=parent::rules();
        $rule=[
            [['keyName'],'string'],
            ['position','safe']
        ];
        return array_merge($rule,$parent);
    }

    public function beforeValidate()
    {
        if(is_array($this->position) && !empty($this->position)){
            $this->positionString=implode(',',$this->position);
        }
        return parent::beforeValidate();
    }

    public function attributeLabels()
    {
        $labels=parent::attributeLabels();
        $labels2=[
            'position'=>'位置',
            'keyName'=>'标识'
        ];
        return array_merge($labels,$labels2);
    }
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $provider=parent::search($params);
        if($this->keyName){
            $query2=ArticleImage::find()->select('article_id')->where(['key_name'=>$this->keyName])
                ->andFilterWhere(['in','position',$this->position]);
            $provider->query->andFilterWhere(['not in','article.id',$query2]);
        }
        $provider->query->andWhere('article.status=1');
        return $provider;
    }
}
