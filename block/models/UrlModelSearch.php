<?php

namespace ricefox\block\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ricefox\block\models\UrlModel;

/**
 * UrlModelSearch represents the model behind the search form about `ricefox\block\models\UrlModel`.
 */
class UrlModelSearch extends UrlModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'target_id', 'uri_match', 'suffix_slashes', 'sort', 'status'], 'integer'],
            [['url', 'protocol', 'domain', 'uri', 'route'], 'safe'],
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
        $query = UrlModel::find();

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
            'target_id' => $this->target_id,
            'uri_match' => $this->uri_match,
            'suffix_slashes' => $this->suffix_slashes,
            'sort' => $this->sort,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'protocol', $this->protocol])
            ->andFilterWhere(['like', 'domain', $this->domain])
            ->andFilterWhere(['like', 'uri', $this->uri])
            ->andFilterWhere(['like', 'route', $this->route]);

        return $dataProvider;
    }
}
