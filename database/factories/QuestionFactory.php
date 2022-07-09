<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\QuestionStatus;
use App\Models\QuestionState;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    private static $questionStatuses;

    private static function getQuestionStatuses(): Collection
    {
        if (static::$questionStatuses) {
            return static::$questionStatuses;
        }
        static::$questionStatuses = QuestionStatus::select('id')->get();
        return static::$questionStatuses;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $questionStatuses = static::getQuestionStatuses();
        return [
            'title' => fake()->realText(50),
            'detail' => fake()->realTextBetween(50, 100),
            'status_id' => $questionStatuses->pluck('id')->random(1)->first(),
            'state_id' => QuestionState::factory()
        ];
    }
}
