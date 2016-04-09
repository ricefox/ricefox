<?php

namespace ricefox\job\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ricefox\job\models\Hire;

/**
 * HireSearch represents the model behind the search form about `ricefox\job\models\Hire`.
 */
class HireSearch extends Hire
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'number', 'year', 'city', 'area', 'type', 'edu', 'salary', 'created_at', 'updated_at'], 'integer'],
            [['title', 'job', 'welfare', 'address', 'content'], 'safe'],
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
        $query = Hire::find();

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
            'company_id' => $this->company_id,
            'number' => $this->number,
            'year' => $this->year,
            'city' => $this->city,
            'area' => $this->area,
            'type' => $this->type,
            'edu' => $this->edu,
            'salary' => $this->salary,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'job', $this->job])
            ->andFilterWhere(['like', 'welfare', $this->welfare])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
