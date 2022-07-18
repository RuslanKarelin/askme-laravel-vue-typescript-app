<?php
namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Http\Request;
use App\Contracts\Helpers\Response\IResponseHelper;

interface IUserService {
    public function creatingUserDuringRegistration(array $data): User|null;
    public function edit(Request $request, User $user): IResponseHelper;
    public function updateUser(Request $request, User $user): User;
    public function show(Request $request, User $user): IResponseHelper;
    public function destroyUser(User $user): bool;
}