<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Database\Seeders\InitialSeeder;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\Roles;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected $seeder = InitialSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->hasProfile()->create();
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

    protected function check_update_user($user)
    {
        $response = $this->actingAs($user)->patch(
            route('users.profile.update', ['user' => $this->user->id]),
            [
                'email' => 'test@mail.ru',
                'password' => '12345678',
                'password_confirmation' => '12345678',
                'profile' => [
                    'first_name' => 'Test',
                    'last_name' => 'Test',
                    'about' => 'Test about',
                ],
            ]
        );
        $this->assertDatabaseHas('users', [
            'email' => 'test@mail.ru',
            'id' => $this->user->id
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'first_name' => 'Test',
            'last_name' => 'Test',
            'about' => 'Test about',
            'user_id' => $this->user->id
        ]);

        $response->assertRedirect(route('users.profile.edit', ['user' => $this->user->id]));
    }

    public function test_show_user_status_ok()
    {
        $response = $this->get(route('users.profile.show', ['user' => $this->user->id]));

        $response->assertOk();
    }

    public function test_ban_on_editing_a_user_to_an_unauthorized_user()
    {
        $response = $this->get(route('users.profile.edit', ['user' => $this->user->id]));

        $response->assertRedirect(route('login'));
    }

    public function test_access_is_allowed_to_an_authorized_user_to_the_user_editing_page()
    {
        $response = $this->actingAs($this->user)->get(route('users.profile.edit', ['user' => $this->user->id]));

        $response->assertOk();

        $adminUser = $this->getAdmin();

        $response = $this->actingAs($adminUser)->get(route('users.profile.edit', ['user' => $this->user->id]));

        $response->assertOk();
    }

    public function test_ban_editing_a_user_by_not_admin_or_not_owner()
    {
        $user = User::factory()->hasProfile()->create();
        $response = $this->actingAs($user)->get(route('users.profile.edit', ['user' => $this->user->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_validation_check_when_updating_a_user()
    {
        $response = $this->actingAs($this->user)->patch(
            route(
                'users.profile.update',
                ['user' => $this->user->id]
            ),
            [
                'email' => '',
            ]
        );

        $this->assertDatabaseMissing('users', [
            'email' => '',
            'id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->patch(
            route(
                'users.profile.update',
                ['user' => $this->user->id]
            ),
            [
                'password' => '',
            ]
        );

        $this->assertDatabaseMissing('users', [
            'password' => '',
            'id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->patch(
            route(
                'users.profile.update',
                ['user' => $this->user->id]
            ),
            [
                'email' => 'test@mail2.ru',
                'password' => '1234567',
            ]
        );

        $this->assertDatabaseMissing('users', [
            'email' => 'test@mail2.ru',
            'id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->patch(
            route(
                'users.profile.update',
                ['user' => $this->user->id]
            ),
            [
                'email' => 'test@mail2.ru',
                'password' => '12345678',
                'password_confirmation' => '12345679',
            ]
        );

        $this->assertDatabaseMissing('users', [
            'email' => 'test@mail2.ru',
            'id' => $this->user->id
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_updating_a_user_by_owner()
    {
        $this->check_update_user($this->user);
    }

    public function test_updating_a_user_by_admin()
    {
        $adminUser = User::whereHas(
            'roles',
            function (Builder $query) {
                $query->where('title', Roles::Admin);
            }
        )->first();
        $this->check_update_user($adminUser);
    }

    public function test_updating_a_user_by_not_admin_or_not_owner()
    {
        $user = User::factory()->hasProfile()->create();
        $response = $this->actingAs($user)->patch(
            route('users.profile.update', ['user' => $this->user->id]),
            [
                'email' => 'test@mail.ru',
                'password' => '12345678',
                'password_confirmation' => '12345678',
            ]
        );
        $this->assertDatabaseMissing('users', [
            'email' => 'test@mail.ru',
            'id' => $this->user->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /*public function test_updating_a_user_with_upload_avatar()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($this->user)->patch(
            route('users.profile.update', ['user' => $this->user->id]),
            [
                'email' => 'test@mail.ru',
                'password' => '12345678',
                'password_confirmation' => '12345678',
                'profile' => [
                    'avatar' => $file,
                ],
            ]
        );
        $this->assertDatabaseHas('users', [
            'email' => 'test@mail.ru',
            'id' => $this->user->id
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $this->user->id,
            'avatar' => $file->hashName()
        ]);

        Storage::disk('public')->assertExists(config('storage.avatars').$this->user->id.'/'.$file->hashName());

        $response->assertRedirect(route('users.profile.edit', ['user' => $this->user->id]));
    }*/

    public function test_deleting_a_user_by_owner()
    {
        $response = $this->actingAs($this->user)->delete(route('users.profile.destroy',
                ['user' => $this->user->id])
        );

        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id
        ]);

        $response->assertRedirect(route('home'));
    }

    public function test_deleting_a_user_by_admin()
    {
        $user = User::factory()->hasProfile()->create();
        $adminUser = $this->getAdmin();
        $response = $this->actingAs($adminUser)->delete(route('users.profile.destroy',
                ['user' => $user->id])
        );

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);

        $response->assertRedirect(route('home'));
    }

    public function test_deleting_a_user_by_not_admin_or_not_owner()
    {
        $user = User::factory()->hasProfile()->create();
        $response = $this->actingAs($user)->delete(route('users.profile.destroy',
                ['user' => $this->user->id])
        );

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}

