<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Parameter;

/**
 * ParameterSearch represents the model behind the search form about `common\models\Parameter`.
 */
class ParameterSearch extends Parameter
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'hidden', 'show_type', 'sort_num'], 'integer'],
            [['name', 'val', 'explain'], 'safe'],
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
        $query = Parameter::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                  'pageSize' => 50,
             ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->orderBy(['sort_num'=>SORT_ASC, 'id'=>SORT_ASC]);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'hidden' => $this->hidden,
            'show_type' => $this->show_type,
            'sort_num' => $this->sort_num,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'val', $this->val])
            ->andFilterWhere(['like', 'explain', $this->explain]);

        return $dataProvider;
    }
}
