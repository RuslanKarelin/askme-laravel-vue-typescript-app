<?php

namespace App\Services;

use App\Contracts\Services\ICommentService;
use App\Models\Answer;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;


class CommentService implements ICommentService
{
    public function store(Request $request, Answer $answer): Comment
    {
        $comment = $answer->comments()->create(
            array_merge(
                $request->validated(),
                ['user_id' => $request->user()->id]
            )
        );

        return $comment;
    }

    public function update(Request $request, Comment $comment): Comment
    {
        $comment->updateOrFail($request->validated());
        return $comment;
    }

    public function destroy(Comment $comment): bool
    {
        return $comment->delete();
    }

}