<?php

namespace App\Controller\API\V1\Response;

use Symfony\Component\HttpFoundation\Response;

interface V1ResponseInterface
{
    public function create(string $status = Status::STATUS_SUCCESS, $data = null, int $httpCode = 200): Response;
}