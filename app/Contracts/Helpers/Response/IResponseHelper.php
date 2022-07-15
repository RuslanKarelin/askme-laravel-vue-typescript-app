<?php

namespace App\Contracts\Helpers\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

interface IResponseHelper
{
    public function json(int $code, JsonResource|array $data = []): JsonResponse;
    public function jsonError(int $code, string $message): JsonResponse;
    public function setViewData(string $viewName, array $viewData = []): static;
    public function view(): View|Factory;
}