<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Answer;
use App\Models\Comment;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Answer $answer)
    {
        $comment = $answer->comments()->create(
            array_merge(
                $request->validated(),
                ['user_id' => $request->user()->id]
            )
        );

        return response()->json(
            [
                'data' => new CommentResource($comment),
                'status' => true
            ], Response::HTTP_CREATED
        );
    }

    public function update(CommentRequest $request, Answer $answer, Comment $comment)
    {
        $comment->updateOrFail($request->validated());

        return response()->json(
            [
                'data' => new CommentResource($comment),
                'status' => true
            ], Response::HTTP_OK
        );
    }

    public function destroy(Request $request, Answer $answer, Comment $comment)
    {
        $comment->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
