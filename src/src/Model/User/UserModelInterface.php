<?php

namespace App\Model\User;

use App\Exception\RecordNotFoundException;
use FOS\UserBundle\Model\UserInterface;

interface UserModelInterface
{
    public function create(): UserInterface;

    public function persist(UserInterface $user): void;

    /**
     * @param string $username
     * @throws RecordNotFoundException
     */
    public function deleteByUsername(string $username): void;
}