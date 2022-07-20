<?php

namespace App\Helpers\Response;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Contracts\Helpers\Response\IResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\Factory;

class ResponseHelper implements IResponseHelper
{
    protected $viewName;
    protected $viewData;

    public function json(int $code, JsonResource|array $data = [], bool $collection = false): JsonResponse
    {
        $responseData = ['status' => true];
        if (!$collection && $data) $responseData['data'] = $data;

        if ($collection) {
            $data['status'] = $responseData['status'];
            $responseData = $data;
        }

        return match($code){
            Response::HTTP_CREATED, Response::HTTP_OK => response()->json($responseData, $code),
            Response::HTTP_NO_CONTENT => response()->json(status: $code)
        };
    }

    public function jsonError(int $code, string $message): JsonResponse
    {
        Log::error("jsonError:: code {$code} - {$message}");
        return response()->json(['status' => false, 'error' => $message], $code);
    }

    public function setViewData(string $viewName, array $viewData = []): static
    {
        $this->viewName = $viewName;
        $this->viewData = $viewData;
        return $this;
    }

    public function view(): View|Factory
    {
        return view($this->viewName, $this->viewData);
    }
}