<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use common\models\Lot;
use common\models\User;

/* @var $this yii\web\View */

$this->title = 'Список всех товаров';
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="body-content">

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
                'value' => function($model) {
                    return $model->actualPrice;
                }
            ],
            [
                'attribute'=>'customer',
                'value' => function($model) {
                    return $model->customer;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'view') {
                    $url = Url::toRoute(['lot/view', 'id' => $key]);
                    return $url;
                }
                }
            ],
        ],
    ]); ?>

    </div>
</div>
