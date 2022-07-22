<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AnswerRequest;
use App\Models\Question;
use App\Models\Answer;
use App\Http\Resources\AnswerResource;
use Illuminate\Http\Response;
use App\Contracts\Services\IAnswerService;
use App\Contracts\Helpers\Response\IResponseHelper;

class AnswerController extends Controller
{
    public function __construct(
        private IAnswerService $answerService,
        private IResponseHelper $responseHelper
    ){}

    public function index(Request $request, Question $question)
    {
        try{
            $list = $this->answerService->getList($request, $question);
            return $this->responseHelper->json(
                Response::HTTP_OK,
                AnswerResource::collection($list)->response()->getData(true),
                true
            );
        } catch (\Exception $e) {
            return $this->responseHelper->jsonError($e->getCode(), $e->getMessage());
        }
    }

    public function store(AnswerRequest $request, Question $question)
    {
        try{
            $answer = $this->answerService->store($request, $question);
            return $this->responseHelper->json(Response::HTTP_CREATED, new AnswerResource($answer));
        } catch (\Exception $e) {
            return $this->responseHelper->jsonError($e->getCode(), $e->getMessage());
        }
    }

    public function update(AnswerRequest $request, Question $question, Answer $answer)
    {
        try{
            $answer = $this->answerService->update($request, $answer);
            return $this->responseHelper->json(Response::HTTP_OK, new AnswerResource($answer));
        } catch (\Exception $e) {
            return $this->responseHelper->jsonError($e->getCode(), $e->getMessage());
        }
    }

    public function destroy(Request $request, Question $question, Answer $answer)
    {
        try{
            $this->answerService->destroy($answer);
            return $this->responseHelper->json(Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->responseHelper->jsonError($e->getCode(), $e->getMessage());
        }
    }

    public function addLike(Request $request, Answer $answer)
    {
        try{
            return $this->answerService->addLike($request, $answer);
        } catch (\Exception $e) {
            return $this->responseHelper->jsonError($e->getCode(), $e->getMessage());
        }
    }
}
