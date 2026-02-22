<?php

namespace app\dto;

use yii\base\Model;

class CreateRequestDto extends Model
{
    public int $user_id;
    public int $amount;
    public int $term;

    public function rules(): array
    {
        return [
            [['user_id', 'amount', 'term'], 'required'],
            [['user_id', 'amount', 'term'], 'integer'],
            ['amount', 'integer', 'min' => 1],
            ['term', 'integer', 'min' => 1],
        ];
    }
}