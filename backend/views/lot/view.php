<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Lot;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Lot */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="lot-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $model->getActualStatus() == 6 ? Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-success']) : '' ?>
        <?= $model->getActualStatus() == 1 ? Html::a('Уменьшить цену', ['low', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
        <?= in_array($model->getActualStatus(), [1,6]) ? Html::a('Завершить', ['close', 'id' => $model->id], ['class' => 'btn btn-danger']) : '' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'description',
            'start_price',
            'actualPrice',
            'step',
            'start_date',
            'end_date',
            [
                'attribute'=>'status',
                'value'=> function($data) {return Lot::getLotStatus()[$data->getActualStatus()]; }
            ],
        ],
    ]) ?>

    <?php if ($model->lotChanges): ?>
    <h4>История изменения цены</h4>
    <?php foreach ($model->lotChanges as $change): ?>
    	<p><?= $change->timestamp ?>: <?= $change->price ?></p>
    <?php endforeach;?>
    <?php endif; ?>

	<?php if ($model->bargainResult): ?>
	<h4>Покупатель</h4>
    <p><?= $model->bargainResult->timestamp?>: <?= User::findIdentity($model->bargainResult->customer_id)->username ?></p>
	<?php endif; ?>

</div>
