<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Contracts\Services\IQuestionService;
use App\Contracts\Helpers\Response\IResponseHelper;

class QuestionController extends Controller
{
    public function __construct(
        private IQuestionService $questionService,
        private IResponseHelper $responseHelper
    ){}

    public function index(Request $request)
    {
        try{
            $list = $this->questionService->getList($request);
           // dd($list);
            return $this->responseHelper->json(
                Response::HTTP_OK,
                QuestionResource::collection($list)->response()->getData(true),
                true
            );
        } catch (\Exception $e) {
            return $this->responseHelper->jsonError($e->getCode(), $e->getMessage());
        }
    }
}
