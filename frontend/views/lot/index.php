<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use common\models\Lot;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LotSeach */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lot-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= \Yii::$app->user->can('createLot') ? Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute'=>'start_date',
                'filter'=> DateRangePicker::widget([
                                 'model'=>$searchModel,
                                 'attribute'=>'start_date',
                                 'convertFormat'=>true,
                                 'pluginOptions'=>[
                                          'locale'=>[
                                              'format'=>'Y-m-d'
                                          ],
                                      ]
                                  ]),

            ],
            [
                'attribute'=>'end_date',
                'filter'=> DateRangePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'end_date',
                    'convertFormat'=>true,
                    'pluginOptions'=>[
                        'locale'=>[
                            'format'=>'Y-m-d'
                        ],
                    ]
                ]),
            ],
            [
                'attribute'=>'status',
                'filter'=>Lot::getLotStatus(),
                'value' => function($model) {
                    return Lot::getLotStatus()[$model->actualStatus];
                }
            ],
            [
                'attribute'=>'actualPrice',
                'filter'=>Lot::getLotStatus(),
                'value' => function($model) {
                    return $model->getActualPrice();
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => 'Просмотр',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return $model->actualStatus == 6 ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => 'Редактировать',
                        ]) : '';
                    },
                 ],
            ],
        ],
    ]); ?>
</div>
