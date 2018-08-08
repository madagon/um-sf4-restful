<?php

namespace App\Model\User;

use App\Exception\RecordNotFoundException;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class UserModel implements UserModelInterface
{
    /**
     * @var UserManagerInterface
     */
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function create(): UserInterface
    {
        return $this->userManager->createUser();
    }

    public function persist(UserInterface $user): void
    {
        $this->userManager->updateUser($user);
    }

    /**
     * @param string $username
     * @throws RecordNotFoundException
     */
    public function deleteByUsername(string $username): void
    {
        $user = $this->userManager->findUserByUsername($username);
        if (null === $user) {
            throw new RecordNotFoundException();
        }

        $this->userManager->deleteUser($user);
    }
}