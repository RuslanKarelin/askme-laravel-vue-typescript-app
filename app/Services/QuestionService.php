<?php

namespace App\Services;

use App\Contracts\Services\IQuestionService;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\QuestionStatus;
use App\Models\User;
use App\Enums\QuestionStatuses;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Contracts\Helpers\Response\IResponseHelper;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\QuestionFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Helpers\PageHelper;


class QuestionService implements IQuestionService
{
    private $responseHelper;
    private $pageHelper;
    private $withRelations = [
        'user.profile.user',
        'user.roles',
        'tags',
        'state',
        'status'
    ];

    private function addTags(Request $request, Question $question)
    {
        if ($request->has('tags') && !empty($request->get('tags'))) {
            $tagsFromRequest = explode(',', $request->get('tags'));
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

    private function recentQuestions(): Builder
    {
        return Question::with($this->withRelations)->withCount(['answers'])->orderBy('id', 'desc');
    }

    private function mostResponses(): Builder
    {
        return Question::with($this->withRelations)
            ->withCount(['answers'])
            ->orderBy('answers_count', 'desc')
            ->orderBy('questions.id', 'asc');
    }

    private function recentlyAnswered(): Builder
    {
        return Question::select('questions.*', DB::raw('MAX(answers.created_at) as max_created_at'))
            ->with($this->withRelations)
            ->distinct()
            ->join('answers', 'answers.question_id', '=', 'questions.id')
            ->groupBy('questions.id')
            ->orderBy('max_created_at', 'desc')
            ->orderBy('questions.id', 'asc')
            ->withCount(['answers']);
    }

    private function noAnswers(): Builder
    {
        return Question::with($this->withRelations)->withCount(['answers'])->doesntHave('answers');
    }

    public function __construct()
    {
        $this->responseHelper = app(IResponseHelper::class);
        $this->pageHelper = app(PageHelper::class);
    }

    public function create(Request $request): IResponseHelper
    {
        $this->pageHelper->setDefaultBreadcrumb()
            ->addBreadcrumb('Create Question')
            ->setPageTitle('Create Question');
        return $this->responseHelper->setViewData('themes.askme.pages.questions.create');
    }

    public function store(Request $request): Question
    {
        $question = $request->user()->questions()->create(
            array_merge(
                $request->validated(),
                ['status_id' => QuestionStatus::where('title', QuestionStatuses::Process)->first() ?->id]
            )
        );
        $question->state()->create();
        $this->addTags($request, $question);
        return $question;
    }

    public function edit(Request $request, Question $question): IResponseHelper
    {
        $this->pageHelper->setDefaultBreadcrumb()
            ->addBreadcrumb('Edit Question', route('questions.edit', ['question' => $question->id]))
            ->addBreadcrumb($question->title)
            ->setPageTitle('Edit Question: ' . $question->title);
        return $this->responseHelper->setViewData('themes.askme.pages.questions.edit', [
            'question' => $question,
            'tags' => implode(',', $question->tags->pluck('title')->toArray()),
            'statuses' => QuestionStatus::select(['id', 'title'])->get()
        ]);
    }

    public function update(Request $request, Question $question): Question
    {
        $question->updateOrFail(array_merge(
            $request->validated(),
            ['status_id' => $request->get('status_id')]
        ));
        $this->addTags($request, $question);
        return $question;
    }

    public function show(Request $request, Question $question): IResponseHelper
    {
        $this->pageHelper->setDefaultBreadcrumb()
            ->addBreadcrumb($question->title)
            ->setPageTitle($question->title);
        return $this->responseHelper->setViewData('themes.askme.pages.questions.show', [
            'question' => $question->load(['status', 'state', 'user.profile', 'tags'])->loadCount(['likes', 'answers'])
        ]);
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

    public function getList(Request $request): LengthAwarePaginator
    {
        $builder = match($request->get('filter')){
            QuestionFilter::recentQuestions->value => $this->recentQuestions(),
            QuestionFilter::mostResponses->value => $this->mostResponses(),
            QuestionFilter::recentlyAnswered->value => $this->recentlyAnswered(),
            QuestionFilter::noAnswers->value => $this->noAnswers(),
            default => Question::with($this->withRelations)->withCount(['answers'])
        };
        return $builder->paginate(config('question.perPage'));
    }

    public function getUserQuestions(Request $request, User $user): IResponseHelper
    {
        $userFullName = $user->profile->fullName();
        $this->pageHelper->setDefaultBreadcrumb()
            ->addBreadcrumb('User Profile', route('users.profile.show', ['user' => $user->id]))
            ->addBreadcrumb('User questions')
            ->setPageTitle('User questions: ' . $userFullName);
        return $this->responseHelper->setViewData('themes.askme.pages.user-profile.questions', [
            'questions' => $user->loadCount(['questions', 'answers'])
                ->questions()
                ->withCount(['answers'])
                ->paginate(config('question.perPage'))
                ->appends($request->query()),
            'user' => $user
        ]);
    }

    public function getUserQuestionThroughAnswers(Request $request, User $user): IResponseHelper
    {
        $userFullName = $user->profile->fullName();
        $this->pageHelper->setDefaultBreadcrumb()
            ->addBreadcrumb('User Profile', route('users.profile.show', ['user' => $user->id]))
            ->addBreadcrumb('Questions answered by the user')
            ->setPageTitle('Questions answered by the user: ' . $userFullName);
        return $this->responseHelper->setViewData('themes.askme.pages.user-profile.questions', [
            'questions' => Question::selectRaw('questions.*')
                ->join('answers', 'answers.question_id', '=', 'questions.id')
                ->groupby('questions.id')
                ->where('answers.user_id', $user->id)
                ->withCount(['answers'])
                ->paginate(config('question.perPage'))
                ->appends($request->query()),
            'user' => $user->loadCount(['questions', 'answers'])
        ]);
    }
}