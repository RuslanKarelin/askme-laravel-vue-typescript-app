<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\Services\ISearchService;

class SearchController extends Controller
{
    public function __construct(
        private ISearchService $searchService
    ){}

    public function __invoke(Request $request)
    {
        return $this->searchService->handle()->view();
    }
}
