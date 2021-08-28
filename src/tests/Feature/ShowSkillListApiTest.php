<?php

namespace Tests\Feature;

use App\User;
use App\Skill;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowSkillListApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $skill;

    public function setUp(): void
    {
        parent::setUp();

        // データベースマイグレーション
        $this->artisan('migrate');

        // テストユーザー作成
        $this->user = factory(User::class)->create();
        // テストスキル作成
        $this->skill = factory(Skill::class)->create();
    }

    /**
     * @test
     */
    public function 指定したユーザーのスキル情報を返却する()
    {
        $response = $this->actingAs($this->user)->json('GET', route('each-skills.index', ['skill' => $this->user->id]));

        $response->assertStatus(200)
            ->assertJson([[
                'id'        => $this->skill->id,
                'user_id'   => $this->user->id
            ]]);
    }

    /**
     * @test
     */
    public function 指定したユーザーIDが不正の場合にバリデーションエラーを返却する()
    {
        $response = $this->actingAs($this->user)->json('GET', route('each-skills.index', ['skill' => 'hoge']));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id']);
    }

    /**
     * @test
     */
    public function 存在しないユーザーIDを指定した場合にバリデーションエラーを返却する()
    {
        $response = $this->actingAs($this->user)->json('GET', route('each-skills.index', ['skill' => 100]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id']);
    }
}
