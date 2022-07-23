<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Question;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    private function checkingForTheOwnerOrAdministrator(User $user, Question $question)
    {
        return ($user->id === $question->user_id) || $user->is_administrator;
    }

    public function edit(User $user, Question $question)
    {
        return $this->checkingForTheOwnerOrAdministrator($user, $question);
    }

    public function update(User $user, Question $question)
    {
        return $this->checkingForTheOwnerOrAdministrator($user, $question);
    }

    public function destroy(User $user, Question $question)
    {
        return $this->checkingForTheOwnerOrAdministrator($user, $question);
    }
}
