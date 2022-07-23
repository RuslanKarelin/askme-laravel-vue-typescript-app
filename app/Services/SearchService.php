<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Contracts\Helpers\Response\IResponseHelper;
use Illuminate\Database\Eloquent\Builder;
use App\Contracts\Services\ISearchService;
use App\Helpers\PageHelper;


class SearchService implements ISearchService
{
    private $request;
    private $responseHelper;
    private $pageHelper;
    private $withRelations = [
        'user.profile.user',
        'user.roles',
        'tags',
        'state',
        'status'
    ];

    private function getListByTag(string $searchQuery)
    {
        $this->pageHelper->setDefaultBreadcrumb()
            ->addBreadcrumb('Search by tag: ' . $searchQuery)
            ->setPageTitle('Search by tag: ' . $searchQuery);
        return Question::with($this->withRelations)->whereHas('tags', function (Builder $query) use ($searchQuery) {
            $query->where('title', '=', $searchQuery);
        })->withCount(['answers'])->paginate(config('question.perPage'));
    }

    private function getListByQuery(string $searchQuery)
    {
        $this->pageHelper->setDefaultBreadcrumb()
            ->addBreadcrumb('Search by query: ' . $searchQuery)
            ->setPageTitle('Search by query: ' . $searchQuery);
        return Question::with($this->withRelations)->where('title', 'like', '%' . $searchQuery . '%')
            ->orWhere('detail', 'like', '%' . $searchQuery . '%')
            ->withCount(['answers'])
            ->paginate(config('question.perPage'));
    }

    public function __construct()
    {
        $this->request = app(Request::class);
        $this->responseHelper = app(IResponseHelper::class);
        $this->pageHelper = app(PageHelper::class);
    }

    public function handle(): IResponseHelper
    {
        if (
            (!$this->request->query->has('tag') && !$this->request->query->has('query')) ||
            (empty($this->request->query->get('tag')) && empty($this->request->query->get('query')))
        ) abort(404);

        if ($this->request->query->has('tag')) {
            $searchQuery = $this->request->query->get('tag');
            $list = $this->getListByTag($searchQuery);
        } else {
            $searchQuery = $this->request->query->get('query');
            $list = $this->getListByQuery($searchQuery);
        }

        return $this->responseHelper->setViewData('themes.askme.pages.search.search', [
            'searchQuery' => $searchQuery,
            'list' => $list->appends($this->request->query())
        ]);
    }
}