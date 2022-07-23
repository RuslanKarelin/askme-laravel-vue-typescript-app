<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Answer;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnswerPolicy
{
    use HandlesAuthorization;

    private function checkingForTheOwnerOrAdministrator(User $user, Answer $answer)
    {
        return ($user->id === $answer->user_id) || $user->is_administrator;
    }

    public function update(User $user, Answer $answer)
    {
        return $this->checkingForTheOwnerOrAdministrator($user, $answer);
    }

    public function destroy(User $user, Answer $answer)
    {
        return $this->checkingForTheOwnerOrAdministrator($user, $answer);
    }
}
