<?php

namespace App\UserInterface\Api\Good;

use App\Good\Query\GoodQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/good', name: 'good')]
class GoodController
{
    #[Route('/all', name: '_all')]
    public function index(GoodQuery $goodQuery): JsonResponse
    {
        $goods = $goodQuery->queryAll();

        return new JsonResponse($goods, Response::HTTP_OK, [
            'Access-Control-Allow-Origin' => '*',
        ]);
    }
}