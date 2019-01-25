<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lot_change".
 *
 * @property int $id
 * @property int $lot_id
 * @property string $timestamp
 * @property double $price
 * @property int $user_id
 *
 * @property Lot $lot
 * @property User $user
 */
class LotChange extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lot_change';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lot_id', 'price', 'user_id'], 'required'],
            [['lot_id', 'user_id'], 'integer'],
            [['timestamp'], 'safe'],
            [['price'], 'number'],
            [['lot_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lot::className(), 'targetAttribute' => ['lot_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLot()
    {
        return $this->hasOne(Lot::className(), ['id' => 'lot_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
