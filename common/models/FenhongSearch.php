<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Fenhong;

/**
 * FenhongSearch represents the model behind the search form about `common\models\Fenhong`.
 */
class FenhongSearch extends Fenhong
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'amount', 'rdt', 'f_amount', 'qi', 'dft'], 'integer'],
            [['money', 'f_money'], 'number'],
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
        $query = Fenhong::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'uid' => $this->uid,
            'amount' => $this->amount,
            'money' => $this->money,
            'rdt' => $this->rdt,
            'f_amount' => $this->f_amount,
            'f_money' => $this->f_money,
            'qi' => $this->qi,
            'dft' => $this->dft,
        ]);

        return $dataProvider;
    }
}
