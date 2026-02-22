<?php

namespace app\models;

use yii\db\ActiveRecord;

class Request extends ActiveRecord
{
    public const string STATUS_PENDING = 'pending';
    public const string STATUS_APPROVED = 'approved';
    public const string STATUS_DECLINED = 'declined';

    public static function tableName(): string
    {
        return '{{%requests}}';
    }

    public function rules(): array
    {
        return [
            [['status', 'amount', 'term', 'user_id'], 'required'],
            [['amount', 'term', 'user_id'], 'integer', 'min' => 0],
            ['status', 'in', 'range' => [
                self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_DECLINED
            ]],
        ];
    }

    public function getUser(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}