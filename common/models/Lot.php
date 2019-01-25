<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lot".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $seller_id
 * @property double $start_price
 * @property double $step
 * @property string $start_date
 * @property string $end_date
 * @property int $status
 *
 * @property BargainResult $bargainResult
 * @property User $seller
 * @property LotChange[] $lotChanges
 */
class Lot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'seller_id', 'start_price', 'step', 'start_date', 'end_date'], 'required'],
            [['seller_id', 'status'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['name', 'description'], 'string', 'max' => 255],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['seller_id' => 'id']],
            ['step', 'number', 'min' => 5, 'max' => 20],
            ['start_price', 'number', 'min' => 0],
            ['end_date', 'compare', 'compareAttribute'=>'start_date', 'operator'=>'>', 'message'=>'"{attribute}" должна быть больше "{compareValue}".'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование товара',
            'description' => 'Описание товара',
            'seller_id' => 'Продавец',
            'start_price' => 'Начальная цена',
            'step' => 'Шаг в %',
            'start_date' => 'Дата начала торгов',
            'end_date' => 'Дата окончания торгов',
            'status' => 'Статус',
            'actualStatus' => 'Статус',
            'customer' => 'Покупатель',
            'actualPrice' => 'Текущая цена'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBargainResult()
    {
        return $this->hasOne(BargainResult::className(), ['lot_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(User::className(), ['id' => 'seller_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotChanges()
    {
        return $this->hasMany(LotChange::className(), ['lot_id' => 'id']);
    }

    public static function getLotStatus()
    {
        return [
            1 => 'В процессе торгов',
            2 => 'Завершен покупкой',
            3 => 'Закрыт продавцом',
            4 => 'Закрыт администратором',
            5 => 'Завершен без покупки',
            6 => 'Еще не начался'
        ];
    }

    public function getActualStatus()
    {
        if ($this->status != 1) {
            return $this->status;
        } else if ($this->status == 1 && count($this->bargainResult) == 0 && $this->end_date < date('Y-m-d')) {
            return 5;
        } else if ($this->status == 1 && $this->start_date > date('Y-m-d')) {
            return 6;
        } else if ($this->status == 1 && $this->start_date <= date('Y-m-d') && $this->end_date >= date('Y-m-d')) {
            return 1;
        }

        return null;
    }

    public function getActualPrice()
    {
        if ($this->lotChanges) {
            $lastChanges = LotChange::find()->where(['lot_id' => $this->id])->orderBy('timestamp DESC')->one();
            return $lastChanges->price;
        } else {
            return $this->start_price;
        }
    }

    public function getCustomer()
    {
        if ($this->bargainResult) {
            return User::findIdentity($this->bargainResult->customer_id)->username;
        } else {
            return null;
        }
    }

    public function setPriceLow()
    {
        $newPrice = $this->actualPrice - $this->start_price * $this->step / 100;

        if ($newPrice <= 0) {
            return false;
        }

        $model = new LotChange();
        $model->lot_id = $this->id;
        $model->price = $newPrice;
        $model->user_id = Yii::$app->user->id;

        return $model->save();
    }

    public function buy()
    {
        $result = $this->bargainResult;

        if (!$result) {
            $model = self::findOne($this->id);
            $model->status = 2;
            $model->save();

            $result = new BargainResult();
            $result->lot_id = $this->id;
            $result->price = $this->actualPrice;
            $result->customer_id = Yii::$app->user->id;

            return $result->save();
        } else {
            return false;
        }
    }

}
