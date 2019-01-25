<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bargain_result".
 *
 * @property int $id
 * @property int $lot_id
 * @property int $customer_id
 * @property string $timestamp
 * @property double $price
 *
 * @property User $customer
 * @property Lot $lot
 */
class BargainResult extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bargain_result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lot_id', 'customer_id', 'price'], 'required'],
            [['lot_id', 'customer_id'], 'integer'],
            [['timestamp'], 'safe'],
            [['price'], 'number'],
            [['lot_id'], 'unique'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['lot_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lot::className(), 'targetAttribute' => ['lot_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lot_id' => 'Лот',
            'customer_id' => 'Покупатель',
            'timestamp' => 'Время покупки',
            'price' => 'Цена покупки',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLot()
    {
        return $this->hasOne(Lot::className(), ['id' => 'lot_id']);
    }
}
