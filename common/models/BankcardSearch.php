<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Bankcard;

/**
 * BankcardSearch represents the model behind the search form about `common\models\Bankcard`.
 */
class BankcardSearch extends Bankcard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id'], 'integer'],
            [['bankname', 'number', 'province', 'city', 'address', 'username'], 'safe'],
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
        $query = Bankcard::find()->where(['member_id'=>Yii::$app->user->identity->id]);

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
            'member_id' => $this->member_id,
        ]);

        $query->andFilterWhere(['like', 'bankname', $this->bankname])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'username', $this->username]);

        return $dataProvider;
    }
}
