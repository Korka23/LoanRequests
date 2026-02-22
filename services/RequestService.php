<?php

namespace app\services;

use app\models\Request;
use app\models\User;
use DomainException;
use Yii;
use yii\db\Transaction;
use yii\db\Exception;

class RequestService
{
    /**
     * Создать заявку на займ
     *
     * @param int $userId
     * @param int $amount
     * @param int $term
     * @return Request
     * @throws Exception
     */
    public function create(int $userId, int $amount, int $term): Request
    {
        $user = User::findOne($userId);
        if (!$user) {
            throw new DomainException('user not found');
        }

        $existsApproved = Request::find()
            ->where([
                'user_id' => $userId,
                'status' => Request::STATUS_APPROVED,
            ])
            ->exists();

        if ($existsApproved) {
            throw new DomainException('already has approved');
        }

        $request = new Request([
            'user_id' => $userId,
            'amount' => $amount,
            'term' => $term,
            'status' => Request::STATUS_PENDING,
        ]);

        if (!$request->save()) {
            throw new \RuntimeException('save failed');
        }

        return $request;
    }

    /**
     * Обработать все заявки с вероятностью 10% одобрения
     *
     * @param int $delay
     * @throws Exception
     */
    public function process(int $delay): void
    {
        while (true) {

            $transaction = Yii::$app->db->beginTransaction();

            try {

                $request = Request::find()
                    ->where(['status' => Request::STATUS_PENDING])
                    ->orderBy(['id' => SORT_ASC])
                    ->limit(1)
                    ->one();

                if (!$request) {
                    $transaction->rollBack();
                    break;
                }

                Yii::$app->db->createCommand(
                    'SELECT id FROM requests WHERE id = :id FOR UPDATE',
                    [':id' => $request->id]
                )->query();

                sleep($delay);

                $approved = random_int(1, 100) <= 10;

                if ($approved) {

                    $existsApproved = Request::find()
                        ->where([
                            'user_id' => $request->user_id,
                            'status' => Request::STATUS_APPROVED,
                        ])
                        ->exists();

                    $request->status = $existsApproved
                        ? Request::STATUS_DECLINED
                        : Request::STATUS_APPROVED;

                } else {
                    $request->status = Request::STATUS_DECLINED;
                }


                $request->save(false);

                $transaction->commit();

            } catch (\Throwable $e) {

                $transaction->rollBack();
                Yii::error([
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ], __METHOD__);
                throw $e;
            }
        }
    }
}
