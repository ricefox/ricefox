<?php

namespace ricefox\block\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ricefox\block\models\FooterLink;

/**
 * FooterLinkSearch represents the model behind the search form about `ricefox\block\models\FooterLink`.
 */
class FooterLinkSearch extends FooterLink
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'sort', 'category_cond'], 'integer'],
            [['url', 'name', 'image_url', 'color'], 'safe'],
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
        $query = FooterLink::find();

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
            'sort' => $this->sort,
            'category_cond' => $this->category_cond,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'image_url', $this->image_url])
            ->andFilterWhere(['like', 'color', $this->color]);

        return $dataProvider;
    }
}
