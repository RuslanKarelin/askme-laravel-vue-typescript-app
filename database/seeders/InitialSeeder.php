<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\QuestionStatus;
use Illuminate\Support\Facades\Hash;
use App\Enums\QuestionStatuses;
use App\Enums\Roles;
use Illuminate\Support\Facades\Storage;

class InitialSeeder extends Seeder
{
    use WithoutModelEvents;

    private function createQuestionStatuses(): static
    {
        QuestionStatus::insert([
            ['title' => QuestionStatuses::Process],
            ['title' => QuestionStatuses::Resolved]
        ]);

        return $this;
    }

    private function createRoles(): static
    {
        Role::insert([
            ['title' => Roles::Admin],
            ['title' => Roles::User]
        ]);

        return $this;
    }

    private function createAdminUser(): static
    {
        $adminUser = User::factory()
            ->hasProfile(['avatar' => 'admin.jpeg'])
            ->create([
                'login' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('11111111')
            ]);

        Storage::disk('public')->copy(
            config('storage.avatars').'admin.jpeg',
            config('storage.avatars').$adminUser->id.'/admin.jpeg'
        );

        $adminRole = Role::select('id')->where('title', 'Admin')->first()?->id;

        if ($adminRole) {
            $adminUser->roles()->attach(Role::select('id')->where('title', 'Admin')->first()?->id);
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
        $this->createQuestionStatuses()
            ->createRoles()
            ->createAdminUser();
    }
}
