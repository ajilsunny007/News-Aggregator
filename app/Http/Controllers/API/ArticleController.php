<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleSearchRequest;
use App\Models\Article;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['category', 'source', 'date']);
        return response()->json($this->articleRepository->all($filters));
    }

    public function show($id)
    {
        return response()->json($this->articleRepository->find($id));
    }

    public function search(ArticleSearchRequest $request)
    {
        $filters = $request->only(['category', 'source', 'date']);
        return response()->json($this->articleRepository->search($request->query, $filters));
    }

    public function personalizedFeed(Request $request)
    {
        return response()->json($this->articleRepository->getPersonalizedFeed($request->user()->id));
    }
}
