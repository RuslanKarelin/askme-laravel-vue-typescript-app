<?php

namespace App\Services;

use App\Contracts\Services\IUserService;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Contracts\Services\IFileService;


class UserService implements IUserService
{
    private $fileService;

    public function __construct()
    {
        $this->fileService = app(IFileService::class);
    }

    public function updateUser(Request $request, User $user): User
    {
        DB::transaction(function() use ($request, $user) {
            $user->updateOrFail($request->validated());

            if ($request->has('profile')) {
                $profileFields = $request->get('profile');
                unset($profileFields['avatar']);
                $file = $this->fileService->uploadUserAvatar($request, $user);
                if ($file) $profileFields['avatar'] = $file->hashName();
                $user->profile()->update($profileFields);
            }
        }, 3);

        return $user;
    }

    public function destroyUser(User $user): bool
    {
        $userId =  $user->id;
        if ($user->delete()) {
            $this->fileService->delete(config('storage.avatars').$userId);
            return true;
        }

        return false;
    }

    public function creatingUserDuringRegistration(array $data): User|null
    {
        $user = null;
        DB::transaction(function () use (&$user, $data) {
            $user = User::create([
                'login' => $data['login'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $user->profile()->create(['avatar' => 'avatar.png']);
            $this->fileService->copy(
                config('storage.avatars').'avatar.png',
                config('storage.avatars').$user->id.'/avatar.png'
            );
        }, 3);

        return $user;
    }
}