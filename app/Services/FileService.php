<?php

namespace App\Services;

use App\Contracts\Services\IFileService;
use App\Models\User;
use Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class FileService implements IFileService
{
    public function uploadUserAvatar(Request $request, User $user): mixed
    {
        if ($request->hasFile('profile.avatar') && !empty($request->file('profile.avatar'))) {
            $file = $request->file('profile.avatar');
            $filename = $file->hashName();
            $image = Image::make($file)->resize(70, 70, function ($const) {
                $const->aspectRatio();
            });
            //Storage::disk('public')->put(config('storage.avatars').$user->id.'/'.$filename, $image);
            Storage::put(config('storage.avatars').$user->id.'/'.$filename, $image);
            return $file;
        }

        return null;
    }

    public function delete(array|string $path): bool
    {
        return Storage::delete($path);
    }

    public function copy(string $from, string $to): bool
    {
        return Storage::disk('public')->copy(
            $from,
            $to
        );
    }
}