<?php

namespace frontend\controllers;
use yii;
use common\models\Post;
use common\helps\tools;
use yii\data\Pagination;
class PostController extends LdBaseController
{   
   // public $layout = 'column1';
    public function actionIndex()
    {   
     $model=Post::find()->where(['is_show'=>1]);
   
        $pagination = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' => $model->count(),
        ]);
        $model = $model->orderby(['is_top'=>SORT_DESC,'create_at'=>SORT_DESC])->asArray()
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index',[
            'model'=>$model,
            'pagination'=>$pagination,
            ]);
    }


    public function actionView($id)
    {
        $data = Post::findOne($id);
        if ($data) {
            $data->read_num+=1;
            $data->update();

            return $this->render('view',['data'=>$data]);
        }else{
            $this->redirect(['index']);
        }
    }


    public function actionGetList()
    {   
        $x = Yii::$app->request->get('x')?Yii::$app->request->get('x'):0;
        $limit = 10;
        $offset = $limit * $x;
        $Post = Post::find()->asArray()->limit($limit)->offset($offset);
        $list = $Post->select(
            [
            'id',
            'title',
            'summary',
            'label_img',
            'created_at',
            'read_num',
            
            ])->all();
        return tools::jsonSuccess('成功',$list);
    }
    public function actionDetails(){
        $id=yii::$app->request->get('id');
        $model=Post::find()->where(['=','id',$id])->andwhere(['is_show'=>1])->asArray()->one();
        //上一页
        $prev= Post::prev($model['id']);
        //下一页
        $Next= Post::Next($model['id']);

       return $this->render('details',[
        'model'=>$model,
        'prev'=>$prev,
        'Next'=>$Next,
        ]);
    }

    public function actionNovice(){
        $model=post::find()->where(['cat_id'=>4])->orderby('updated_at DESC')->one();
        if($model){
            return $this->render('novice',['model'=>$model]);
        }else{
            return $this->render('hint');
        }
    }

    public function actionAbout(){

        $model=post::find()->where(['cat_id'=>5])->orderby('updated_at DESC')->one();
        if($model){
            return $this->render('about',['model'=>$model]);
        }else{
            return $this->render('hint');
        }
    
    }
}
