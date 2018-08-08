<?php

namespace App\Controller\API\V1;

use App\Controller\API\V1\Response\Status;
use App\Controller\API\V1\Response\V1ResponseInterface;
use App\Exception\DuplicateRecordException;
use App\Exception\NotEmptyException;
use App\Exception\RecordNotFoundException;
use App\Form\UserGroupDeleteType;
use App\Model\UserGroup\UserGroupModelInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\UserBundle\Form\Factory\FactoryInterface as UserFormFactoryInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class UsergroupController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @var UserFormFactoryInterface
     */
    private $userFormFactory;

    /**
     * @var UserGroupModelInterface
     */
    private $userGroupModel;

    /**
     * @var V1ResponseInterface
     */
    private $response;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(
        UserFormFactoryInterface $userFormFactory,
        UserGroupModelInterface $userGroupModel,
        V1ResponseInterface $response,
        TranslatorInterface $translator,
        FormFactoryInterface $formFactory
    ) {
        $this->userFormFactory = $userFormFactory;
        $this->userGroupModel = $userGroupModel;
        $this->response = $response;
        $this->translator = $translator;
        $this->formFactory = $formFactory;
    }

    public function postAction(Request $request)
    {
        $userGroup = $this->userGroupModel->create();
        $userGroup->setName($request->request->get('name'));

        $form = $this->userFormFactory->createForm();

        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->userGroupModel->persist($userGroup);
                return $this->response->create(Status::STATUS_SUCCESS, null, Status::HTTP_CREATED);

            } catch (DuplicateRecordException $e) {
                $form->get('name')->addError(
                    new FormError($this->translator->trans('usergroup.validators.name.duplicate', [], 'usergroup'))
                );
            }
        }

        return $this->response->create(Status::STATUS_VALIDATION_FAILED, $form, Status::HTTP_BAD_REQUEST);
    }

    public function deleteAction(Request $request, $name)
    {
        $input = [
            'name' => $name
        ];

        $form = $this->formFactory->create(UserGroupDeleteType::class);

        $form->submit($input);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->userGroupModel->deleteByName($name);
                return $this->response->create(Status::STATUS_SUCCESS, null, Status::HTTP_ACCEPTED);

            } catch (RecordNotFoundException $e) {
                $form->get('name')->addError(
                    new FormError($this->translator->trans('usergroup.validators.name.not_exists', [], 'usergroup'))
                );
            } catch (NotEmptyException $e) {
                $form->addError(
                    new FormError($this->translator->trans('usergroup.validators.not_empty', [], 'usergroup'))
                );
            }
        }

        return $this->response->create(Status::STATUS_VALIDATION_FAILED, $form);
    }

    public function postUserAction($groupName, $username)
    {
        try {
            $this->userGroupModel->addUser($groupName, $username);
            return $this->response->create(Status::STATUS_SUCCESS, null, Status::HTTP_CREATED);

        } catch (RecordNotFoundException $e) {
        }

        return $this->response->create(Status::STATUS_FAILED);
    }

    public function deleteUserAction($groupName, $username)
    {
        try {
            $this->userGroupModel->removeUser($groupName, $username);
            return $this->response->create(Status::STATUS_SUCCESS, null, Status::HTTP_ACCEPTED);

        } catch (RecordNotFoundException $e) {
        }

        return $this->response->create(Status::STATUS_FAILED);
    }
}