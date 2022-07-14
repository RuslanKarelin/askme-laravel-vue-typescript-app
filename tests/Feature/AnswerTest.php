<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Question;
use App\Models\User;
use App\Models\Answer;
use Database\Seeders\InitialSeeder;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\Roles;
use Illuminate\Testing\Fluent\AssertableJson;

class AnswerTest extends TestCase
{
    use RefreshDatabase;

    private $question;
    private $user;
    private $answer;

    protected $seeder = InitialSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->hasProfile()->create();
        $this->question = Question::factory()->hasState()->for($this->user)->create();
        $this->answer = Answer::factory(['question_id' => $this->question->id])->for($this->user)->create();
    }

    protected function check_update_answer($user)
    {
        $response = $this->actingAs($user)->patch(
            route(
                'questions.answers.update',
                [
                    'question' => $this->question->id,
                    'answer' => $this->answer->id,
                ]
            ),
            [
                'detail' => 'test'
            ]
        );
        $this->assertDatabaseHas('answers', [
            'detail' => 'test',
            'question_id' => $this->question->id,
            'id' => $this->answer->id,
        ]);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
            ->has('status')
        );

        $response->assertOk();
    }

    public function test_ban_on_creating_a_answer_to_an_unauthorized_user()
    {
        $response = $this->post(route('questions.answers.store', ['question' => $this->question->id]),
            ['detail' => 'test']
        );

        $response->assertRedirect(route('login'));
    }

    public function test_validation_check_when_creating_a_answer()
    {
        $response = $this->actingAs($this->user)->post(
            route(
                'questions.answers.store',
                ['question' => $this->question->id]
            ),
            [
                'detail' => '',
            ]
        );

        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_creating_a_answer()
    {
        $response = $this->actingAs($this->user)->post(
            route(
                'questions.answers.store',
                ['question' => $this->question->id]
            ),
            ['detail' => 'test']);

        $this->assertDatabaseHas('answers', ['detail' => 'test']);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
                ->has('status')
        );

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_ban_on_updating_a_answer_to_an_unauthorized_user()
    {
        $response = $this->patch(route('questions.answers.update', [
            'question' => $this->question->id,
            'answer' => $this->answer->id
        ]));

        $response->assertRedirect(route('login'));
    }

    public function test_validation_check_when_updating_a_answer()
    {
        $response = $this->actingAs($this->user)->patch(
            route(
                'questions.answers.update',
                [
                    'question' => $this->question->id,
                    'answer' => $this->answer->id,
                ]
            ),
            [
                'detail' => ''
            ]
        );

        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_updating_a_answer_by_owner()
    {
        $this->check_update_answer($this->user);
    }

    public function test_updating_a_answer_by_admin()
    {
        $adminUser = User::whereHas(
            'roles',
            function (Builder $query) {
                $query->where('title', Roles::Admin);
            }
        )->first();
        $this->check_update_answer($adminUser);
    }

    public function test_updating_a_answer_by_not_admin_or_not_owner()
    {
        $user = User::factory()->hasProfile()->create();
        $response = $this->actingAs($user)->patch(
            route(
                'questions.answers.update',
                [
                    'question' => $this->question->id,
                    'answer' => $this->answer->id,
                ]
            ),
            [
                'detail' => 'test'
            ]
        );
        $this->assertDatabaseMissing('answers', [
            'detail' => 'test',
            'question_id' => $this->question->id,
            'id' => $this->answer->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_deleting_a_answer_by_owner()
    {
        $response = $this->actingAs($this->user)->delete(route('questions.answers.destroy',
                [
                    'question' => $this->question->id,
                    'answer' => $this->answer->id,
                ]
            )
        );

        $this->assertDatabaseMissing('answers', [
            'id' => $this->answer->id,
            'question_id' => $this->question->id,
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_deleting_a_answer_by_admin()
    {
        $question = Question::factory()->hasState()->for($this->user)->create();
        $answer = Answer::factory()->for($question)->for($this->user)->create();
        $adminUser = User::whereHas(
            'roles',
            function (Builder $query) {
                $query->where('title', Roles::Admin);
            }
        )->first();
        $response = $this->actingAs($adminUser)->delete(route('questions.answers.destroy',
                [
                    'question' => $question->id,
                    'answer' => $answer->id,
                ]
            )
        );

        $this->assertDatabaseMissing('answers', [
            'id' => $answer->id,
            'question_id' => $question->id
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_deleting_a_answer_by_not_admin_or_not_owner()
    {
        $question = Question::factory()->hasState()->for($this->user)->create();
        $answer = Answer::factory()->for($question)->for($this->user)->create();
        $user = User::factory()->hasProfile()->create();
        $response = $this->actingAs($user)->delete(route('questions.answers.destroy',
                [
                    'question' => $question->id,
                    'answer' => $answer->id,
                ])
        );

        $this->assertDatabaseHas('answers', [
            'id' => $answer->id,
            'question_id' => $question->id,
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_add_or_remove_answer_like_by_unauthorized_user()
    {
        $response = $this->post(route('answers.add.like',
                ['answer' => $this->answer->id])
        );

        $response->assertRedirect(route('login'));
    }

    public function test_add_or_remove_answer_like_by_authorized_user()
    {
        $response = $this->actingAs($this->user)->post(route('answers.add.like',
                ['answer' => $this->answer->id])
        );

        $this->assertDatabaseHas('answer_like', [
                'answer_id' => $this->answer->id, 'user_id' => $this->user->id]
        );

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
        );

        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->actingAs($this->user)->post(route('answers.add.like',
                ['answer' => $this->answer->id])
        );

        $this->assertDatabaseMissing('answer_like', [
                'answer_id' => $this->answer->id, 'user_id' => $this->user->id]
        );

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
