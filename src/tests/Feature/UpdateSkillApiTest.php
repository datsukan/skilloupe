<?php

namespace Tests\Feature;

use App\User;
use App\Skill;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class UpdateSkillApiTest extends TestCase
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
    }

    /**
     * @test
     */
    public function スキルを空登録する()
    {
        $data = [];

        $data['skill_list'] = [];

        $response = $this->actingAs($this->user)->json('PUT', route('each-skills.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function スキルを登録する()
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data['skill_list'][] = [
                'name'              => Str::random(random_int(5, 50)),
                'type'              => Str::random(random_int(5, 50)),
                'level'             => random_int(0, 5),
                'experience_period' => random_int(0, 100),
                'tool'              => [Str::random(random_int(3, 10))],
                'platform'          => [Str::random(random_int(3, 10))],
                'system'            => [Str::random(random_int(3, 10))],
                'experience_detail' => Str::random(random_int(10, 1000)),
            ];
        }

        $response = $this->actingAs($this->user)->json('PUT', route('each-skills.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function スキルを更新する()
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data['skill_list'][] = [
                'name'              => Str::random(random_int(5, 50)),
                'type'              => Str::random(random_int(5, 50)),
                'level'             => random_int(0, 5),
                'experience_period' => random_int(0, 100),
                'tool'              => [Str::random(random_int(3, 10))],
                'platform'          => [Str::random(random_int(3, 10))],
                'system'            => [Str::random(random_int(3, 10))],
                'experience_detail' => Str::random(random_int(10, 1000)),
            ];
        }

        $firstResponse = $this->actingAs($this->user)->json('PUT', route('each-skills.update', ['skill' => $this->user->id]), $data);

        $firstResponse->assertStatus(201);

        $skills = User::find((int) $this->user->id)->skill;

        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data['skill_list'][] = [
                'name'              => Str::random(random_int(5, 50)),
                'type'              => Str::random(random_int(5, 50)),
                'level'             => random_int(0, 5),
                'experience_period' => random_int(0, 100),
                'tool'              => [Str::random(random_int(3, 10))],
                'platform'          => [Str::random(random_int(3, 10))],
                'system'            => [Str::random(random_int(3, 10))],
                'experience_detail' => Str::random(random_int(10, 1000)),
            ];
        }

        $secondResponse = $this->actingAs($this->user)->json('PUT', route('each-skills.update', ['skill' => $this->user->id]), $data);

        $secondResponse->assertStatus(201);
    }


    /**
     * @test
     */
    public function スキルを削除する()
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data['skill_list'][] = [
                'name'              => Str::random(random_int(5, 50)),
                'type'              => Str::random(random_int(5, 50)),
                'level'             => random_int(0, 5),
                'experience_period' => random_int(0, 100),
                'tool'              => [Str::random(random_int(3, 10))],
                'platform'          => [Str::random(random_int(3, 10))],
                'system'            => [Str::random(random_int(3, 10))],
                'experience_detail' => Str::random(random_int(10, 1000)),
            ];
        }

        $firstResponse = $this->actingAs($this->user)->json('PUT', route('each-skills.update', ['skill' => $this->user->id]), $data);

        $firstResponse->assertStatus(201);

        $skills = User::find((int) $this->user->id)->skill;

        $data = [];

        $data['skill_list'] = [];

        $secondResponse = $this->actingAs($this->user)->json('PUT', route('each-skills.update', ['skill' => $this->user->id]), $data);

        $secondResponse->assertStatus(201);
    }

    /**
     * @test
     */
    public function 管理権限がないユーザーが自分以外のスキルを登録する場合は認可エラーを返却する()
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

        // 管理権限 OFFのユーザーでスキル：プロジェクト更新
        $user = User::where('email', $userData['email'])->first();

        $data = [];

        $data['skill_list'] = [];

        $response = $this->actingAs($user)->json('PUT', route('each-skills.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function 管理権限があるユーザーが自分以外のスキルを登録する場合は認可エラーを返却しない()
    {
        // 管理権限 OFFのユーザーを登録
        $userData = [
            'name'                  => 'vuesplash user',
            'email'                 => 'dummy@email.com',
            'password'              => 'test1234',
            'password_confirmation' => 'test1234',
            'is_readonly'           => 0,
            'is_manage'             => 1,
        ];

        $userResponse = $this->actingAs($this->user)
            ->json('POST', route('users.register'), $userData);

        $userResponse
            ->assertStatus(201)
            ->assertJson(['name' => $userData['name']]);

        // 管理権限 OFFのユーザーでスキル：プロジェクト更新
        $user = User::where('email', $userData['email'])->first();

        $data = [];

        $data['skill_list'] = [];

        $response = $this->actingAs($user)->json('PUT', route('each-skills.update', ['skill' => $this->user->id]), $data);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function 管理権限がないユーザーが自分のスキルを登録する場合は認可エラーを返却しない()
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

        // 管理権限 OFFのユーザーでスキル：プロジェクト更新
        $user = User::where('email', $userData['email'])->first();

        $data = [];

        $data['skill_list'] = [];

        $response = $this->actingAs($user)->json('PUT', route('each-skills.update', ['skill' => $user->id]), $data);

        $response->assertStatus(201);
    }
}
