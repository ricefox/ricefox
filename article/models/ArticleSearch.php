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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'type_id', 'sort', 'status', 'user_id', 'created_at', 'updated_at', 'has_code', 'replies'], 'integer'],
            [['title', 'thumbnail', 'keywords', 'description', 'url', 'username', 'source'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = Article::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'type_id' => $this->type_id,
            'sort' => $this->sort,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'has_code' => $this->has_code,
            'replies' => $this->replies,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'source', $this->source]);

        return $dataProvider;
    }
}
