<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Contracts\Services\IUserService;
use App\Contracts\Services\IQuestionService;

class UserController extends Controller
{
    public function __construct(
        private IUserService $userService,
        private IQuestionService $questionService

    ){}

    public function show(Request $request, User $user)
    {
        return $this->userService->show($request, $user)->view();
    }

    public function edit(Request $request, User $user)
    {
        return $this->userService->edit($request, $user)->view();
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

    public function questions(Request $request, User $user)
    {
        return $this->questionService->getUserQuestions($request, $user)->view();
    }

    public function questionsThroughAnswers(Request $request, User $user)
    {
        return $this->questionService->getUserQuestionThroughAnswers($request, $user)->view();
    }
}
