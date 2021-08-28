<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserDeleteApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // データベースマイグレーション
        $this->artisan('migrate');

        // テストユーザー作成
        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function ユーザー情報を削除する()
    {
        $response = $this->actingAs($this->user)
            ->json('DELETE', route('users.destroy', ['id' => $this->user->id]));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function 管理権限がないユーザーの場合は認可エラーを返却する()
    {
        // 管理権限 OFFのユーザーを登録
        $userData = [
            'name'                  => 'vuesplash user',
            'email'                 => 'dummy@email.com',
            'password'              => 'test1234',
            'password_confirmation' => 'test1234',
            'is_readonly'           => 0,
            'is_manage'             => 0,
        ];

        $userResponse = $this->actingAs($this->user)
            ->json('POST', route('users.register'), $userData);

        $userResponse
            ->assertStatus(201)
            ->assertJson(['name' => $userData['name']]);

        // 管理権限 OFFのユーザーでユーザー削除
        $user = User::where('email', $userData['email'])->first();

        $response = $this->actingAs($user)
            ->json('DELETE', route('users.destroy', ['id' => $this->user->id]));

        $response
            ->assertStatus(403);
    }
}
