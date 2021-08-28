<?php

namespace Tests\Feature;

use App\User;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowProjectListApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $project;

    public function setUp(): void
    {
        parent::setUp();

        // データベースマイグレーション
        $this->artisan('migrate');

        // テストユーザー作成
        $this->user = factory(User::class)->create();
        // テストプロジェクト作成
        $this->project = factory(Project::class)->create();
    }

    /**
     * @test
     */
    public function 指定したユーザーのスキル：プロジェクト情報を返却する()
    {
        $response = $this->actingAs($this->user)->json('GET', route('projects.index', ['skill' => $this->user->id]));

        $response->assertStatus(200)
            ->assertJson([[
                'id'        => $this->project->id,
                'user_id'   => $this->user->id
            ]]);
    }

    /**
     * @test
     */
    public function 指定したユーザーIDが不正の場合にバリデーションエラーを返却する()
    {
        $response = $this->actingAs($this->user)->json('GET', route('projects.index', ['skill' => 'hoge']));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id']);
    }

    /**
     * @test
     */
    public function 存在しないユーザーIDを指定した場合にバリデーションエラーを返却する()
    {
        $response = $this->actingAs($this->user)->json('GET', route('projects.index', ['skill' => 100]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id']);
    }
}
