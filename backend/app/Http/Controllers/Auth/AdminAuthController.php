<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/admin/login",
     *     operationId="adminLogin",
     *     tags={"Admin Auth"},
     *     summary="管理者ログイン",
     *     description="管理者がメールアドレスとパスワードでログインし、認証トークンを取得します。",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ログイン成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|zzzzzz"),
     *             @OA\Property(property="type", type="string", example="admin")
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

        $admin = Admin::where('email', $credentials['email'])->first();

        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

        return response()->json([
            'token' => $token,
            'type' => 'admin',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/logout",
     *     operationId="adminLogout",
     *     tags={"Admin Auth"},
     *     summary="管理者ログアウト",
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
     *     path="/api/admin/me",
     *     operationId="getAdminInfo",
     *     tags={"Admin Auth"},
     *     summary="ログイン中の管理者情報を取得",
     *     description="現在のトークンに紐づく管理者情報を返します。",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="管理者情報",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="管理者太郎"),
     *             @OA\Property(property="email", type="string", example="admin@example.com")
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
