<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Http\Requests\QuestionRequest;
use App\Contracts\Services\IQuestionService;
use App\Contracts\Helpers\Response\IResponseHelper;

class QuestionController extends Controller
{
    public function __construct(
        private IQuestionService $questionService
    ){}

    public function create(Request $request)
    {
        return $this->questionService->create($request)->view();
    }

    public function store(QuestionRequest $request)
    {
        $question = $this->questionService->store($request);
        return response()->redirectToRoute('questions.edit', ['question' => $question->id]);
    }

    public function show(Request $request, Question $question)
    {
        $question = $this->questionService->incrementViews($question);
        return $this->questionService->show($request, $question)->view();
    }

    public function edit(Request $request, Question $question)
    {
        return $this->questionService->edit($request, $question)->view();
    }

    public function update(QuestionRequest $request, Question $question)
    {
        $question = $this->questionService->update($request, $question);
        return response()->redirectToRoute('questions.edit', ['question' => $question->id]);
    }

    public function destroy(Request $request, Question $question)
    {
        $this->questionService->destroy($question);
        return response()->redirectToRoute('home');
    }

    public function addLike(Request $request, Question $question)
    {
        try{
            return $this->questionService->addLike($request, $question);
        } catch (\Exception $e) {
            return $this->responseHelper->jsonError($e->getMessage(), $e->getCode());
        }
    }
}
