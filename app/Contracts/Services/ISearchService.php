<?php
namespace App\Contracts\Services;

use App\Contracts\Helpers\Response\IResponseHelper;

interface ISearchService {
    public function handle(): IResponseHelper;
}