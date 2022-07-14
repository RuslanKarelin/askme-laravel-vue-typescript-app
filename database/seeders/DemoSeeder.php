<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;

class DemoSeeder extends Seeder
{
    use WithoutModelEvents;

    const COUNT_USERS = 10;
    const COUNT_QUESTIONS = 10;
    const COUNT_ANSWERS = 50;
    const COUNT_COMMENTS = 250;
    const COUNT_TAGS = 1;

    private static $questions;

    private static function getQuestions(): Collection
    {
        if (static::$questions) {
            return static::$questions;
        }
        static::$questions = Question::select('id')->get();
        return static::$questions;
    }

    private function createUsers() : Collection
    {
        $users = User::factory(static::COUNT_USERS)
            ->hasProfile()
            ->has(
                Question::factory()
                    ->hasTags(static::COUNT_TAGS)
                    ->hasState()
                    ->count(static::COUNT_QUESTIONS)
            )
            ->create();

        foreach ($users as $user) {
            Storage::disk('public')->copy(
                config('storage.avatars').'avatar.png',
                config('storage.avatars').$user->id.'/avatar.png'
            );
        }

        $userRole = Role::select('id')->where('title', 'User')->first()?->id;
        if ($userRole) {
            foreach ($users as $user) {
                $user->roles()->attach(
                    [$userRole]
                );
            }
        }

        return $users;
    }

    private function createAnswers(Collection $users): static
    {
        $questions = static::getQuestions();

        foreach (range(0, static::COUNT_ANSWERS) as $number) {
            $answer = Answer::factory()
                ->for($questions->random(1)->first())
                ->for($users->random(1)->first())
                ->create();

            $answer->likes()->attach($users->random(rand(1, static::COUNT_USERS))->pluck('id')->toArray());
        }

        return $this;
    }

    private function createComments(Collection $users): static
    {
        $answers = Answer::select('id')->get();

        foreach (range(0, static::COUNT_COMMENTS) as $number) {
            Comment::factory()
                ->for($answers->random(1)->first())
                ->for($users->random(1)->first())
                ->create();
        }

        return $this;
    }

    private function createQuestionLikes(Collection $users): static
    {
        $questions = static::getQuestions();

        foreach ($questions as $question) {
            $question->likes()->attach($users->random(rand(1, static::COUNT_USERS))->pluck('id')->toArray());
        }

        return $this;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = $this->createUsers();
        $this->createAnswers($users)
            ->createComments($users)
            ->createQuestionLikes($users);
    }
}
