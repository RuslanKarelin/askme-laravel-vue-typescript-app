<?php
namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Http\Request;

interface IUserService {
    public function creatingUserDuringRegistration(array $data): User|null;
    public function updateUser(Request $request, User $user): User;
    public function destroyUser(User $user): bool;
}