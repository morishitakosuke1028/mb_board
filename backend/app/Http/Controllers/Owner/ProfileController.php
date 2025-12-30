<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * 現在ログイン中のオーナー情報を返す
     */
    public function edit(Request $request)
    {
        $owner = $request->user();

        return response()->json(
            $owner->only([
                'id',
                'name',
                'kana',
                'company_name',
                'company_kana',
                'contact_zip',
                'contact_address',
                'contact_tel',
                'secret_zip',
                'secret_address',
                'secret_tel',
                'email',
            ])
        );
    }

    /**
     * プロフィール更新
     */
    public function update(Request $request, Owner $owner)
    {
        if ($owner->id !== $request->user()->id) {
            return response()->json(['message' => '権限がありません'], 403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'kana' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_kana' => ['nullable', 'string', 'max:255'],
            'contact_zip' => ['nullable', 'string', 'max:20'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'contact_tel' => ['nullable', 'string', 'max:20', 'unique:owners,contact_tel,' . $owner->id],
            'secret_zip' => ['required', 'string', 'max:20'],
            'secret_address' => ['required', 'string', 'max:255'],
            'secret_tel' => ['required', 'string', 'max:20', 'unique:owners,secret_tel,' . $owner->id],
            'email' => ['required', 'email', 'max:255', 'unique:owners,email,' . $owner->id],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $owner->update($data);

        return response()->json([
            'message' => 'プロフィールを更新しました。',
            'owner' => $owner->fresh(),
        ]);
    }

    /**
     * 退会（SoftDelete）
     */
    public function destroy(Request $request, Owner $owner)
    {
        if ($owner->id !== $request->user()->id) {
            return response()->json(['message' => '権限がありません'], 403);
        }

        $owner->delete();

        return response()->json([
            'message' => 'アカウントは退会処理されました',
            'status' => 'deleted',
        ]);
    }
}
