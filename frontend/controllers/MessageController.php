<?php

namespace frontend\controllers;

use Yii;
use common\models\Message;
use common\models\MessageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\WriteMessageForm;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends LdBaseController
{
    /**
     * Lists all Message models.
     * @return mixed
     */
    public function actionInbox()
    {
        $searchModel = new MessageSearch();
        $searchModel->tuid = $this->user->id;
        $queryParams = Yii::$app->request->queryParams;
        // $queryParams['MessageSearch']['tuid'] = Yii::$app->user->getId();
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('inbox', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionInboxview()
    {
        $id = Yii::$app->request->get('id');
        $model = Message::find()->where('id=:id AND tuid=:tuid', [':id'=>$id, ':tuid' => Yii::$app->user->getId()])->one();
        if (!$model) {
            Yii::$app->session->setFlash('error', '您要查看的邮件不存');
            return $this->redirect(['message/inbox']);
        }
        else {
            $model->t_read = 1;
            $model->save();
        }
        return $this->render('outboxview', ['model' => $model]);
    }

    public function actionOutbox()
    {
        $searchModel = new MessageSearch();
        $queryParams = Yii::$app->request->queryParams;
        $queryParams['MessageSearch']['fuid'] = Yii::$app->user->getId();
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('outbox', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOutboxview()
    {
        $id = Yii::$app->request->get('id');
        $model = Message::find()->where('id=:id AND fuid=:fuid', [':id'=>$id, ':fuid' => Yii::$app->user->getId()])->one();
        if (!$model) {
            Yii::$app->session->setFlash('error', '您要查看的邮件不存');
            return $this->redirect(['message/outbox']);
        }
        else {
            $model->f_read = 1;
            $model->save();
        }
        return $this->render('outboxview', ['model' => $model]);
    }

    public function actionWrite()
    {
        $model = new WriteMessageForm();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model->load($post);
            $model->fuid = Yii::$app->user->getId();
            $model->fusername = Yii::$app->user->identity->username;
            if ( $model->write() )
            {
                Yii::$app->session->setFlash('success', '发送成功');
                return $this->refresh();
            }
        }
        return $this->render('write', ['model'=>$model]);
    }



    /**
     * Displays a single Message model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Message();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Message model.
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
     * Deletes an existing Message model.
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
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
