<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UpdateProfileRequest;

class ProfileController extends Controller
{
    /**
     * 現在ログイン中のユーザー情報を返す
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id'      => $user->id,
            'name'    => $user->name,
            'kana'    => $user->kana,
            'zip'     => $user->zip,
            'address' => $user->address,
            'tel'     => $user->tel,
            'email'   => $user->email,
        ]);
    }

    /**
     * プロフィール更新
     */
    public function update(UpdateProfileRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->kana = $validated['kana'];
        $user->zip = $validated['zip'];
        $user->address = $validated['address'];
        $user->tel = $validated['tel'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'message' => 'プロフィールを更新しました。',
            'user' => $user
        ]);
    }

    /**
     * 退会（SoftDelete）
     */
    public function destroy(Request $request, User $user)
    {
        if ($user->id !== $request->user()->id) {
            return response()->json(['message' => '権限がありません'], 403);
        }

        $user->delete(); // ← SoftDelete発動

        return response()->json([
            'message' => 'アカウントは退会処理されました',
            'status'  => 'deleted'
        ]);
    }
}
