<?php

namespace app\controllers;

use app\dto\CreateRequestDto;
use DomainException;
use yii\rest\Controller;
use Yii;
use app\services\RequestService;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class RequestController extends Controller
{
    private RequestService $service;

    public function __construct($id, $module, RequestService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function verbs(): array
    {
        return [
            'create' => ['POST'],
            'processor' => ['GET']
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        unset($behaviors['rateLimiter']);

        return $behaviors;
    }


    public function actionCreate(): array
    {
        $dto = new CreateRequestDto();
        $dto->load(Yii::$app->request->bodyParams, '');

        if (!$dto->validate()) {
            Yii::$app->response->statusCode = 400;

            return [
                'result' => false,
            ];
        }

        try {
            $request = $this->service->create(
                $dto->user_id,
                $dto->amount,
                $dto->term
            );

            Yii::$app->response->statusCode = 201;

            return [
                'result' => true,
                'id' => $request->id,
            ];

        } catch (DomainException $e) {

            Yii::$app->response->statusCode = 400;

            return [
                'result' => false,
            ];
        }
    }

    public function actionProcessor(int $delay = 5): array
    {
        try {
            $this->service->process($delay);

            return [
                'result' => true
            ];

        } catch (\Throwable $e) {

            Yii::error($e->getMessage(), __METHOD__);

            return [
                'result' => false
            ];
        }
    }

}