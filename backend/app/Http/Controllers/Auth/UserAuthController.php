<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserAuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/user/login",
     *     operationId="userLogin",
     *     tags={"User Auth"},
     *     summary="ユーザーログイン",
     *     description="ユーザーがメールアドレスとパスワードでログインし、認証トークンを取得します。",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ログイン成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|xxxxxx"),
     *             @OA\Property(property="type", type="string", example="user")
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
        $credentials = $request->only('email', 'password');

        if (! $token = Auth::guard('user')->attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('user')->factory()->getTTL() * 60,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/user/logout",
     *     operationId="userLogout",
     *     tags={"User Auth"},
     *     summary="ユーザーログアウト",
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
    public function logout()
    {
        Auth::guard('user')->logout();
        return response()->json(['message' => 'ログアウトしました']);
    }

    /**
     * @OA\Get(
     *     path="/api/user/me",
     *     operationId="getUserInfo",
     *     tags={"User Auth"},
     *     summary="ログイン中のユーザー情報を取得",
     *     description="現在のトークンに紐づくユーザー情報を返します。",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="ユーザー情報",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="テストユーザー"),
     *             @OA\Property(property="email", type="string", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="認証エラー"
     *     )
     * )
     */
    public function me()
    {
        return response()->json(Auth::guard('user')->user());
    }
}
