<?php
namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Http\Request;

interface IFileService {
    public function uploadUserAvatar(Request $request, User $user): mixed;
    public function delete(array|string $path): bool;
    public function copy(string $from, string $to): bool;
}