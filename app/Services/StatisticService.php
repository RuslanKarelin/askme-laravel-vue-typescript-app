<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Tag;
use App\Models\Answer;
use App\Contracts\Services\IStatisticService;


class StatisticService implements IStatisticService
{
    public function getStatisticForSidebar(): array
    {
        return [
            'tags' => Tag::has('questions')->select('title')->orderBy('id', 'desc')->limit(20)->get()
                ->pluck('title')->toArray(),
            'question_count' => Question::count(),
            'answer_count' => Answer::count(),
            'last_questions' => Question::orderBy('id', 'desc')->limit(2)->get(),
        ];
    }
}