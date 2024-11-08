<?php

namespace App\Interfaces;

interface FavoriteRepositoryInterface
{
    public function findAll();

    public function findId(int $id);

    public function addId(int $id, array $data);

    public function removeId(int $id);

}
