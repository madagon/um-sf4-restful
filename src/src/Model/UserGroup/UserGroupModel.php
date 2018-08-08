<?php

namespace App\Model\UserGroup;

use App\Exception\DuplicateRecordException;
use App\Exception\NotEmptyException;
use App\Exception\RecordNotFoundException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use FOS\UserBundle\Model\GroupInterface;
use FOS\UserBundle\Model\GroupManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class UserGroupModel implements UserGroupModelInterface
{
    /**
     * @var GroupManagerInterface
     */
    private $groupManager;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    public function __construct(GroupManagerInterface $groupManager, UserManagerInterface $userManager)
    {
        $this->groupManager = $groupManager;
        $this->userManager = $userManager;
    }

    public function create(): GroupInterface
    {
        return $this->groupManager->createGroup('');
    }

    /**
     * @param GroupInterface $userGroup
     * @throws DuplicateRecordException
     */
    public function persist(GroupInterface $userGroup): void
    {
        if ($this->groupManager->findGroupByName($userGroup->getName())) {
            throw new DuplicateRecordException();
        }

        $this->groupManager->updateGroup($userGroup);
    }

    /**
     * @param string $name
     * @throws RecordNotFoundException
     * @throws NotEmptyException
     */
    public function deleteByName(string $name): void
    {
        $group = $this->groupManager->findGroupByName($name);
        if (null === $group) {
            throw new RecordNotFoundException();
        }

        try {
            $this->groupManager->deleteGroup($group);

        } catch (ForeignKeyConstraintViolationException $exception) {
            throw new NotEmptyException();
        }
    }

    /**
     * @param string $groupName
     * @param string $username
     * @throws RecordNotFoundException
     */
    public function addUser(string $groupName, string $username): void
    {
        $group = $this->groupManager->findGroupByName($groupName);
        if (null === $group) {
            throw new RecordNotFoundException();
        }

        $user = $this->userManager->findUserByUsername($username);
        if (null === $user) {
            throw new RecordNotFoundException();
        }

        $user->addGroup($group);

        $this->userManager->updateUser($user);
    }

    /**
     * @param string $groupName
     * @param string $username
     * @throws RecordNotFoundException
     */
    public function removeUser(string $groupName, string $username): void
    {
        $group = $this->groupManager->findGroupByName($groupName);
        if (null === $group) {
            throw new RecordNotFoundException();
        }

        $user = $this->userManager->findUserByUsername($username);
        if (null === $user) {
            throw new RecordNotFoundException();
        }

        $user->removeGroup($group);

        $this->userManager->updateUser($user);
    }
}