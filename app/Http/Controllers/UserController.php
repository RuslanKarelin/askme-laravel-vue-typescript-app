<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Contracts\Services\IUserService;

class UserController extends Controller
{
    public function __construct(
        private IUserService $userService
    ){}

    public function show(User $user)
    {
        //
    }

    public function edit(Request $request, User $user)
    {

    }

    public function update(UserRequest $request, User $user)
    {
        $user = $this->userService->updateUser($request, $user);
        return response()->redirectToRoute('users.profile.edit', ['user' => $user->id]);
    }

    public function destroy(Request $request, User $user)
    {
        $this->userService->destroyUser($user);
        return response()->redirectToRoute('home');
    }
}
