<?php

namespace Tests\Feature;

use App\User;
use App\Profile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileDetailApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $profile;

    public function setUp(): void
    {
        parent::setUp();

        // データベースマイグレーション
        $this->artisan('migrate');

        // テストユーザー作成
        $this->user = factory(User::class)->create();
        // テストプロフィール作成
        $this->profile = factory(Profile::class)->create();
    }

    /**
     * @test
     */
    public function 指定したユーザーのプロフィール情報を返却する()
    {
        $response = $this->actingAs($this->user)->json('GET', route('skills.show', ['skill' => $this->user->id]));

        $response->assertStatus(200)
            ->assertJson([
                'user'       => ['id' => $this->user->id],
                'profile'    => ['id' => $this->profile->id]
            ]);
    }

    /**
     * @test
     */
    public function 指定したユーザーIDが不正の場合にバリデーションエラーを返却する()
    {
        $response = $this->actingAs($this->user)->json('GET', route('skills.show', ['skill' => 'hoge']));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id']);
    }

    /**
     * @test
     */
    public function 存在しないユーザーIDを指定した場合にバリデーションエラーを返却する()
    {
        $response = $this->actingAs($this->user)->json('GET', route('skills.show', ['skill' => 100]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id']);
    }
}
