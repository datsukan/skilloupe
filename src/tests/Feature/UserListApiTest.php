<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Seeder;

class UserListApiTest extends TestCase
{
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
    public function 登録済みのユーザーリストを返却する()
    {
        $response = $this->actingAs($this->user)->json('GET', route('users.reference'));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function 未認証の場合にエラーを返却する()
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])->json('GET', route('users.reference'));

        $response->assertStatus(401);
    }
}
