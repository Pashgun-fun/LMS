<?php

namespace interfaces;

use entites\Publish;

interface Articles
{
    public function getAllArticles(): array;

    public function readAllArticles(): array;

    public function deleteArticle(int $indexDel, int $id);

    public function openEditWindowArticle(int $indexEdit, int $id): array;

    public function editArticle(Publish $publish);

    public function newArticleBlock(Publish $publish): array;

    public function pagination(int $page): array;
}