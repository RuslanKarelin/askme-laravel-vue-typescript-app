<?php
namespace App\Contracts\Services;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface IAnswerService {
    public function store(Request $request, Question $question): Answer;
    public function update(Request $request, Answer $answer): Answer;
    public function destroy(Answer $answer): bool;
    public function addLike(Request $request, Answer $answer): JsonResponse;
}