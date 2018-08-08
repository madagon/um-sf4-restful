<?php

namespace App\Controller\API\V1\Response;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class V1Response implements V1ResponseInterface
{
    /**
     * @var ViewHandlerInterface
     */
    private $viewHandler;

    public function __construct(ViewHandlerInterface $viewHandler)
    {
        $this->viewHandler = $viewHandler;
    }

    public function create(string $status = Status::STATUS_SUCCESS, $data = null, int $httpCode = 200): Response
    {
        if ($data instanceof FormInterface) {
            $data = $this->getErrorMessages($data);
        }

        return $this->viewHandler->handle(View::create([
            'status' => $status,
            'data' => $data
        ], $httpCode));
    }

    private function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}