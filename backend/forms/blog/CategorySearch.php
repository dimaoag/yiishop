<?php

namespace backend\forms\blog;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\blog\Category;

class CategorySearch extends Model
{
    public $id;
    public $name;
    public $slug;
    public $title;

    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['name', 'slug', 'title'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Category::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['sort' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
