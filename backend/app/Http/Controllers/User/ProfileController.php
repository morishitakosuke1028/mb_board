<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * 現在ログイン中のユーザー情報を返す
     */
    public function edit(Request $request)
    {
        return response()->json([
            'id'    => $request->user()->id,
            'name'  => $request->user()->name,
            'email' => $request->user()->email,
        ]);
    }

    /**
     * プロフィール更新
     */
    public function update(Request $request, User $user)
    {
        // 認可チェック（他人を更新できないように）
        if ($user->id !== $request->user()->id) {
            return response()->json(['message' => '権限がありません'], 403);
        }

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'min:6'],
        ]);

        $user->name = $validated['name'];
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
