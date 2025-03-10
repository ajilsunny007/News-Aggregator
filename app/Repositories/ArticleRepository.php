<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\User;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class ArticleRepository implements ArticleRepositoryInterface
{
    protected $model;

    public function __construct(Article $article)
    {
        $this->model = $article;
    }

    public function all(array $filters = [], int $perPage = 15)
    {
        $query = $this->model->newQuery();
        
        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        
        if (isset($filters['source'])) {
            $query->where('source', $filters['source']);
        }
        
        if (isset($filters['date'])) {
            $query->whereDate('published_at', $filters['date']);
        }

        return $query->latest('published_at')->paginate($perPage);
    }

    public function find($id)
    {
        return Cache::remember("article.{$id}", 3600, function () use ($id) {
            return $this->model->findOrFail($id);
        });
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $article = $this->find($id);
        $article->update($data);
        Cache::forget("article.{$id}");
        return $article;
    }

    public function delete($id)
    {
        $article = $this->find($id);
        Cache::forget("article.{$id}");
        return $article->delete();
    }

    public function search(string $query, array $filters = [])
    {
        return $this->model
            ->whereFullText(['title', 'content'], $query)
            ->when(isset($filters['category']), function (Builder $query) use ($filters) {
                $query->where('category', $filters['category']);
            })
            ->when(isset($filters['source']), function (Builder $query) use ($filters) {
                $query->where('source', $filters['source']);
            })
            ->latest('published_at')
            ->paginate(15);
    }

    public function getPersonalizedFeed($userId, int $perPage = 15)
    {
        $user = User::with('preferences')->findOrFail($userId);
        $preferences = $user->preferences;

        return $this->model
            ->when($preferences->preferred_sources, function (Builder $query) use ($preferences) {
                $query->whereIn('source', $preferences->preferred_sources);
            })
            ->when($preferences->preferred_categories, function (Builder $query) use ($preferences) {
                $query->whereIn('category', $preferences->preferred_categories);
            })
            ->when($preferences->preferred_authors, function (Builder $query) use ($preferences) {
                $query->whereIn('author', $preferences->preferred_authors);
            })
            ->latest('published_at')
            ->paginate($perPage);
    }
}