<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    private function checkingForTheOwnerOrAdministrator(User $user, Comment $comment)
    {
        return ($user->id === $comment->user_id) || $user->isAdministrator();
    }

    public function update(User $user, Comment $comment)
    {
        return $this->checkingForTheOwnerOrAdministrator($user, $comment);
    }

    public function destroy(User $user, Comment $comment)
    {
        return $this->checkingForTheOwnerOrAdministrator($user, $comment);
    }
}
