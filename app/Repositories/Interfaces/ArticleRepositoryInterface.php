<?php

namespace App\Repositories\Interfaces;

interface ArticleRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function search(string $query, array $filters = []);
    public function getPersonalizedFeed($userId, int $perPage = 15);
}
