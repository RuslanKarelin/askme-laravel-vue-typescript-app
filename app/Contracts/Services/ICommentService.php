<?php
namespace App\Contracts\Services;

use App\Models\Answer;
use App\Models\Comment;
use Illuminate\Http\Request;

interface ICommentService {
    public function store(Request $request, Answer $answer): Comment;
    public function update(Request $request, Comment $comment): Comment;
    public function destroy(Comment $comment): bool;
}