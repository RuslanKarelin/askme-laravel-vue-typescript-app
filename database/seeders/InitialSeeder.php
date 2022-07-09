<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\QuestionStatus;
use Illuminate\Support\Facades\Hash;

class InitialSeeder extends Seeder
{
    use WithoutModelEvents;

    private function createRoles(): static
    {
        QuestionStatus::insert([
            ['title' => 'Process'],
            ['title' => 'Resolved']
        ]);

        Role::insert([
            ['title' => 'Admin'],
            ['title' => 'User']
        ]);

        Permission::insert([
            ['title' => 'create_question'],
            ['title' => 'store_question'],
            ['title' => 'edit_question'],
            ['title' => 'update_question'],
            ['title' => 'create_answer'],
            ['title' => 'store_answer'],
            ['title' => 'edit_answer'],
            ['title' => 'update_answer'],
            ['title' => 'create_comment'],
            ['title' => 'store_comment'],
            ['title' => 'edit_comment'],
            ['title' => 'update_comment']
        ]);

        Role::select('id')->first('title', 'User')->permissions()->attach(
            Permission::select('id')->pluck('id')->toArray()
        );

        Permission::insert([
            ['title' => 'delete_question'],
            ['title' => 'delete_answer'],
            ['title' => 'delete_comment'],
        ]);

        Role::select('id')->first('title', 'Admin')->permissions()->attach(
            Permission::select('id')->pluck('id')->toArray()
        );

        return $this;
    }

    private function createAdminUser(): static
    {
        $adminUser = User::factory()
            ->hasProfile(1)
            ->create([
                'login' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('11111111')
            ]);

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
        $this->createRoles()
            ->createAdminUser();
    }
}
