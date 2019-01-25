<?php

namespace backend\controllers;

use Yii;
use common\models\Lot;
use common\models\LotSeach;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * LotController implements the CRUD actions for Lot model.
 */
class LotController extends Controller
{

    /**
     * Lists all Lot models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LotSeach();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lot model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Lot model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->seller_id == Yii::$app->user->id || $model->getActualStatus() != 6) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Lot model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lot the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lot::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionClose($id)
    {
        $model = $this->findModel($id);

        if (in_array($model->getActualStatus(), [1,6])) {
            $model->status = 4;
            $model->save();
            Yii::$app->session->setFlash('success', 'Статус изменен успешно');
        } else {
            Yii::$app->session->setFlash('error', 'Возникла ошибка при изменении статуса');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionLow($id)
    {
        $model = $this->findModel($id);

        if ($model->getActualStatus() == 1 && $model->setPriceLow()) {
            Yii::$app->session->setFlash('success', 'Цена изменена успешно');
        } else {
            Yii::$app->session->setFlash('error', 'Возникла ошибка при изменении цены');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }
}
