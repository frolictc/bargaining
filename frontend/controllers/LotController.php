<?php

namespace frontend\controllers;

use Yii;
use common\models\Lot;
use common\models\LotSeach;
use common\models\BargainResult;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * LotController implements the CRUD actions for Lot model.
 */
class LotController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'close',
                            'low'
                        ],
                        'roles' => [
                            'seller'
                        ]
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'view',
                        ],
                        'roles' => [
                            'seller',
                            'customer'
                        ]
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'buy',
                            'purchase'
                        ],
                        'roles' => [
                            'customer'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Lot models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LotSeach(['seller_id' => Yii::$app->user->id]);
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
     * Creates a new Lot model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lot();
        $model->start_date = date("Y-m-d");

        if ($model->load(Yii::$app->request->post())) {
            $model->seller_id = Yii::$app->user->id;
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
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
            $model->status = 3;
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

    public function actionBuy($id)
    {
        $model = $this->findModel($id);

        if ($model->getActualStatus() == 1 && $model->buy()) {
            Yii::$app->session->setFlash('success', 'Покупка совершена успешно');
        } else {
            Yii::$app->session->setFlash('error', 'Возникла ошибка при покупке товара');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionPurchase()
    {
        $user_id = Yii::$app->user->id;
        $lots = BargainResult::find()->select('lot_id')->where(['customer_id' => $user_id])->column();

        $searchModel = new LotSeach(['id' => $lots]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = false;

        return $this->render('purchase', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
