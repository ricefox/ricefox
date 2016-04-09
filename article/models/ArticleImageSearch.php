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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id'], 'integer'],
            [['url', 'image_url', 'title', 'key_name', 'position'], 'safe'],
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
        $query = ArticleImage::find();

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
        ]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'image_url', $this->image_url])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'key_name', $this->key_name])
            ->andFilterWhere(['like', 'position', $this->position]);

        return $dataProvider;
    }
}
