<?php

namespace App\Controller\API\V1;

use App\Controller\API\V1\Response\Status;
use App\Controller\API\V1\Response\V1ResponseInterface;
use App\Exception\RecordNotFoundException;
use App\Form\UserDeleteType;
use App\Model\User\UserModelInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\UserBundle\Form\Factory\FactoryInterface as UserFormFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class UserController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @var UserFormFactoryInterface
     */
    private $userFormFactory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var TranslatorInterface
     */

    private $translator;

    /**
     * @var UserModelInterface
     */
    private $userModel;

    /**
     * @var V1ResponseInterface
     */
    private $response;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(
        UserFormFactoryInterface $userFormFactory,
        EventDispatcherInterface $eventDispatcher,
        TranslatorInterface $translator,
        UserModelInterface $userModel,
        V1ResponseInterface $response,
        FormFactoryInterface $formFactory
    ) {
        $this->userFormFactory = $userFormFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->userModel = $userModel;
        $this->response = $response;
        $this->formFactory = $formFactory;
    }

    public function postAction(Request $request)
    {
//        $user = $this->get('security.token_storage')->getToken()->getUser()->getName();
//        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
//            return $this->response->createForbiddenResponse();
//        }

        $user = $this->userModel->create();
        $user->setEnabled(true);
        $user->setEmail($request->request->get('email'));
        $user->setFullname($request->request->get('name'));
        $user->setUsername($request->request->get('username'));
        $user->setPlainPassword($request->request->get('plainPassword'));

        $form = $this->userFormFactory->createForm();

        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userModel->persist($user);
            return $this->response->create(Status::STATUS_SUCCESS, null, Status::HTTP_CREATED);
        }

        return $this->response->create(Status::STATUS_VALIDATION_FAILED, $form, Status::HTTP_BAD_REQUEST);
    }

    public function deleteAction(Request $request, $username)
    {
        $input = [
            'username' => $username
        ];

        $form = $this->formFactory->create(UserDeleteType::class);

        $form->submit($input);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->userModel->deleteByUsername($username);
                return $this->response->create(Status::STATUS_SUCCESS, null, Status::HTTP_ACCEPTED);

            } catch (RecordNotFoundException $e) {
                $form->get('username')->addError(
                    new FormError($this->translator->trans('user.validators.username.not_exists', [], 'user'))
                );
            }
        }

        return $this->response->create(Status::STATUS_VALIDATION_FAILED, $form);
    }
}
