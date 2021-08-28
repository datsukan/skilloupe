<?php

namespace Tests\Feature;

use App\User;
use App\Qualification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowQualificationListApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // データベースマイグレーション
        $this->artisan('migrate');

        // テストユーザー作成
        $this->user = factory(User::class)->create();
        // テスト資格作成
        factory(Qualification::class)->create();
    }

    /**
     * @test
     */
    public function 指定したユーザーの資格情報を返却する()
    {
        $response = $this->actingAs($this->user)->json('GET', route('qualifications.index', ['skill' => $this->user->id]));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function 指定したユーザーIDが不正の場合にバリデーションエラーを返却する()
    {
        $response = $this->actingAs($this->user)->json('GET', route('qualifications.index', ['skill' => 'hoge']));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id']);
    }

    /**
     * @test
     */
    public function 存在しないユーザーIDを指定した場合にバリデーションエラーを返却する()
    {
        $response = $this->actingAs($this->user)->json('GET', route('qualifications.index', ['skill' => 100]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id']);
    }
}
