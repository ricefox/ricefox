<?php

namespace ricefox\setting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ricefox\setting\models\SettingGroup;

/**
 * SettingGroupSearch represents the model behind the search form about `ricefox\setting\models\SettingGroup`.
 */
class SettingGroupSearch extends SettingGroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'preload', 'sort'], 'integer'],
            [['key_name', 'name'], 'safe'],
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
        $query = SettingGroup::find();

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
            'preload' => $this->preload,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'key_name', $this->key_name])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
