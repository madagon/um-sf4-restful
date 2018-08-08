<?php

namespace App\Model\UserGroup;

use App\Exception\DuplicateRecordException;
use App\Exception\NotEmptyException;
use App\Exception\RecordNotFoundException;
use FOS\UserBundle\Model\GroupInterface;

interface UserGroupModelInterface
{
    public function create(): GroupInterface;

    /**
     * @param GroupInterface $userGroup
     * @throws DuplicateRecordException
     */
    public function persist(GroupInterface $userGroup): void;

    /**
     * @param string $name
     * @throws RecordNotFoundException
     * @throws NotEmptyException
     */
    public function deleteByName(string $name): void;

    /**
     * @param string $groupName
     * @param string $username
     * @throws RecordNotFoundException
     */
    public function addUser(string $groupName, string $username): void;

    /**
     * @param string $groupName
     * @param string $username
     * @throws RecordNotFoundException
     */
    public function removeUser(string $groupName, string $username): void;
}