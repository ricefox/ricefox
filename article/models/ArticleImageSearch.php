<?php

namespace ricefox\article\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ricefox\article\models\ArticleImage;

/**
 * ArticleImageSearch represents the model behind the search form about `ricefox\article\models\ArticleImage`.
 */
class ArticleImageSearch extends ArticleImage
{
    public $idFrom;
    public $idTo;
    public $expireFrom;
    public $expireTo;
    public $expireFromString;//保存提交的数据，用于返回后显示。
    public $expireToString;//

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id','category_cond','expireFrom','expireTo','idFrom','idTo'], 'integer'],

            [['url', 'image_url', 'title', 'key_name', 'position','expireFromString','expireToString'], 'string'],
        ];
    }
    public function beforeValidate()
    {
        $this->expireFromString=$this->expireFrom;
        $this->expireToString=$this->expireTo;
        $this->expireFrom=strtotime($this->expireFrom);
        $this->expireTo=strtotime($this->expireTo);
        if($this->expireFrom===false)$this->expireFrom=null;
        if($this->expireTo===false)$this->expireTo=null;
        return parent::beforeValidate();
    }
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    public function attributeLabels()
    {
        $labels=parent::attributeLabels();
        $labels2=[
            'expireFrom'=>'从',
            'expireTo'=>'到',
            'idFrom'=>'从',
            'idTo'=>'到',
        ];
        return array_merge($labels,$labels2);
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ArticleImage::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder'=>['id'=>SORT_DESC,],
            'attributes'=>[
                'id'=>[
                    'default'=>SORT_DESC
                ],
                'sort'=>[
                    'default'=>SORT_DESC
                ],
                'expire'=>[
                    'default'=>SORT_DESC
                ],
            ],
            'enableMultiSort'=>true
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // 根据所属菜单搜索
        $query->andFilterWhere(['category_id'=>$this->category_id])
            ->andFilterWhere(['key_name'=>$this->key_name])
            ->andFilterWhere(['position'=>$this->position])
            ->andFilterWhere(['category_cond'=>$this->category_cond]);

        // 根据有效期时间范围搜索
        $query->andFilterWhere(['>=', 'expire', $this->expireFrom])
            ->andFilterWhere(['<=', 'expire', $this->expireTo]);

        // 根据文章ID范围搜索
        $query->andFilterWhere(['>=', 'id', $this->idFrom])
            ->andFilterWhere(['<=', 'id', $this->idTo]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'image_url', $this->image_url])
            ->andFilterWhere(['like', 'title', $this->title]);
            //->andFilterWhere(['like', 'key_name', $this->key_name])
            //->andFilterWhere(['like', 'position', $this->position]);

        return $dataProvider;
    }
}
