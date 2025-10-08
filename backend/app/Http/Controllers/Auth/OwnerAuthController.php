<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Owner;

class OwnerAuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/owner/login",
     *     operationId="ownerLogin",
     *     tags={"Owner Auth"},
     *     summary="オーナーログイン",
     *     description="オーナーがメールアドレスとパスワードでログインし、認証トークンを取得します。",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="owner@example.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ログイン成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|yyyyyy"),
     *             @OA\Property(property="type", type="string", example="owner")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="認証失敗",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $owner = Owner::where('email', $credentials['email'])->first();

        if (! $owner || ! Hash::check($credentials['password'], $owner->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = $owner->createToken('owner-token', ['owner'])->plainTextToken;

        return response()->json([
            'token' => $token,
            'type' => 'owner',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/owner/logout",
     *     operationId="ownerLogout",
     *     tags={"Owner Auth"},
     *     summary="オーナーログアウト",
     *     description="現在のアクセストークンを削除してログアウトします。",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="ログアウト成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    /**
     * @OA\Get(
     *     path="/api/owner/me",
     *     operationId="getOwnerInfo",
     *     tags={"Owner Auth"},
     *     summary="ログイン中のオーナー情報を取得",
     *     description="現在のトークンに紐づくオーナー情報を返します。",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="オーナー情報",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="テストオーナー"),
     *             @OA\Property(property="email", type="string", example="owner@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="認証エラー"
     *     )
     * )
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
