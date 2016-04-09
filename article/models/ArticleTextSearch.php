<?php

namespace ricefox\article\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ricefox\article\models\ArticleText;

/**
 * ArticleTextSearch represents the model behind the search form about `ricefox\article\models\ArticleText`.
 */
class ArticleTextSearch extends ArticleText
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'article_id', 'category_id', 'expire'], 'integer'],
            [['url', 'title', 'description', 'key_name', 'position'], 'safe'],
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
        $query = ArticleText::find();

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
            'article_id' => $this->article_id,
            'category_id' => $this->category_id,
            'expire' => $this->expire,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'key_name', $this->key_name])
            ->andFilterWhere(['like', 'position', $this->position]);

        return $dataProvider;
    }
}
