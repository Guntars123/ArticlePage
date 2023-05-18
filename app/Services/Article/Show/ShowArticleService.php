<?php declare(strict_types=1);

namespace App\Services\Article\Show;

use App\ApiClient;
use App\Exceptions\ResourceNotFoundException;


class ShowArticleService
{
    private ApiClient $apiClient;

    public function __construct()
    {
        $this->apiClient = new ApiClient();
    }

    public function execute(ShowArticleRequest $request): ShowArticleResponse
    {
        $article = $this->apiClient->getArticleByArticleId($request->getArticleId());

        if ($article == null) {
            throw new ResourceNotFoundException("Article by id {$request->getArticleId()} not found");
        }

        $comments = $this->apiClient->getCommentsByArticleId($article->getId());

        return new ShowArticleResponse($article, $comments);
    }

}
