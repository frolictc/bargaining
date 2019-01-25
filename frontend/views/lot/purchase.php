<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use common\models\Lot;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LotSeach */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои покупки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lot-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= \Yii::$app->user->can('createLot') ? Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description',
            [
                'label' => 'Продавец',
                'value' => function($model) {
                    return User::findIdentity($model->seller_id)->username;
                }
            ],
            [
                'attribute'=>'actualPrice',
                'value' => function($model) {
                    return $model->getActualPrice();
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
            ],
        ],
    ]); ?>
</div>
