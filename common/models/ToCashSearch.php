<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ToCash;

/**
 * ToCashSearch represents the model behind the search form about `common\models\ToCash`.
 */
class ToCashSearch extends ToCash
{
    /**
     * @inheritdoc
     */
    public $s_time;
    public $e_time;
    public $member_name;
    public function rules()
    {
        return [
            [['id', 'member_id', 'type', 'create_time', 'confirm_time', 'state'], 'integer'],
            [['bankname', 'number', 'username', 'address','s_time','e_time'], 'safe'],
            [['to_money', 'tax', 'real_money'], 'number'],
            ['member_name','string'],
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
        $query = ToCash::find()->orderBy(['id'=>SORT_DESC]);

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
        if($this->member_name){
            $model = Member::find()->where(['username'=>$this->member_name])->one();
            if($model){
                $this->member_id = $model->id;
            }
        }
        if ($this->s_time) {
            if (empty($this->e_time)) {
                $this->e_time = date('Y-m-d H:i',strtotime($this->s_time) + 24 *3600);
            }
            $query->andFilterWhere(['between','create_time',strtotime($this->s_time),strtotime($this->e_time)]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'member_id' => $this->member_id,
            'to_money' => $this->to_money,
            'tax' => $this->tax,
            'real_money' => $this->real_money,
            'type' => $this->type,
            'create_time' => $this->create_time,
            'confirm_time' => $this->confirm_time,
            'state' => $this->state,
        ]);

        $query->andFilterWhere(['like', 'bankname', $this->bankname])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }

	public function searchUser($params, $member_id)
    {
        $query = ToCash::find()->orderBy(['id'=>SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		$query->andWhere(['member_id'=>$member_id]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->s_time) {
            if (empty($this->e_time)) {
                $this->e_time = date('Y-m-d H:i',strtotime($this->s_time) + 24 *3600);
            }
            $query->andFilterWhere(['between','create_time',strtotime($this->s_time),strtotime($this->e_time)]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'to_money' => $this->to_money,
            'tax' => $this->tax,
            'real_money' => $this->real_money,
            'type' => $this->type,
            'create_time' => $this->create_time,
            'confirm_time' => $this->confirm_time,
            'state' => $this->state,
        ]);

        $query->andFilterWhere(['like', 'bankname', $this->bankname])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
