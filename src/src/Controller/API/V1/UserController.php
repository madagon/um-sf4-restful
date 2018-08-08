<?php

namespace App\Controller\API\V1;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends FOSRestController implements ClassResourceInterface
{
    public function postAction(Request $request)
    {
        return new Response("Hello");
    }
}
