<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\QuestionStatus;
use App\Models\Tag;
use App\Http\Requests\QuestionRequest;
use App\Enums\QuestionStatuses;
use App\Http\Resources\QuestionResource;
use Illuminate\Http\Response;

class QuestionController extends Controller
{

    public function create(Request $request)
    {

    }

    public function store(QuestionRequest $request)
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

        return response()->redirectToRoute('questions.edit', ['question' => $question->id]);
    }

    public function show(Question $question)
    {
        $question->state->increment('views');
        return response()->json(new QuestionResource($question));
    }

    public function edit(Request $request, Question $question)
    {

    }

    public function update(QuestionRequest $request, Question $question)
    {
        $question->updateOrFail($request->validated());

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

        return response()->redirectToRoute('questions.edit', ['question' => $question->id]);
    }

    public function destroy(Request $request, Question $question)
    {
        $question->delete();

        return response()->redirectToRoute('home');
    }

    public function addLike(Request $request, Question $question)
    {
        $userId = $request->user()->id;
        if ($question->likes()->where('user_id', $userId)->count()) {
            $question->likes()->detach([$userId]);
            return response()->json(status: Response::HTTP_NO_CONTENT);
        }

        $question->likes()->attach([$userId]);

        return response()->json(
            [
                'status' => true
            ], Response::HTTP_CREATED
        );

    }
}
