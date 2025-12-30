<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Http\Requests\Owner\UpdateOwnerRequest;
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
    public function update(UpdateOwnerRequest $request, Owner $owner)
    {
        $data = $request->validated();

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
