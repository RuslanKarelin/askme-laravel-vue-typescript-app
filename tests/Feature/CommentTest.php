<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Question;
use App\Models\User;
use App\Models\Answer;
use App\Models\Comment;
use Database\Seeders\InitialSeeder;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\Roles;
use Illuminate\Testing\Fluent\AssertableJson;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private $question;
    private $user;
    private $answer;
    private $comment;

    protected $seeder = InitialSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->hasProfile()->create();
        $this->question = Question::factory()->hasState()->for($this->user)->create();
        $this->answer = Answer::factory(['question_id' => $this->question->id])->for($this->user)->create();
        $this->comment = Comment::factory(['answer_id' => $this->answer->id])->for($this->user)->create();
    }

    protected function check_update_comment($user)
    {
        $response = $this->actingAs($user)->patch(
            route(
                'answers.comments.update',
                [
                    'answer' => $this->answer->id,
                    'comment' => $this->comment->id,
                ]
            ),
            [
                'detail' => 'test'
            ]
        );
        $this->assertDatabaseHas('comments', [
            'detail' => 'test',
            'answer_id' => $this->answer->id,
            'id' => $this->comment->id,
        ]);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
                ->has('status')
        );

        $response->assertOk();
    }

    public function test_ban_on_creating_a_comment_to_an_unauthorized_user()
    {
        $response = $this->post(route('answers.comments.store', ['answer' => $this->answer->id]),
            ['detail' => 'test']
        );

        $response->assertRedirect(route('login'));
    }

    public function test_validation_check_when_creating_a_comment()
    {
        $response = $this->actingAs($this->user)->post(
            route(
                'answers.comments.store',
                ['answer' => $this->answer->id]
            ),
            [
                'detail' => '',
            ]
        );

        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_creating_a_comment()
    {
        $response = $this->actingAs($this->user)->post(
            route(
                'answers.comments.store',
                ['answer' => $this->answer->id]
            ),
            ['detail' => 'test']);

        $this->assertDatabaseHas('comments', ['detail' => 'test']);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
                ->has('status')
        );

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_ban_on_updating_a_comment_to_an_unauthorized_user()
    {
        $response = $this->patch(route('answers.comments.update', [
            'answer' => $this->answer->id,
            'comment' => $this->comment->id,
        ]));

        $response->assertRedirect(route('login'));
    }

    public function test_validation_check_when_updating_a_comment()
    {
        $response = $this->actingAs($this->user)->patch(
            route(
                'answers.comments.update',
                [
                    'answer' => $this->answer->id,
                    'comment' => $this->comment->id,
                ]
            ),
            [
                'detail' => ''
            ]
        );

        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_updating_a_comment_by_owner()
    {
        $this->check_update_comment($this->user);
    }

    public function test_updating_a_comment_by_admin()
    {
        $adminUser = User::whereHas(
            'roles',
            function (Builder $query) {
                $query->where('title', Roles::Admin);
            }
        )->first();
        $this->check_update_comment($adminUser);
    }

    public function test_updating_a_comment_by_not_admin_or_not_owner()
    {
        $user = User::factory()->hasProfile()->create();
        $response = $this->actingAs($user)->patch(
            route(
                'answers.comments.update',
                [
                    'answer' => $this->answer->id,
                    'comment' => $this->comment->id,
                ]
            ),
            [
                'detail' => 'test'
            ]
        );
        $this->assertDatabaseMissing('comments', [
            'detail' => 'test',
            'answer_id' => $this->answer->id,
            'id' => $this->comment->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_deleting_a_comment_by_owner()
    {
        $response = $this->actingAs($this->user)->delete(route('answers.comments.destroy',
                [
                    'answer' => $this->answer->id,
                    'comment' => $this->comment->id,
                ]
            )
        );

        $this->assertDatabaseMissing('comments', [
            'id' => $this->comment->id,
            'answer_id' => $this->answer->id,
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_deleting_a_comment_by_admin()
    {
        $question = Question::factory()->hasState()->for($this->user)->create();
        $answer = Answer::factory()->for($question)->for($this->user)->create();
        $comment = Comment::factory()->for($answer)->for($this->user)->create();
        $adminUser = User::whereHas(
            'roles',
            function (Builder $query) {
                $query->where('title', Roles::Admin);
            }
        )->first();
        $response = $this->actingAs($adminUser)->delete(route('answers.comments.destroy',
                [
                    'answer' => $answer->id,
                    'comment' => $comment->id,
                ]
            )
        );

        $this->assertDatabaseMissing('comments', [
            'id' => $answer->id,
            'answer_id' => $comment->id
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_deleting_a_comment_by_not_admin_or_not_owner()
    {
        $question = Question::factory()->hasState()->for($this->user)->create();
        $answer = Answer::factory()->for($question)->for($this->user)->create();
        $comment = Comment::factory()->for($answer)->for($this->user)->create();
        $user = User::factory()->hasProfile()->create();
        $response = $this->actingAs($user)->delete(route('answers.comments.destroy',
                [
                    'answer' => $answer->id,
                    'comment' => $comment->id,
                ])
        );

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'answer_id' => $answer->id,
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
