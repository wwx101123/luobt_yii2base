<?php

namespace frontend\controllers;

use Yii;
use common\models\Product;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

use common\models\Sort;
/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends LdBaseController
{
    /**
     * Lists all Product models.
     * @return mixed
     */
    protected $except = ['*'];

    public function actionIndex()
    {
        $query = Product::find()->where(['is_show'=>Product::IS_SHOW,'is_login'=>0])->orderBy(['create_time'=>SORT_DESC,'is_hot'=>SORT_DESC]);
        $sorts = Sort::find()->where(['parent_id'=>0])->asArray()->all();
        
        $pages = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' => $query->count(),
        ]);
        $product_list = $query->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index',[
            'product_list'=>$product_list,
            'pages' => $pages,
            'sorts' => $sorts,
        ]);
    
    }

    public function actionCate($id)
    {
        $query = Product::find();
        $product = $query->where(['is_show' => Product::IS_SHOW,'is_login'=>0])->andWhere(['big_id' => $id])->orderBy(['big_id' => 'desc'])->asArray()->all();
        //var_dump($product);exit;

        $sorts = Sort::find()->where(['parent_id'=>0])->asArray()->all();

        $pages = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' => $query->count(),
        ]);

        return $this->render('cate', [
            'product' => $product,
            'pages' => $pages,
            'sorts' => $sorts,
            'id' => $id,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView()
    {
        $id=yii::$app->request->get('id');
        $model=Product::find()->where(['id'=>$id])->asarray()->one();
        if(empty($model)){
            return $this->redirect(['index']);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
