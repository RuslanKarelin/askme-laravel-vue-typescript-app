<?php

namespace App\Services;

use App\Contracts\Services\IAnswerService;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Contracts\Helpers\Response\IResponseHelper;


class AnswerService implements IAnswerService
{
    private $responseHelper;

    public function __construct()
    {
        $this->responseHelper = app(IResponseHelper::class);
    }

    public function store(Request $request, Question $question): Answer
    {
        $answer = $question->answers()->create(
            array_merge(
                $request->validated(),
                ['user_id' => $request->user()->id]
            )
        );

        return $answer;
    }

    public function update(Request $request, Answer $answer): Answer
    {
        $answer->updateOrFail($request->validated());
        return $answer;
    }

    public function destroy(Answer $answer): bool
    {
        return $answer->delete();
    }

    public function addLike(Request $request, Answer $answer): JsonResponse
    {
        $userId = $request->user()->id;
        if ($answer->likes()->where('user_id', $userId)->count()) {
            $answer->likes()->detach([$userId]);
            return $this->responseHelper->json(Response::HTTP_NO_CONTENT);
        }

        $answer->likes()->attach([$userId]);
        return $this->responseHelper->json(Response::HTTP_CREATED);
    }
}