<?php

namespace ricefox\block\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ricefox\block\models\Category;

/**
 * CategroySearch represents the model behind the search form about `ricefox\block\models\Category`.
 */
class CategroySearch extends Category
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'has_child', 'sort', 'status',  'module_id', 'show_type'], 'integer'],
            [['name', 'path', 'url', 'children', 'title_name', 'title', 'keywords', 'description', 'related'], 'safe'],
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
        $query = Category::find();

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
            'parent_id' => $this->parent_id,
            'has_child' => $this->has_child,
            'sort' => $this->sort,
            'status' => $this->status,
            'module_id' => $this->module_id,
            'show_type' => $this->show_type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'children', $this->children])
            ->andFilterWhere(['like', 'title_name', $this->title_name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'related', $this->related]);

        return $dataProvider;
    }
}
