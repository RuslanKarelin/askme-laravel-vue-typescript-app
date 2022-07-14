<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AnswerRequest;
use App\Models\Question;
use App\Models\Answer;
use App\Http\Resources\AnswerResource;
use Illuminate\Http\Response;

class AnswerController extends Controller
{
    public function store(AnswerRequest $request, Question $question)
    {
        $answer = $question->answers()->create(
            array_merge(
                $request->validated(),
                ['user_id' => $request->user()->id]
            )
        );

        return response()->json(
            [
                'data' => new AnswerResource($answer),
                'status' => true
            ], Response::HTTP_CREATED
        );
    }

    public function update(AnswerRequest $request, Question $question, Answer $answer)
    {
        $answer->updateOrFail($request->validated());

        return response()->json(
            [
                'data' => new AnswerResource($answer),
                'status' => true
            ], Response::HTTP_OK
        );
    }

    public function destroy(Request $request, Question $question, Answer $answer)
    {
        $answer->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function addLike(Request $request, Answer $answer)
    {
        $userId = $request->user()->id;
        if ($answer->likes()->where('user_id', $userId)->count()) {
            $answer->likes()->detach([$userId]);
            return response()->json(status: Response::HTTP_NO_CONTENT);
        }

        $answer->likes()->attach([$userId]);

        return response()->json(
            [
                'status' => true
            ], Response::HTTP_CREATED
        );

    }
}
