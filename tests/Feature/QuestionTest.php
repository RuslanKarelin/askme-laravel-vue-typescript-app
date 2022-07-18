<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Database\Seeders\InitialSeeder;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\Roles;
use Illuminate\Testing\Fluent\AssertableJson;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    private $question;
    private $user;

    protected $seeder = InitialSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->hasProfile()->create();
        $this->question = Question::factory()->hasState()->for($this->user)->create();
    }

    protected function getAdmin(): User
    {
        return User::whereHas(
            'roles',
            function (Builder $query) {
                $query->where('title', Roles::Admin);
            }
        )->first();
    }

    protected function check_update_question($user)
    {
        $response = $this->actingAs($user)->patch(
            route('questions.update', ['question' => $this->question->id]),
            [
                'title' => 'test',
                'detail' => 'test'
            ]
        );
        $this->assertDatabaseHas('questions', [
            'title' => 'test',
            'detail' => 'test',
            'id' => $this->question->id
        ]);
        $response->assertRedirect(route('questions.edit', ['question' => $this->question->id]));
    }

    public function test_show_question_status_ok()
    {
        $response = $this->get(route('questions.show', ['question' => $this->question->id]));

        $response->assertOk();
    }

    public function test_ban_on_editing_a_question_to_an_unauthorized_user()
    {
        $response = $this->get(route('questions.edit', ['question' => $this->question->id]));

        $response->assertRedirect(route('login'));
    }

    public function test_ban_on_creating_a_question_to_an_unauthorized_user()
    {
        $response = $this->post(route('questions.store'));

        $response->assertRedirect(route('login'));
    }

    public function test_access_is_allowed_to_an_authorized_user_owner_or_admin_to_the_question_editing_page()
    {
        $response = $this->actingAs($this->user)->get(route('questions.edit', ['question' => $this->question->id]));

        $response->assertOk();

        $adminUser = $this->getAdmin();

        $response = $this->actingAs($adminUser)->get(route('questions.edit', ['question' => $this->question->id]));

        $response->assertOk();
    }

    public function test_editing_a_question_by_not_admin_or_not_owner()
    {
        $user = User::factory()->hasProfile()->create();
        $response = $this->actingAs($user)->get(route('questions.edit', ['question' => $this->question->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_access_is_allowed_to_an_authorized_user_to_the_question_creating_page()
    {
        $response = $this->actingAs($this->user)->get(route('questions.create'));

        $response->assertOk();
    }

    public function test_validation_check_when_creating_a_question()
    {
        $response = $this->actingAs($this->user)->post(route('questions.store'), [
            'title' => '',
            'detail' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_validation_check_when_updating_a_question()
    {
        $response = $this->actingAs($this->user)->patch(
            route(
                'questions.update',
                ['question' => $this->question->id]
            ),
            [
                'title' => '',
                'detail' => '',
            ]
        );

        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_creating_a_question()
    {
        $response = $this->actingAs($this->user)->post(
            route('questions.store'),
            [
                'title' => 'test',
                'detail' => 'test',
                'tags' => 'php,js,ruby'
            ]
        );
        $this->assertDatabaseHas('questions', ['title' => 'test', 'detail' => 'test']);
        $this->assertDatabaseCount('tags', 3);
        $newQuestion = Question::select('id')->orderBy('id', 'desc')->first();
        $this->assertEquals(3, $newQuestion->tags()->count());
    }

    public function test_updating_a_question_by_owner()
    {
        $this->check_update_question($this->user);
    }

    public function test_updating_a_question_by_owner_with_tags()
    {
        $response = $this->actingAs($this->user)->patch(
            route('questions.update', ['question' => $this->question->id]),
            [
                'title' => 'test',
                'detail' => 'test',
                'tags' => 'php,python'
            ]
        );
        $this->assertDatabaseCount('tags', 2);
        $this->assertEquals(2, $this->question->tags()->count());
    }

    public function test_updating_a_question_by_admin()
    {
        $adminUser = $this->getAdmin();
        $this->check_update_question($adminUser);
    }

    public function test_updating_a_question_by_not_admin_or_not_owner()
    {
        $user = User::factory()->hasProfile()->create();
        $response = $this->actingAs($user)->patch(
            route('questions.update', ['question' => $this->question->id]),
            [
                'title' => 'test',
                'detail' => 'test'
            ]
        );
        $this->assertDatabaseMissing('questions', [
            'title' => 'test',
            'detail' => 'test',
            'id' => $this->question->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_deleting_a_question_by_owner()
    {
        $response = $this->actingAs($this->user)->delete(route('questions.destroy',
                ['question' => $this->question->id])
        );

        $this->assertDatabaseMissing('questions', [
            'id' => $this->question->id
        ]);

        $response->assertRedirect(route('home'));
    }

    public function test_deleting_a_question_by_admin()
    {
        $question = Question::factory()->hasState()->for($this->user)->create();
        $adminUser = User::whereHas(
            'roles',
            function (Builder $query) {
                $query->where('title', Roles::Admin);
            }
        )->first();
        $response = $this->actingAs($adminUser)->delete(route('questions.destroy',
                ['question' => $question->id])
        );

        $this->assertDatabaseMissing('questions', [
            'id' => $question->id
        ]);

        $response->assertRedirect(route('home'));
    }

    public function test_deleting_a_question_by_not_admin_or_not_owner()
    {
        $question = Question::factory()->hasState()->for($this->user)->create();
        $user = User::factory()->hasProfile()->create();
        $response = $this->actingAs($user)->delete(route('questions.destroy',
                ['question' => $question->id])
        );

        $this->assertDatabaseHas('questions', [
            'id' => $question->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_add_or_remove_question_like_by_unauthorized_user()
    {
        $response = $this->post(route('questions.add.like',
                ['question' => $this->question->id])
        );

        $response->assertRedirect(route('login'));
    }

    public function test_add_or_remove_question_like_by_authorized_user()
    {
        $response = $this->actingAs($this->user)->post(route('questions.add.like',
                ['question' => $this->question->id])
        );

        $this->assertDatabaseHas('question_like', [
                'question_id' => $this->question->id, 'user_id' => $this->user->id]
        );

        $response->assertJson(fn(AssertableJson $json) =>
            $json->has('status')
        );

        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->actingAs($this->user)->post(route('questions.add.like',
                ['question' => $this->question->id])
        );

        $this->assertDatabaseMissing('question_like', [
                'question_id' => $this->question->id, 'user_id' => $this->user->id]
        );

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_add_question_view()
    {
        $views = $this->question->state->views;

        $response = $this->get(route('questions.show',
                ['question' => $this->question->id])
        );

        $this->question->refresh();

        $this->assertEquals($this->question->state->views, ++$views);
    }
}

