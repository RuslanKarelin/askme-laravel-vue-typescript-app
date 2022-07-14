<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    private function checkingForTheOwnerOrAdministrator(User $currentUser, User $user)
    {
        return ($currentUser->id === $user->id) || $currentUser->isAdministrator();
    }

    public function edit(User $currentUser, User $user)
    {
        return $this->checkingForTheOwnerOrAdministrator($currentUser, $user);
    }

    public function update(User $currentUser, User $user)
    {
        return $this->checkingForTheOwnerOrAdministrator($currentUser, $user);
    }

    public function destroy(User $currentUser, User $user)
    {
        return $this->checkingForTheOwnerOrAdministrator($currentUser, $user);
    }
}
