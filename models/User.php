<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%users}}';
    }

    public function getLoanRequests(): \yii\db\ActiveQuery
    {
        return $this->hasMany(LoanRequest::class, ['user_id' => 'id']);
    }
}