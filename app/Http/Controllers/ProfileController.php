<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->safe()->only(['name', 'email']);

        if ($request->boolean('remove_avatar') && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->wasChanged('email')) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('success', __('Profile updated successfully.'));
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => $request->validated('password'),
        ]);

        return back()->with('success', __('Password changed successfully.'));
    }
}
