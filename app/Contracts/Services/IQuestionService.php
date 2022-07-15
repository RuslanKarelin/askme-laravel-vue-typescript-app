<?php
namespace App\Contracts\Services;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Contracts\Helpers\Response\IResponseHelper;

interface IQuestionService {
    public function create(Request $request): IResponseHelper;
    public function store(Request $request): Question;
    public function edit(Request $request, Question $question): IResponseHelper;
    public function update(Request $request, Question $question): Question;
    public function show(Request $request, Question $question): IResponseHelper;
    public function destroy(Question $question): bool;
    public function incrementViews(Question $question): Question;
    public function addLike(Request $request, Question $question): JsonResponse;
}