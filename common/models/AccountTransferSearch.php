<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AccountTransfer;

/**
 * AccountTransferSearch represents the model behind the search form about `common\models\AccountTransfer`.
 */
class AccountTransferSearch extends AccountTransfer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'out_id', 'out_name', 'into_id', 'into_name', 'type', 'create_time'], 'integer'],
            [['out_money', 'into_money'], 'number'],
            [['info'], 'safe'],
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
        $query = AccountTransfer::find()->orderBy(['id'=>SORT_DESC]);

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
        $query->orFilterWhere(['=', 'out_id', $this->out_id])->orFilterWhere(['=', 'into_id', $this->into_id]);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'out_name' => $this->out_name,
            'into_name' => $this->into_name,
            'out_money' => $this->out_money,
            'into_money' => $this->into_money,
            'type' => $this->type,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'info', $this->info]);

        return $dataProvider;
    }
}
