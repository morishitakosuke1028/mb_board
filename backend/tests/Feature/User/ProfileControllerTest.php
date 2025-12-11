<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function edit_returns_authenticated_user_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'user')
            ->getJson("/api/user/profile/{$user->id}/edit");

        $response->assertOk()
            ->assertExactJson([
                'id' => $user->id,
                'name' => $user->name,
                'kana' => $user->kana,
                'zip' => $user->zip,
                'address' => $user->address,
                'tel' => $user->tel,
                'email' => $user->email,
            ]);
    }

    #[Test]
    public function update_changes_profile_information_and_hashes_password(): void
    {
        $user = User::factory()->create();

        $payload = [
            'name' => '新しい名前',
            'kana' => 'シンカナ',
            'zip' => '123-4567',
            'address' => '新住所1-2-3',
            'tel' => '09012345678',
            'email' => 'new@example.com',
            'password' => 'newPassword123',
        ];

        $response = $this->actingAs($user, 'user')
            ->putJson("/api/user/profile/{$user->id}", $payload);

        $response->assertOk()
            ->assertJson([
                'message' => 'プロフィールを更新しました。',
                'user' => ['id' => $user->id]
            ]);

        $user->refresh();

        $this->assertSame($payload['name'], $user->name);
        $this->assertSame($payload['kana'], $user->kana);
        $this->assertSame($payload['zip'], $user->zip);
        $this->assertSame($payload['address'], $user->address);
        $this->assertSame($payload['tel'], $user->tel);
        $this->assertSame($payload['email'], $user->email);
        $this->assertTrue(Hash::check($payload['password'], $user->password));
    }

    #[Test]
    public function destroy_soft_deletes_the_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'user')
            ->deleteJson("/api/user/profile/{$user->id}");

        $response->assertOk()
            ->assertJson([
                'message' => 'アカウントは退会処理されました',
                'status' => 'deleted',
            ]);

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}
