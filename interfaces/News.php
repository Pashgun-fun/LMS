<?php

namespace interfaces;

use entites\Publish;

interface News
{
    public function getAllNews(): array;

    public function removeNews(int $indexDel, int $id);

    public function openEditWindowNews(int $indexEdit, int $id): array;

    public function edit(Publish $publish);

    public function newNewsBlock(Publish $publish): array;

    public function oldNews(int $index): array;

    public function pagination(int $page): array;
}