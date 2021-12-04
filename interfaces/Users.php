<?php

namespace interfaces;

use entites\User;

interface Users
{
    public function newUser(User $user): string;

    public function deleteUser(int $indexDel, int $id);

    public function editUser(User $user);

    public function getAllUsers(): array;

    public function getAuthorization(User $user): array;
}