<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Answer;
use App\Models\Comment;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Response;
use App\Contracts\Services\ICommentService;
use App\Contracts\Helpers\Response\IResponseHelper;

class CommentController extends Controller
{
    public function __construct(
        private ICommentService $commentService,
        private IResponseHelper $responseHelper
    ){}

    public function store(CommentRequest $request, Answer $answer)
    {
        try{
            $comment = $this->commentService->store($request, $answer);
            return $this->responseHelper->json(Response::HTTP_CREATED, new CommentResource($comment));
        } catch (\Exception $e) {
            return $this->responseHelper->jsonError($e->getMessage(), $e->getCode());
        }
    }

    public function update(CommentRequest $request, Answer $answer, Comment $comment)
    {
        try{
            $comment = $this->commentService->update($request, $comment);
            return $this->responseHelper->json(Response::HTTP_OK, new CommentResource($comment));
        } catch (\Exception $e) {
            return $this->responseHelper->jsonError($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(Request $request, Answer $answer, Comment $comment)
    {
        try{
            $this->commentService->destroy($comment);
            return $this->responseHelper->json(Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->responseHelper->jsonError($e->getMessage(), $e->getCode());
        }
    }
}
