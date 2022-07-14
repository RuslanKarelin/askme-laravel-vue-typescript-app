<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show(User $user)
    {
        //
    }

    public function edit(Request $request, User $user)
    {

    }

    public function update(UserRequest $request, User $user)
    {
        DB::transaction(function() use ($request, $user) {
            $user->updateOrFail($request->validated());

            if ($request->has('profile')) {
                $profileFields = $request->get('profile');
                unset($profileFields['avatar']);
                if ($request->hasFile('profile.avatar') && !empty($request->file('profile.avatar'))) {
                    $file = $request->file('profile.avatar');
                    $filename = $file->hashName();
                    $image = Image::make($file)->resize(70, 70, function ($const) {
                        $const->aspectRatio();
                    });
                    //Storage::disk('public')->put(config('storage.avatars').$user->id.'/'.$filename, $image);
                    Storage::put(config('storage.avatars').$user->id.'/'.$filename, $image);
                    $profileFields['avatar'] = $filename;
                }

                $user->profile()->update($profileFields);
            }
        }, 3);

        return response()->redirectToRoute('users.profile.edit', ['user' => $user->id]);
    }

    public function destroy(Request $request, User $user)
    {
        $userId =  $user->id;
        if ($user->delete()) {
            Storage::delete(config('storage.avatars').$userId);
        }

        return response()->redirectToRoute('home');
    }
}
