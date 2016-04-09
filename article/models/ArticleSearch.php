<?php

namespace ricefox\article\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ricefox\article\models\Article;

/**
 * ArticleSearch represents the model behind the search form about `ricefox\article\models\Article`.
 */
class ArticleSearch extends Article
{
    public $createFrom;
    public $createTo;
    public $createFromString;//保存提交的数据，用于返回后显示。
    public $createToString;//
    public $idFrom;
    public $idTo;
    public $title;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idFrom','idTo','category_id'],'integer'],
            [['title'],'string'],
            [['createFrom','createTo'],'safe']
        ];
    }

    public function beforeValidate()
    {
        $this->createFromString=$this->createFrom;
        $this->createToString=$this->createTo;
        $this->createFrom=strtotime($this->createFrom);
        $this->createTo=strtotime($this->createTo);
        if($this->createFrom===false)$this->createFrom=null;
        if($this->createTo===false)$this->createTo=null;
        return parent::beforeValidate();
    }

    public function attributeLabels()
    {
        $labels=parent::attributeLabels();
        $labels2=[
            'createFrom'=>'起始时间',
            'createTo'=>'结束时间',
            'idFrom'=>'起始ID',
            'idTo'=>'结束ID',
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
        $query = Article::find();
        $query->joinWith(['category']);
        $provider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $provider->setSort([
            'defaultOrder'=>['created_at'=>SORT_DESC,],
            'attributes'=>['created_at','sort',
                'id'=>[
                    'default'=>SORT_DESC
                ]
            ],
            'enableMultiSort'=>true
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $provider;
        }
        // 根据所属菜单搜索
        if($this->category_id>0){
            $query->andFilterWhere(['article.category_id'=>$this->category_id]);
        }
        // 根据文章标题搜索
        $query->andFilterWhere(['like', 'article.title', $this->title]);
        //var_dump($this->createFrom);
        //var_dump($this->createTo);
        // 根据文章发布时间范围搜索
        $query->andFilterWhere(['>=', 'article.created_at', $this->createFrom])
            ->andFilterWhere(['<=', 'article.created_at', $this->createTo]);

        // 根据文章ID范围搜索
        $query->andFilterWhere(['>=', 'article.id', $this->idFrom])
            ->andFilterWhere(['<=', 'article.id', $this->idTo]);
        return $provider;
    }
}
