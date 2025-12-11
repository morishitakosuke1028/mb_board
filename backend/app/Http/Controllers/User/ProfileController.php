<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\User\UpdateProfileRequest;

class ProfileController extends Controller
{
    /**
     * 現在ログイン中のユーザー情報を返す
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        return response()->json(
            $user->only(['id', 'name', 'kana', 'zip', 'address', 'tel', 'email'])
        );
    }

    /**
     * プロフィール更新
     */
    public function update(UpdateProfileRequest $request, User $user)
    {
        $user->updateProfile($request->validated());

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

        $user->delete();

        return response()->json([
            'message' => 'アカウントは退会処理されました',
            'status'  => 'deleted'
        ]);
    }
}
