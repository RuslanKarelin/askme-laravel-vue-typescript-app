<?php

namespace App\Services;

use App\Contracts\Services\IQuestionService;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\QuestionStatus;
use App\Enums\QuestionStatuses;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Contracts\Helpers\Response\IResponseHelper;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;


class QuestionService implements IQuestionService
{
    private $responseHelper;

    private function addTags(Request $request, Question $question)
    {
        if ($request->has('tags')) {
            $tagsFromRequest = $request->get('tags');
            $tags = Tag::select('id', 'title')->whereIn('title', $tagsFromRequest)->get();
            $nonExistingTags = collect($tagsFromRequest)->diff($tags->pluck('title'))->values()->toArray();
            Tag::insert(
                array_map(fn($tag) => ['title' => $tag, 'created_at' => now()], $nonExistingTags)
            );
            $tagsIds = $tags->pluck('id')->merge(
                Tag::select('id')->orderBy('id', 'desc')->limit(count($nonExistingTags))->get()->pluck('id')
            )->toArray();
            $question->tags()->sync($tagsIds);
        }
    }

    public function __construct()
    {
        $this->responseHelper = app(IResponseHelper::class);
    }

    public function create(Request $request): IResponseHelper
    {
        return $this->responseHelper->setViewData('themes.askme.pages.questions.create');
    }

    public function store(Request $request): Question
    {
        $question = $request->user()->questions()->create(
            array_merge(
                $request->validated(),
                [
                    'status_id' => QuestionStatus::where('title', QuestionStatuses::Process)->first() ?->id
                ]
            )
        );
        $question->state()->create();
        $this->addTags($request, $question);
        return $question;
    }

    public function edit(Request $request, Question $question): IResponseHelper
    {
        return $this->responseHelper->setViewData('themes.askme.pages.questions.edit', ['question' => $question]);
    }

    public function update(Request $request, Question $question): Question
    {
        $question->updateOrFail($request->validated());
        $this->addTags($request, $question);
        return $question;
    }

    public function show(Request $request, Question $question): IResponseHelper
    {
        return $this->responseHelper->setViewData('themes.askme.pages.questions.show', ['question' => $question]);
    }

    public function destroy(Question $question): bool
    {
        return $question->delete();
    }

    public function incrementViews(Question $question): Question
    {
        $question->state->increment('views');
        return $question;
    }

    public function addLike(Request $request, Question $question): JsonResponse
    {
        $userId = $request->user()->id;
        if ($question->likes()->where('user_id', $userId)->count()) {
            $question->likes()->detach([$userId]);
            return $this->responseHelper->json(Response::HTTP_NO_CONTENT);
        }

        $question->likes()->attach([$userId]);
        return $this->responseHelper->json(Response::HTTP_CREATED);
    }
}