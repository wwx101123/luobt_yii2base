<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * ProductSearch represents the model behind the search form about `common\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cate_id', 'brand_id', 'inventory', 'is_show', 'is_top', 'is_hot', 'is_new', 'is_reg', 'create_time'], 'integer'],
            [['goods_name', 'goods_code', 'goods_unit', 'goods_weight', 'goods_img', 'content'], 'safe'],
            [['market_price', 'present_price'], 'number'],
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
        $query = Product::find();

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
            'cate_id' => $this->cate_id,
            'brand_id' => $this->brand_id,
            'inventory' => $this->inventory,
            'market_price' => $this->market_price,
            'present_price' => $this->present_price,
            'is_show' => $this->is_show,
            'is_top' => $this->is_top,
            'is_hot' => $this->is_hot,
            'is_new' => $this->is_new,
            'is_reg' => $this->is_reg,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'goods_name', $this->goods_name])
            ->andFilterWhere(['like', 'goods_code', $this->goods_code])
            ->andFilterWhere(['like', 'goods_unit', $this->goods_unit])
            ->andFilterWhere(['like', 'goods_weight', $this->goods_weight])
            ->andFilterWhere(['like', 'goods_img', $this->goods_img])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
