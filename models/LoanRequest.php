<?php

namespace app\models;

use yii\db\ActiveRecord;

class LoanRequest extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%loan_requests}}';
    }

    public function rules(): array
    {
        return [
            [['status', 'amount', 'term', 'user_id'], 'required'],
            [['amount', 'term', 'user_id'], 'integer', 'min' => 0],
            [['status'], 'string', 'max' => 255],
        ];
    }

    public function getUser(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}